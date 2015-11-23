<?php
$installer = $this;
$installer->startSetup();

$table = <<<SQLTEXT
    DROP TABLE IF EXISTS `marceli_newsletter_counpon_code`;
    CREATE TABLE `marceli_newsletter_counpon_code` (
        `code_id` int(11) NOT NULL AUTO_INCREMENT,
        `rule_id` int(11) NOT NULL,
        `coupon_id` int(11) NOT NULL,
        `customer_email` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`code_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8
SQLTEXT;

$installer->run($table);

$emailTemplate = <<<EOD
<!--@subject Kod rabatowy @-->
<!--@vars
{"var data.coupon_code":"Kod rabatowy"}
@-->
<!--@styles @-->
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>Witamy</h1>
            <p>Kod rabatowy: {{var data.coupon_code}}</p>
        </td>
    </tr>
</table>
{{template config_path="design/email/footer"}}
EOD;

$installer->run("
DELETE FROM `{$this->getTable('core/email_template')}` WHERE `template_code` = 'kody_rabatowe_newsletter';
INSERT INTO {$this->getTable('core_email_template')} (`template_code`, `template_text`, `template_type`, `template_subject`, `template_sender_name`, `template_sender_email`, `added_at`, `modified_at`, `orig_template_code`) VALUES
  ('kody_rabatowe_newsletter', '" . $emailTemplate . "', 2, 'Kod rabatowy', NULL, NULL, NOW(), NOW(), 'marceli_ncc_email_template');
");

$installer->endSetup();
