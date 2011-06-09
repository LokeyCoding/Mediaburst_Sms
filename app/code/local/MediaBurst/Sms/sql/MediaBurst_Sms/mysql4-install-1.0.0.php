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
    -- DROP TABLE IF EXISTS {$this->getTable('MediaBurst_Sms/Message')};
    CREATE TABLE {$this->getTable('MediaBurst_Sms/Message')} (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `store_id` SMALLINT(5) UNSIGNED NOT NULL,
        `to` VARCHAR(40) NOT NULL,
        `from` VARCHAR(40),
        `content` VARCHAR(180),
        `status` TINYINT(1) NOT NULL DEFAULT 0,
        `message_id` VARCHAR(255),
        `error_number` INT(10) UNSIGNED,
        `error_description` VARCHAR(255),
        PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='MediaBurst SMS Messages';
");

$this->run("
    ALTER TABLE `{$this->getTable('MediaBurst_Sms/Message')}`
      ADD KEY `FK_MESSAGE_STORE` (`store_id`),
      ADD CONSTRAINT `FK_MESSAGE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE;
");

$this->endSetup();