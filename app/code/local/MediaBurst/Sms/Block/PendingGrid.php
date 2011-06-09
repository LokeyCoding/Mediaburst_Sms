<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 * Pending Message Grid
 */
class MediaBurst_Sms_Block_PendingGrid extends MediaBurst_Sms_Block_AbstractGrid
{

    protected function _filterCollection(Varien_Data_Collection_Db $collection)
    {
        $collection->addFieldToFilter('status', MediaBurst_Sms_Model_Message::STATUS_PENDING);
        return $this;
    }

    protected function _prepareColumns()
    {
        if (Mage::getSingleton('admin/session')->isAllowed('sales/mediaburst_sms/send')) {
            $this->addColumnAfter('action', array(
                'header' => $this->__('Action'),
                'width' => '50px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => $this->__('Send'),
                        'url' => array('base' => '*/*/send'),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'is_system' => true,
                ), 'content');
        }

        return parent::_prepareColumns();
    }

}