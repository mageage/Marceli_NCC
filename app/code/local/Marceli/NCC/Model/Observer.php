<?php

/**
 * Class Marceli_NCC_Model_Observer
 */
class Marceli_NCC_Model_Observer
{

    /**
     * Log file name
     */
    const LOG_FILE = 'marceli_ncc.log';

    /**
     * @var string
     */
    private $_email = '';

    /**
     * @param Varien_Event_Observer $observer
     */
    public function subscribeToNewsletter(Varien_Event_Observer $observer)
    {
        if (!$this->_canSendNccEmails()) {
            return;
        }

        $event      = $observer->getEvent();
        $subscriber = $event->getDataObject();
        $data       = $subscriber->getData();
        $canProcess = true;

	
    	if ($this->_getCustomerOrdersByEmail($data['subscriber_email'])) {
	        $orderErrMsg = Mage::helper("marceli_ncc")->__('Customer already exists in orders!');
    	    Mage::log($orderErrMsg, Zend_Log::INFO, self::LOG_FILE);
    	    $canProcess = false;
            Mage::getSingleton('core/session')->addError($orderErrMsg);
	        throw new Exception($orderErrMsg);
    	}

    	if ($this->_isCustomerSubscribed($data['subscriber_email'])) {
	        $subErrMsg = Mage::helper("marceli_ncc")->__('Customer already exists in newsletter!');
    	    Mage::log($subErrMsg, Zend_Log::INFO, self::LOG_FILE);
    	    $canProcess = false;
            Mage::getSingleton('core/session')->addError($subErrMsg);
	        throw new Exception($subErrMsg);
    	}

        Mage::register('can_process_ncc', $canProcess);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function subscribedToNewsletter(Varien_Event_Observer $observer)
    {
        if (!$this->_canSendNccEmails() || !Mage::registry('can_process_ncc')) {
            return;
        }

        $event        = $observer->getEvent();
        $subscriber   = $event->getDataObject();
        $data         = $subscriber->getData();
        $statusChange = $subscriber->getIsStatusChanged();
        $customerData = array();

        $this->setCustomerEmail($data['subscriber_email']);

        /** Set some customer data */
        $customerData['coupon_code'] = $this->_getCouponCode();
        $customerData['email']       = $data['subscriber_email'];
        $customerData['name']        = '';

        if ($data['subscriber_status'] == "1" && $statusChange == true) {
            $this->_sendNccMail($customerData);
        }
    }

    /**
     * @param string $email
     */
    public function setCustomerEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->_email;
    }

    /**
     * @param string $email
     * @return bool
     */
    protected function _isCustomerSubscribed($email)
    {
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
        if ($subscriber->getId()) {
            return true;
        }

        return false;
    }

    /**
     * @param string $mail
     * @return bool
     */
    protected function _getCustomerOrdersByEmail($email)
    {
        $_collection = Mage::getModel('sales/order')->getCollection();
        $_collection->addFieldToFilter('customer_email', $email);

        if ($_collection->getSize()) {
            return true;
        }

        return false;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    protected function _sendNccMail($data)
    {
        /** @var $postObject Varien_Object */
        $postObject = new Varien_Object();
        $postObject->setData($data);

        /* @var $translate Mage_Core_Model_Translate */
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);

        /** @var $emailTemplate Mage_Core_Model_Email_Template */
        $emailTemplate = Mage::getModel('core/email_template');
        $emailTemplate->loadByCode('kody_rabatowe_newsletter');

        if(!$emailTemplate->getTemplateId()) {
            throw new Exception();
        }

        $processedTemplate = $emailTemplate->getProcessedTemplate(array(
            'data' => $postObject
        ));

        $mail = Mage::getModel('core/email')
            ->setToName($data['name'])
            ->setToEmail($data['email'])
            ->setFromEmail(Mage::getStoreConfig('trans_email/ident_general/email'))
            ->setFromName(Mage::getStoreConfig('trans_email/ident_general/name'))
            ->setBody($processedTemplate)
            ->setSubject($emailTemplate->getTemplateSubject())
            ->setType('html');

        try {
	    $sendMail = new Zend_Mail('utf-8');
	    $sendMail->setBodyHtml($mail->getBody());
	    $sendMail->setFrom($mail->getFromEmail(), $mail->getFromName())
        	     ->addTo($mail->getToEmail(), $mail->getToName())
        	     ->setSubject($mail->getSubject());
    	    $sendMail->send();
        } catch (Exception $error) {
            Mage::log($error->getMessage(), null, self::LOG_FILE);
        }

        Mage::helper('marceli_ncc/cookie')->setCookie();
        $translate->setTranslateInline(true);
    }

    /**
     * @return bool
     */
    protected function _canSendNccEmails()
    {
        $_helper = Mage::helper('marceli_ncc/config');

        if ($_helper->getIsEnabled()) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function _getCouponCode()
    {
        $_helper     = Mage::helper('marceli_ncc/config');
        $_salesRule  = Mage::getModel('salesrule/rule')->load($_helper->getPromotion());
        $_collection = Mage::getResourceModel('salesrule/coupon_collection')
            ->addFieldToSelect(array('coupon_id', 'code'))
            ->addRuleToFilter($_salesRule)
            ->addFieldToFilter('times_used', array(
                'eq' => 0
            ))
            ->addGeneratedCouponsFilter();

        if (count($this->_getAlreadySentCouponCodeIds($_salesRule->getId()))) {
            $_collection->addFieldToFilter('coupon_id', array(
                'nin' => $this->_getAlreadySentCouponCodeIds($_salesRule->getId())
            ));
        }

        try {
            if ($_collection->getSize() == 0) {
                $errMsg = 'No more coupon codes to send';
                throw new Exception($errMsg);
            }
            $this->_saveNcc(
                $_salesRule->getId(),
                $_collection->getFirstItem()->getCouponId()
            );
        } catch (Exception $e) {
            Mage::log($e->getMessage(), Zend_Log::ERR, self::LOG_FILE);
        }

        return $_collection->getFirstItem()->getCode();
    }

    /**
     * @param int $ruleId
     * @param int $couponId
     */
    protected function _saveNcc($ruleId, $couponId)
    {
        $_model = Mage::getModel('marceli_ncc/codes');
        $_model->setRuleId($ruleId)
            ->setCouponId($couponId)
            ->setCustomerEmail($this->getCustomerEmail())
            ->save();
    }

    /**
     * @param int $ruleId
     * @return array
     */
    protected function _getAlreadySentCouponCodeIds($ruleId)
    {
        $_collection = Mage::getModel('marceli_ncc/codes')->getCollection();
        $_collection->addFieldToSelect('coupon_id');
        $_collection->addFieldToFilter('rule_id', array(
            'eq' => $ruleId
        ));

        return $_collection->getData();
    }

}