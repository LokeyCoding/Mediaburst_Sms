<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 * Failed Message Grid
 */
class MediaBurst_Sms_Block_FailedGrid extends MediaBurst_Sms_Block_AbstractMessageGrid
{

    protected function _filterCollection(Varien_Data_Collection_Db $collection)
    {
        $collection->addFieldToFilter('status', MediaBurst_Sms_Model_Message::STATUS_FAILED);
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumnAfter('error', array(
            'header' => $this->__('Error'),
            'index' => 'error_description',
            'filter' => false,
            ), 'content');

        if (Mage::getSingleton('admin/session')->isAllowed('sales/mediaburst_sms/retry')) {
            $this->addColumnAfter('action', array(
                'header' => $this->__('Action'),
                'width' => '50px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => $this->__('Retry'),
                        'url' => array('base' => '*/*/retry'),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'is_system' => true,
                ), 'error');
        }

        return parent::_prepareColumns();
    }

}