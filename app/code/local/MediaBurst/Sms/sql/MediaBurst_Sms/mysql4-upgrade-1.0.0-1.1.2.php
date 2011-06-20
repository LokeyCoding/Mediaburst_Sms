<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */
/* @var $this Mage_Core_Model_Resource_Setup */

$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('MediaBurst_Sms/Message')}`
        ADD COLUMN `created_at` DATETIME NULL,
        ADD COLUMN `updated_at` DATETIME NULL;
");

$this->endSetup();