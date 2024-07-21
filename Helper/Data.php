<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_OrderInformation
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */
namespace Mavenbird\OrderInformation\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Get Enable|Disable
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->scopeConfig->isSetFlag(
            'order_details/general/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Enable|Disable Product Details
     *
     * @return bool
     */
    public function isEnableOrderProduct()
    {
        return $this->scopeConfig->isSetFlag(
            'order_details/general/show_order_product',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Enable|Disable Shipping Address
     *
     * @return bool
     */
    public function isEnableShippingAddress()
    {
        return $this->scopeConfig->isSetFlag(
            'order_details/general/show_shipping_address',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Enable|Disable Shipping Method
     *
     * @return bool
     */
    public function isEnableShippingMethod()
    {
        return $this->scopeConfig->isSetFlag(
            'order_details/general/show_shipping_method',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Enable|Disable BiLLing Address
     *
     * @return bool
     */
    public function isEnableBillingAddress()
    {
        return $this->scopeConfig->isSetFlag(
            'order_details/general/show_billing_address',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Enable|Disable Payment Method
     *
     * @return bool
     */
    public function isEnablePaymentMethod()
    {
        return $this->scopeConfig->isSetFlag(
            'order_details/general/show_payment_method',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Can View Print Order
     *
     * @return bool
     */
    public function isEnablePrintOrderLink()
    {
        return $this->scopeConfig->isSetFlag(
            'order_details/general/show_print_order_link',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Can View Re-Order
     *
     * @return bool
     */
    public function isEnableReOrderLink()
    {
        return $this->scopeConfig->isSetFlag(
            'order_details/general/show_reorder_link',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Enable|Disable Order Status
     *
     * @return bool
     */
    public function isEnableOrderStatus()
    {
        return $this->scopeConfig->isSetFlag(
            'order_details/general/show_order_status',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Enable|Disable Re-Order
     *
     * @return bool
     */
    public function isEnableReOrder()
    {
        return $this->scopeConfig->isSetFlag(
            'sales/reorder/allow',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Thanks Messeger
     *
     * @return string
     */
    public function getThankMesseger()
    {
        $thankMss= $this->scopeConfig->getValue(
            'order_details/thank_cofig/thank_messager',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $thankMss;
    }

    /**
     * Get Text Before Order
     *
     * @return string
     */
    public function getBeforeText()
    {
        $textbefore= $this->scopeConfig->getValue(
            'order_details/before_config/text_before',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $textbefore;
    }

    /**
     * Get Text After
     *
     * @return string
     */
    public function getAfterText()
    {
        $textafter= $this->scopeConfig->getValue(
            'order_details/after_config/text_after',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $textafter;
    }

    /**
     * Get Thank Messeger Size
     *
     * @return string
     */
    public function getThankMessegerSize()
    {
        $thanksz= $this->scopeConfig->getValue(
            'order_details/thank_cofig/size_thanks',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $thanksz;
    }

    /**
     * Get Text Before Size
     *
     * @return string
     */
    public function getBeforeTextSize()
    {
        $textbeforesz= $this->scopeConfig->getValue(
            'order_details/before_config/size_before_text',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $textbeforesz;
    }

    /**
     * Get Text After Size
     *
     * @return string
     */
    public function getAfterTextSize()
    {
        $textaftersz= $this->scopeConfig->getValue(
            'order_details/after_config/size_after_text',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $textaftersz;
    }

    /**
     * Get Thank Messeger Color
     *
     * @return string
     */
    public function getThankMessegerColor()
    {
        $thankcl= $this->scopeConfig->getValue(
            'order_details/thank_cofig/color_thanks',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $thankcl;
    }

    /**
     * Get Text Before Color
     *
     * @return string
     */
    public function getBeforeTextColor()
    {
        $textbeforecl= $this->scopeConfig->getValue(
            'order_details/before_config/color_text_before',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $textbeforecl;
    }

    /**
     * Get Text After Color
     *
     * @return string
     */
    public function getAfterTextColor()
    {
        $textaftercl= $this->scopeConfig->getValue(
            'order_details/after_config/color_text_after',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $textaftercl;
    }
}
