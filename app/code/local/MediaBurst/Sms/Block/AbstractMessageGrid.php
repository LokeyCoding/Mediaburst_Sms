<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 * Abstract Message Grid
 */
abstract class MediaBurst_Sms_Block_AbstractMessageGrid extends Mage_Adminhtml_Block_Widget_Grid
{

    protected $_resourceClass = null;

    public function setCollectionResourceModel($model)
    {
        $this->_collectionResourceModel = $model;
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function getCollectionResourceModel()
    {
        return $this->_collectionResourceModel;
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->getCollectionResourceModel());
        $this->_filterCollection($collection);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _filterCollection(Varien_Data_Collection_Db $collection)
    {
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => $this->__('Message #'),
            'width' => '80px',
            'type' => 'number',
            'index' => 'id',
        ));

        $this->addColumn('store_id', array(
            'header' => $this->__('Store'),
            'index' => 'store_id',
            'type' => 'store',
            'store_view' => true,
            'display_deleted' => true,
        ));

        $this->addColumn('to', array(
            'header' => $this->__('To'),
            'index' => 'to',
        ));

        $this->addColumn('from', array(
            'header' => $this->__('From'),
            'index' => 'from',
        ));

        $this->addColumn('content', array(
            'header' => $this->__('Content'),
            'index' => 'content',
            'filter' => false,
        ));

        return parent::_prepareColumns();
    }

}