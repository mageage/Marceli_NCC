<?php

/**
 * Class Marceli_NCC_Block_Adminhtml_Codes_Edit_Tab_Form
 */
class Marceli_NCC_Block_Adminhtml_Codes_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset("ncc_form", array(
            "legend" => Mage::helper("marceli_ncc")->__("Item information")
        ));

        $fieldset->addField("customer_email", "text", array(
            "label" => Mage::helper("marceli_ncc")->__("Customer Email"),
            "name" => "customer_email",
        ));

        $fieldset->addField("code", "text", array(
            "label" => Mage::helper("marceli_ncc")->__("Coupon Code"),
            "name" => "code",
        ));

        $fieldset->addField("code_id", "text", array(
            "label" => Mage::helper("marceli_ncc")->__("Coupon Code ID"),
            "name" => "code_id",
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );

        $fieldset->addField('created_at', 'date', array(
            'label'        => Mage::helper('marceli_ncc')->__('Created At'),
            'name'         => 'created_at',
            'time' => true,
            'image'        => $this->getSkinUrl('images/grid-cal.gif'),
            'format'       => $dateFormatIso
        ));

        if (Mage::getSingleton("adminhtml/session")->getCodesData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getCodesData());
            Mage::getSingleton("adminhtml/session")->setCodesData(null);
        }  elseif (Mage::registry("codes_data")) {
            $form->setValues(Mage::registry("codes_data")->getData());
        }

        return parent::_prepareForm();
    }
    
}
