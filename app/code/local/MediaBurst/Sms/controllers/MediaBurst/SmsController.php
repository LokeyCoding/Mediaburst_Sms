<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 *
 */
class MediaBurst_Sms_MediaBurst_SmsController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->_redirect('*/*/pending');
    }

    public function checkAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('sales/mediaburst_sms/check');
        $this->_addBreadcrumb($this->__('Sales'), $this->__('Sales'));
        $this->renderLayout();
    }

    public function pendingAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('sales/mediaburst_sms/pending');
        $this->_addBreadcrumb($this->__('Sales'), $this->__('Sales'));
        $this->renderLayout();
    }

    public function sentAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('sales/mediaburst_sms/sent');
        $this->_addBreadcrumb($this->__('Sales'), $this->__('Sales'));
        $this->renderLayout();
    }

    public function failedAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('sales/mediaburst_sms/failed');
        $this->_addBreadcrumb($this->__('Sales'), $this->__('Sales'));
        $this->renderLayout();
    }

    public function sendAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        $message = Mage::getModel('MediaBurst_Sms/Message')->load($id);
        if ($message->getId() > 0 && $message->getId() == $id) {
            if ($message->getStatus() == MediaBurst_Sms_Model_Message::STATUS_PENDING) {
                $helper = Mage::helper('MediaBurst_Sms/Data');
                $helper->setDefaultStore($message->getStoreId());
                $api = Mage::getModel('MediaBurst_Sms/Api', $helper);
                $result = $api->sendMessage($message);
                $helper->setDefaultStore(null);
                $helper->reportResults($this->_getSession(), $result);
            } else {
                $this->_getSession()->addError($this->__('Invalid Message Status'));
            }
        } else {
            $this->_getSession()->addError($this->__('Invalid Message ID'));
        }

        $this->_redirect('*/*/pending');
    }

    public function requeueAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        $message = Mage::getModel('MediaBurst_Sms/Message')->load($id);
        if ($message->getId() > 0 && $message->getId() == $id) {
            if ($message->getStatus() == MediaBurst_Sms_Model_Message::STATUS_SENT) {
                try {
                    $message->setStatus(MediaBurst_Sms_Model_Message::STATUS_PENDING);
                    $message->getMessageId(null);
                    $message->setErrorNumber(null);
                    $message->setErrorDescription(null);
                    $message->save();
                    $this->_getSession()->addSuccess($this->__('Requeued message %s to %s', $message->getId(), $message->getTo()));
                } catch (Exception $e) {
                    $this->_getSession()->addException($e);
                }
            } else {
                $this->_getSession()->addError($this->__('Invalid Message Status'));
            }
        } else {
            $this->_getSession()->addError($this->__('Invalid Message ID'));
        }

        $this->_redirect('*/*/sent');
    }

    public function retryAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        $message = Mage::getModel('MediaBurst_Sms/Message')->load($id);
        if ($message->getId() > 0 && $message->getId() == $id) {
            if ($message->getStatus() == MediaBurst_Sms_Model_Message::STATUS_FAILED) {
                try {
                    $message->setStatus(MediaBurst_Sms_Model_Message::STATUS_PENDING);
                    $message->getMessageId(null);
                    $message->setErrorNumber(null);
                    $message->setErrorDescription(null);
                    $message->save();
                    $this->_getSession()->addSuccess($this->__('Retrying message %s to %s', $message->getId(), $message->getTo()));
                } catch (Exception $e) {
                    $this->_getSession()->addException($e);
                }
            } else {
                $this->_getSession()->addError($this->__('Invalid Message Status'));
            }
        } else {
            $this->_getSession()->addError($this->__('Invalid Message ID'));
        }

        $this->_redirect('*/*/failed');
    }

    public function forceCronAction()
    {
        Mage::getSingleton('MediaBurst_Sms/Observer')->sendPendingMessages($this->_getSession());
        $this->_redirect('*/*');
    }

    protected function _isAllowed()
    {
        $allowed = false;

        $action = $this->getRequest()->getActionName();
        switch ($action) {
            case 'pending':
            case 'send':
            case 'sent':
            case 'requeue':
            case 'failed':
            case 'retry':
            case 'check':
                $allowed = $this->_permissionCheck($action);
                break;
            case 'index':
            case 'forceCron':
                $allowed = true;
                break;
        }

        return $allowed;
    }

    protected function _permissionCheck($permission)
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/mediaburst_sms/' . $permission);
    }

}