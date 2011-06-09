<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 * Sent Message Grid
 */
class MediaBurst_Sms_Block_SentGrid extends MediaBurst_Sms_Block_AbstractGrid
{

    protected function _filterCollection(Varien_Data_Collection_Db $collection)
    {
        $collection->addFieldToFilter('status', MediaBurst_Sms_Model_Message::STATUS_SENT);
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumnAfter('message_id', array(
            'header' => $this->__('MessageID'),
            'index' => 'message_id',
            'filter' => false,
            ), 'content');

        if (Mage::getSingleton('admin/session')->isAllowed('sales/mediaburst_sms/requeue')) {
            $this->addColumnAfter('action', array(
                'header' => $this->__('Action'),
                'width' => '50px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => $this->__('Requeue'),
                        'url' => array('base' => '*/*/requeue'),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'is_system' => true,
                ), 'message_id');
        }

        return parent::_prepareColumns();
    }

}