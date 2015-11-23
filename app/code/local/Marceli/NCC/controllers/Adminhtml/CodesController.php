<?php

/**
 * Class Marceli_NCC_Adminhtml_CodesController
 */
class Marceli_NCC_Adminhtml_CodesController
    extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu("newsletter/subscriber")
            ->_addBreadcrumb(Mage::helper("adminhtml")->__("Newsletter Coupon Codes"), Mage::helper("adminhtml")->__("Manager"));

        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__("NCC"));
        $this->_title($this->__("Manager Codes"));

        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__("NCC"));
        $this->_title($this->__("Codes"));
        $this->_title($this->__("Edit Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("marceli_ncc/codes")->load($id);

        if ($model->getId()) {
            Mage::register("codes_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("marceli_ncc/codes");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Codes Manager"), Mage::helper("adminhtml")->__("Codes Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Codes Description"), Mage::helper("adminhtml")->__("Codes Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("marceli_ncc/adminhtml_codes_edit"))->_addLeft($this->getLayout()->createBlock("marceli_ncc/adminhtml_codes_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("marceli_ncc")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function newAction()
    {
        $this->_title($this->__("NCC"));
        $this->_title($this->__("Codes"));
        $this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
        $model  = Mage::getModel("marceli_ncc/codes")->load($id);

        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("codes_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("marceli_ncc/codes");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Codes Manager"), Mage::helper("adminhtml")->__("Codes Manager"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Codes Description"), Mage::helper("adminhtml")->__("Codes Description"));

        $this->_addContent($this->getLayout()->createBlock("marceli_ncc/adminhtml_codes_edit"))->_addLeft($this->getLayout()->createBlock("marceli_ncc/adminhtml_codes_edit_tabs"));

        $this->renderLayout();

    }

    public function saveAction()
    {
        $post_data = $this->getRequest()->getPost();
        if ($post_data) {

            try {
                $model = Mage::getModel("marceli_ncc/codes")
                    ->addData($post_data)
                    ->setId($this->getRequest()->getParam("id"))
                    ->save();

                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Codes was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setCodesData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));

                    return;
                }
                $this->_redirect("*/*/");

                return;
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setCodesData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));

                return;
            }

        }
        $this->_redirect("*/*/");
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam("id") > 0 ) {
            try {
                $model = Mage::getModel("marceli_ncc/codes");
                $model->setId($this->getRequest()->getParam("id"))->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
                $this->_redirect("*/*/");
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            }
        }
        $this->_redirect("*/*/");
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'ncc_codes.csv';
        $grid     = $this->getLayout()->createBlock('marceli_ncc/adminhtml_codes_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName = 'ncc_codes.xml';
        $grid     = $this->getLayout()->createBlock('marceli_ncc/adminhtml_codes_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

}
