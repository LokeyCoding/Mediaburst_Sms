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
 * Config Interface
 */
interface Mediaburst_Sms_Model_ApiConfig
{

    /**
     * Return the URL to the send message service√è
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
     * @param int   $level
     * @param mixed $store
     */
    public function log($message, $level = Zend_Log::DEBUG, $store = null);
}