<?php
/**
 * Mediaburst SMS Magento Integration
 *
 * Copyright © 2011 by Mediaburst Limited
 *
 * Permission to use, copy, modify, and/or distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND ISC DISCLAIMS ALL WARRANTIES WITH REGARD
 * TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY AND
 * FITNESS. IN NO EVENT SHALL ISC BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT,
 * OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF
 * USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER
 * TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE
 * OF THIS SOFTWARE.
 *
 * @category  Mage
 * @package   Mediaburst_Sms
 * @license   http://opensource.org/licenses/isc-license.txt
 * @copyright Copyright © 2011 by Mediaburst Limited
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 * Failed Message Grid
 */
class Mediaburst_Sms_Block_FailedGrid extends Mediaburst_Sms_Block_AbstractMessageGrid
{

    protected function _filterCollection(Varien_Data_Collection_Db $collection)
    {
        $collection->addFieldToFilter('status', Mediaburst_Sms_Model_Message::STATUS_FAILED);
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumnAfter(
            'error',
            array(
                 'header' => $this->__('Error'),
                 'index'  => 'error_description',
                 'filter' => false,
            ),
            'content'
        );

        if (Mage::getSingleton('admin/session')->isAllowed('sales/mediaburst_sms/retry')) {
            $this->addColumnAfter(
                'action',
                array(
                     'header'    => $this->__('Action'),
                     'width'     => '50px',
                     'type'      => 'action',
                     'getter'    => 'getId',
                     'filter'    => false,
                     'sortable'  => false,
                     'is_system' => true,
                     'actions'   => array(
                         array(
                             'caption' => $this->__('Retry'),
                             'url'     => array('base' => '*/*/retry'),
                             'field'   => 'id'
                         )
                     )
                ),
                'error'
            );
        }

        return parent::_prepareColumns();
    }
}