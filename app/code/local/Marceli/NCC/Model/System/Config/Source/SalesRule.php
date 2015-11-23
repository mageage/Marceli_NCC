<?php

/**
 * Class Marceli_NCC_Model_System_Config_Source_SalesRule
 */
class Marceli_NCC_Model_System_Config_Source_SalesRule
{

    protected $_options = null;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result      = array();
        $_collection = Mage::getModel('salesrule/rule')->getCollection();

        foreach ($_collection as $item) {
            $result[] = array(
                'value' => $item->getId(),
                'label' => $item->getName()
            );
        }

        return $result;
    }

}