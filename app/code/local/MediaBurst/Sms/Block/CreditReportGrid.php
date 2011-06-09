<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 * Credit Report Grid
 */
class MediaBurst_Sms_Block_CreditReportGrid extends Mage_Adminhtml_Block_Widget_Grid
{

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->unsetChild('reset_filter_button');
        $this->unsetChild('search_button');

        return $this;
    }

    protected function _prepareCollection()
    {
        $collection = new Varien_Data_Collection();

        $helper = Mage::helper('MediaBurst_Sms/Data');

        $runs = array();

        $stores = Mage::app()->getStores();
        foreach ($stores as $store) {
            if ($helper->isActive($store)) {
                $username = $helper->getUsername($store);
                $password = $helper->getPassword($store);
                $url = $helper->getCheckUrl($store);
                $hash = md5($username . ':' . $password . ':' . $url);

                if (!isset($runs[$hash])) {
                    $runs[$hash] = array(
                        'username' => $username,
                        'url' => $url,
                        'stores' => array()
                    );
                }

                $runs[$hash]['stores'][] = $store->getId();
            }
        }

        $api = Mage::getModel('MediaBurst_Sms/Api', $helper);
        /* @var $api MediaBurst_Sms_Model_Api */

        $results = array();

        foreach ($runs as $hash => $run) {
            $helper->setDefaultStore(reset($run['stores']));
            $credits = $api->checkCredits();

            $item = new Varien_Object();
            $item->setUsername($run['username']);
            $item->setUrl($run['url']);
            $item->setCredits($credits);

            $collection->addItem($item);
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('username', array(
            'header' => $this->__('Username'),
            'index' => 'username',
            'filter' => false,
        ));

        $this->addColumn('url', array(
            'header' => $this->__('Service URL'),
            'index' => 'url',
            'filter' => false,
        ));

        $this->addColumn('credits', array(
            'header' => $this->__('Credits'),
            'index' => 'credits',
            'filter' => false,
        ));

        return parent::_prepareColumns();
    }

    public function registerBuyButton()
    {
        $container = $this->getParentBlock();
        if ($container instanceof Mage_Adminhtml_Block_Widget_Grid_Container) {
            $helper = Mage::helper('MediaBurst_Sms/Data');
            $container->addButton('buy', array(
                'label' => $this->__('Buy'),
                'onclick' => 'setLocation(\'' . $helper->getBuyUrl(0) . '\')',
                'class' => 'add',
            ));
        }
    }

}