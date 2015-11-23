<?php

/**
 * Class Marceli_NCC_Block_Adminhtml_Codes_Grid
 */
class Marceli_NCC_Block_Adminhtml_Codes_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();

        $this->setId("ncc_codesGrid");
        $this->setDefaultSort("code_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("marceli_ncc/codes")->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn("code_id", array(
            "header" => Mage::helper("marceli_ncc")->__("ID"),
            "align" =>"right",
            "width" => "50px",
            "type" => "number",
            "index" => "code_id",
        ));

        $this->addColumn("rule_id", array(
            "header" => Mage::helper("marceli_ncc")->__("Rule ID"),
            "index" => "rule_id",
        ));

        $this->addColumn("coupon_id", array(
            "header" => Mage::helper("marceli_ncc")->__("Coupon ID"),
            "index" => "coupon_id",
        ));

        $this->addColumn("customer_email", array(
            "header" => Mage::helper("marceli_ncc")->__("Customer Email"),
            "index" => "customer_email",
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('marceli_ncc')->__('Created At'),
            'index'     => 'created_at',
            'type'      => 'datetime',
        ));
        //$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        //$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl("*/*/edit", array(
            "id" => $row->getId()
        ));
    }

}