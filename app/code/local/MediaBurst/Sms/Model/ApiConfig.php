<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 * Config Interface
 */
interface MediaBurst_Sms_Model_ApiConfig
{

    /**
     * Return the URL to the send message serviceÏ
     */
    public function getSendUrl();

    /**
     * Return the URL to the credit check service
     *
     * @return string
     */
    public function getCheckUrl();

    /**
     * Return the API username
     *
     * @return string
     */
    public function getUsername();

    /**
     * Return the API password
     *
     * @return string
     */
    public function getPassword();

    /**
     * Check is debugging is enabled
     *
     * @return bool
     */
    public function isDebug();

    /**
     * Log a message
     *
     * @param mixed $message
     * @param int $level
     * @param mixed $store
     */
    public function log($message, $level, $store);
}