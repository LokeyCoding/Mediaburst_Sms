<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 * Helper
 */
class MediaBurst_Sms_Helper_Data extends Mage_Core_Helper_Abstract implements MediaBurst_Sms_Model_ApiConfig
{
    const XML_CONFIG_BASE_PATH = 'mediaburst_sms/';

    protected $_defaultStore = null;

    public function setDefaultStore($store)
    {
        $this->_defaultStore = $store;
    }

    public function isActive($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfigFlag(self::XML_CONFIG_BASE_PATH . 'general/active', $store);
    }

    public function getCheckUrl($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'general/check_url', $store);
    }

    public function getBuyUrl($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'general/buy_url', $store);
    }

    public function getSendUrl($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'general/send_url', $store);
    }

    public function getUsername($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'general/username', $store);
    }

    public function getPassword($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'general/password', $store);
    }

    public function isDebug($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfigFlag(self::XML_CONFIG_BASE_PATH . 'general/debug', $store);
    }

    public function log($message, $level = Zend_Log::DEBUG, $store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        if ($message instanceof Exception) {
            $message = "\n" . $message->__toString();
            $level = Zend_Log::ERR;
            $file = Mage::getStoreConfig('dev/log/exception_file', $store);
        } else {
            if (is_array($message) || is_object($message)) {
                $message = print_r($message, true);
            }
            $file = Mage::getStoreConfig('dev/log/file', $store);
        }

        if ($level < Zend_Log::DEBUG || $this->isDebug($store)) {
            $force = ($level <= Zend_Log::ERR);
            Mage::log($message, $level, $file, $force);
        }
    }

    public function isOrderCreatedActive($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return $this->isActive($store) && Mage::getStoreConfigFlag(self::XML_CONFIG_BASE_PATH . 'order_created/active', $store);
    }

    public function getOrderCreatedTo($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'order_created/to', $store);
    }

    public function getOrderCreatedFrom($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'order_created/from', $store);
    }

    public function getOrderCreatedContent($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'order_created/content', $store);
    }

    public function isOrderHeldActive($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return $this->isActive($store) && Mage::getStoreConfigFlag(self::XML_CONFIG_BASE_PATH . 'order_held/active', $store);
    }

    public function getOrderHeldFrom($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'order_held/from', $store);
    }

    public function getOrderHeldContent($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'order_held/content', $store);
    }

    public function isOrderUnheldActive($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return $this->isActive($store) && Mage::getStoreConfigFlag(self::XML_CONFIG_BASE_PATH . 'order_unheld/active', $store);
    }

    public function getOrderUnheldFrom($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'order_unheld/from', $store);
    }

    public function getOrderUnheldContent($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'order_unheld/content', $store);
    }

    public function isOrderShippedActive($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return $this->isActive($store) && Mage::getStoreConfigFlag(self::XML_CONFIG_BASE_PATH . 'order_shipped/active', $store);
    }

    public function getOrderShippedFrom($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'order_shipped/from', $store);
    }

    public function getOrderShippedContent($store = null)
    {
        if ($store === null) {
            $store = $this->_defaultStore;
        }

        return Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'order_shipped/content', $store);
    }

    public function generateOrderCreatedContent(Mage_Sales_Model_Order $order)
    {
        $filter = Mage::getModel('core/email_template_filter');
        $filter->setPlainTemplateMode(true);
        $filter->setStoreId($order->getStoreId());
        $filter->setVariables(array('order' => $order));
        return $filter->filter($this->getOrderCreatedContent($order->getStoreId()));
    }

    public function generateOrderShippedContent(Mage_Sales_Model_Order $order, Mage_Sales_Model_Order_Shipment $shipment)
    {
        $filter = Mage::getModel('core/email_template_filter');
        $filter->setPlainTemplateMode(true);
        $filter->setStoreId($order->getStoreId());
        $filter->setVariables(array('order' => $order, 'shipment' => $shipment));
        return $filter->filter($this->getOrderShippedContent($order->getStoreId()));
    }

    public function generateOrderHeldContent(Mage_Sales_Model_Order $order)
    {
        $filter = Mage::getModel('core/email_template_filter');
        $filter->setPlainTemplateMode(true);
        $filter->setStoreId($order->getStoreId());
        $filter->setVariables(array('order' => $order));
        return $filter->filter($this->getOrderHeldContent($order->getStoreId()));
    }

    public function generateOrderUnheldContent(Mage_Sales_Model_Order $order)
    {
        $filter = Mage::getModel('core/email_template_filter');
        $filter->setPlainTemplateMode(true);
        $filter->setStoreId($order->getStoreId());
        $filter->setVariables(array('order' => $order));
        return $filter->filter($this->getOrderUnheldContent($order->getStoreId()));
    }

    /**
     * Convert a result array into a series of session messages
     *
     * @param Mage_Core_Model_Session_Abstract $session
     * @return MediaBurst_Sms_Helper_Data
     */
    public function reportResults(Mage_Core_Model_Session_Abstract $session, array $result)
    {
        foreach ($result['sent'] as $message) {
            $session->addSuccess($this->__('Sent message %s to %s', $message->getId(), $message->getTo()));
        }
        foreach ($result['failed'] as $message) {
            $session->addError($this->__('Failed sending message %s to %s (%s: %s)', $message->getId(), $message->getTo(), $message->getErrorNumber(), $message->getErrorDescription()));
        }
        foreach ($result['errors'] as $error) {
            $session->addError(implode(' / ', $error));
        }

        return $this;
    }

    public function getTelephone(Mage_Sales_Model_Order $order)
    {
        $billingAddress = $order->getBillingAddress();

        $number = $billingAddress->getTelephone();
        $number = preg_replace('#[^\+\d]#', '', trim($number));

        if (substr($number, 0, 1) === '+') {
            $number = substr($number, 1);
        } elseif (substr($number, 0, 2) === '00') {
            $number = substr($number, 2);
        } else {
            $expectedPrefix = Zend_Locale_Data::getContent(Mage::app()->getLocale()->getLocale(), 'phonetoterritory', $billingAddress->getCountry());

            if (empty($expectedPrefix)) {
                $expectedPrefix = Mage::getStoreConfig(self::XML_CONFIG_BASE_PATH . 'general/failsafe_prefix', $store);
            }

            if (!empty($expectedPrefix)) {
                $prefix = substr($number, 0, strlen($expectedPrefix));
                if ($prefix !== $expectedPrefix) {
                    $number = $expectedPrefix . $number;
                }
            }
        }

        return preg_replace('#[^\d]#', '', trim($number));
    }

}