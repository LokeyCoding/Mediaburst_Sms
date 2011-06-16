<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 * Event Observer
 */
class MediaBurst_Sms_Model_Observer
{

    public function createOrderCreatedMessage(Varien_Event_Observer $observer)
    {
        $order = $observer->getOrder();
        if ($order instanceof Mage_Sales_Model_Order) {
            /* @var $order Mage_Sales_Model_Order */
            if (!$this->getHelper()->isOrderCreatedActive($order->getStoreId())) {
                return;
            }
            try {
                $message = Mage::getModel('MediaBurst_Sms/Message');
                $message->setStoreId($order->getStoreId());
                $message->setTo($this->getHelper()->getOrderCreatedTo());
                $message->setFrom($this->getHelper()->getOrderCreatedFrom());
                $message->setContent($this->getHelper()->generateOrderCreatedContent($order));
                $message->save();
            } catch (Exception $e) {
                $this->getHelper()->log('Error creating Order Created SMS Message Record for Order ' . $order->getIncrementId(), Zend_Log::ERR);
            }
        }
    }

    public function createOrderShippedMessage(Varien_Event_Observer $observer)
    {
        $shipment = $observer->getShipment();
        if ($shipment instanceof Mage_Sales_Model_Order_Shipment) {
            /* @var $shipment Mage_Sales_Model_Order_Shipment */
            $order = $shipment->getOrder();
            if (!$this->getHelper()->isOrderShippedActive($order->getStoreId())) {
                return;
            }
            try {
                $message = Mage::getModel('MediaBurst_Sms/Message');
                $message->setStoreId($order->getStoreId());
                $message->setTo($this->getHelper()->getTelephone($order));
                $message->setFrom($this->getHelper()->getOrderShippedFrom());
                $message->setContent($this->getHelper()->generateOrderShippedContent($order, $shipment));
                $message->save();
            } catch (Exception $e) {
                $this->getHelper()->log('Error creating Order Shipped SMS Message Record for Order ' . $order->getIncrementId(), Zend_Log::ERR);
            }
        }
    }

    public function createOrderHeldMessage(Varien_Event_Observer $observer)
    {
        $order = $observer->getOrder();
        if ($order instanceof Mage_Sales_Model_Order) {
            /* @var $order Mage_Sales_Model_Order */
            if (!$this->getHelper()->isOrderHeldActive($order->getStoreId())) {
                return;
            }
            if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_HOLDED) {
                try {
                    $message = Mage::getModel('MediaBurst_Sms/Message');
                    $message->setStoreId($order->getStoreId());
                    $message->setTo($this->getHelper()->getTelephone($order));
                    $message->setFrom($this->getHelper()->getOrderHeldFrom());
                    $message->setContent($this->getHelper()->generateOrderHeldContent($order));
                    $message->save();
                } catch (Exception $e) {
                    $this->getHelper()->log('Error creating Order Held SMS Message Record for Order ' . $order->getIncrementId(), Zend_Log::ERR);
                }
            }
        }
    }

    public function createOrderUnheldMessage(Varien_Event_Observer $observer)
    {
        $order = $observer->getOrder();
        if ($order instanceof Mage_Sales_Model_Order) {
            /* @var $order Mage_Sales_Model_Order */
            if (!$this->getHelper()->isOrderUnheldActive($order->getStoreId())) {
                return;
            }
            if ($order->getState() !== $order->getOrigData('state') && $order->getOrigData('state') === Mage_Sales_Model_Order::STATE_HOLDED) {
                try {
                    $message = Mage::getModel('MediaBurst_Sms/Message');
                    $message->setStoreId($order->getStoreId());
                    $message->setTo($this->getHelper()->getTelephone($order));
                    $message->setFrom($this->getHelper()->getOrderUnheldFrom());
                    $message->setContent($this->getHelper()->generateOrderUnheldContent($order));
                    $message->save();
                } catch (Exception $e) {
                    $this->getHelper()->log('Error creating Order Held SMS Message Record for Order ' . $order->getIncrementId(), Zend_Log::ERR);
                }
            }
        }
    }

    /**
     * Cron Job
     */
    public function sendPendingMessages($session = null)
    {
        $runs = array();

        $stores = Mage::app()->getStores();
        foreach ($stores as $store) {
            if ($this->getHelper()->isActive($store)) {
                $username = $this->getHelper()->getUsername($store);
                $password = $this->getHelper()->getPassword($store);
                $url = $this->getHelper()->getSendUrl($store);
                $hash = md5($username . ':' . $password . ':' . $url);

                if (!isset($runs[$hash])) {
                    $runs[$hash] = array(
                        'username' => $username,
                        'password' => $password,
                        'url' => $url,
                        'stores' => array()
                    );
                }

                $runs[$hash]['stores'][] = $store->getId();
            }
        }

        $api = Mage::getModel('MediaBurst_Sms/Api', $this->getHelper());
        /* @var $api MediaBurst_Sms_Model_Api */

        foreach ($runs as $run) {
            $collection = Mage::getModel('MediaBurst_Sms/Message')->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('status', MediaBurst_Sms_Model_Message::STATUS_PENDING)
                ->addFieldToFilter('store_id', $run['stores'])
                ->setPageSize(MediaBurst_Sms_Model_Api::SMS_PER_REQUEST_LIMIT);

            Mage::dispatchEvent('mediaburst_sms_send_pending_before', array('collection' => $collection));

            $this->getHelper()->setDefaultStore(reset($run['stores']));

            $results = $api->sendMessages($collection->getItems());

            if ($session instanceof Mage_Core_Model_Session_Abstract) {
                $this->getHelper()->reportResults($session, $results);
            }
        }

        $this->getHelper()->setDefaultStore(null);
    }

    /**
     *
     * @return MediaBurst_Sms_Helper_Data
     */
    public function getHelper()
    {
        return Mage::helper('MediaBurst_Sms/Data');
    }

}