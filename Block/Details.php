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
namespace Mavenbird\OrderInformation\Block;

class Details extends \Magento\Sales\Block\Order\Totals
{
    /**
     * Get Checkout Session
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * Get Customer Session
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Sales Factory
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * Order Address
     * @var \Magento\Sales\Model\Order\Address\Renderer
     */
    protected $render;

    /**
     * Mavenbird Helper Data
     * @var \Mavenbird\OrderInformation\Helper\Data
     */
    protected $helper;

    /**
     * Pricing Helper Data
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $formatPrice;

    /**
     * Undocumented variable
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Order Details Constructor
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Mavenbird\OrderInformation\Helper\Data $helper
     * @param \Magento\Sales\Model\Order\Address\Renderer $render
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Pricing\Helper\Data $formatPrice
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        \Mavenbird\OrderInformation\Helper\Data $helper,
        \Magento\Sales\Model\Order\Address\Renderer $render,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Pricing\Helper\Data $formatPrice,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Psr\Log\LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($context, $registry, $data);
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->_orderFactory = $orderFactory;
        $this->render = $render;
        $this->helper = $helper;
        $this->formatPrice = $formatPrice;
        $this->_scopeConfig = $scopeConfig;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $product
     * @param [type] $imageType
     * @return void
     */
    public function getImageUrl($product, $imageType)
    {
        $imageUrl = false;
        try {
            $product = $this->productRepository->getById($product->getId());
            $image = $product->getMediaGalleryImages()->getItemByColumnValue('media_type', 'image');
            if ($image) {
                $imageUrl = $this->getUrl('pub/media/catalog') . 'product' . $image->getFile();
            }
        } catch (\Exception $e) {
            $this->logger->error('Error getting image URL: ' . $e->getMessage());
        }
        return $imageUrl;
    }
    /**
     * Is Cms Block Enable
     *
     * @return boolean
     */
    public function isCmsBlockEnabled()
    {
        return $this->_scopeConfig->getValue(
            'order_details/custom_block_config/enable_cms_block_1',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Is second Cms Block Enable
     *
     * @return boolean
     */
    public function isSecondCmsBlockEnabled()
    {
        return $this->_scopeConfig->getValue(
            'order_details/custom_block_config/enable_cms_block_2',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Cms Block Details
     *
     * @return void
     */
    public function getCmsBlockDetails()
    {
        return $this->_scopeConfig->getValue('order_details/custom_block_config/cms_block', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get Second Cms Block Details
     *
     * @return void
     */
    public function getSecondCmsBlockDetails()
    {
        return $this->_scopeConfig->getValue('order_details/custom_block_config/second_cms_block', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get last order id
     *
     * @return string
     */
    public function getOrder()
    {
        return  $this->_order = $this->_orderFactory->create()->loadByIncrementId(
            $this->checkoutSession->getLastRealOrderId()
        );
    }

    /**
     * Get Enable|Disable
     *
     * @return bool
     */
    public function isEnableDetails()
    {
        return $this->helper->isEnable();
    }

    /**
     * Get Thanks Messeger
     *
     * @return string
     */
    public function getThankMessegerDetails()
    {
        return $this->helper->getThankMesseger();
    }

    /**
     * Get Enable|Disable Order Status
     *
     * @return bool
     */
    public function isEnableOrderStatusDetails()
    {
        return $this->helper->isEnableOrderStatus();
    }

    /**
     * Get Text Before Order
     *
     * @return string
     */
    public function getBeforeTextDetails()
    {
        return $this->helper->getBeforeText();
    }

    /**
     * Get Text After Order
     *
     * @return string
     */
    public function getAfterTextDetails()
    {
        return $this->helper->getAfterText();
    }

    /**
     * Get Enable|Disable Shipping Address
     *
     * @return bool
     */
    public function isEnableShippingAddressDetails()
    {
        return $this->helper->isEnableShippingAddress();
    }

    /**
     * Get Enable|Disable Shipping Method
     *
     * @return bool
     */
    public function isEnableShippingMethodDetails()
    {
        return $this->helper->isEnableShippingMethod();
    }

    /**
     * Get Enable|Disable BiLLing Address
     *
     * @return bool
     */
    public function isEnableBillingAddressDetails()
    {
        return $this->helper->isEnableBillingAddress();
    }

    /**
     * Get Enable|Disable Payment Method
     *
     * @return bool
     */
    public function isEnablePaymentMethodDetails()
    {
        return $this->helper->isEnablePaymentMethod();
    }

    /**
     * Get Enable|Disable Product Details
     *
     * @return bool
     */
    public function isEnableOrderProductDetails()
    {
        return $this->helper->isEnableOrderProduct();
    }

    /**
     * Get Thank Messeger Size
     *
     * @return string
     */
    public function getThankMessegerSizeDetails()
    {
        return $this->helper->getThankMessegerSize();
    }

    /**
     * Get Text Before Size
     *
     * @return string
     */
    public function getBeforeTextSizeDetails()
    {
        return $this->helper->getBeforeTextSize();
    }

    /**
     * Get Text After Size
     *
     * @return string
     */
    public function getAfterTextSizeDetails()
    {
        return $this->helper->getAfterTextSize();
    }

    /**
     * Get Thank Messeger Color
     *
     * @return string
     */
    public function getThankMessegerColorDetails()
    {
        return $this->helper->getThankMessegerColor();
    }

    /**
     * Get Text Before Color
     *
     * @return string
     */
    public function getBeforeTextColorDetails()
    {
        return $this->helper->getBeforeTextColor();
    }

    /**
     * Get Text After Color
     *
     * @return string
     */
    public function getAfterTextColorDetails()
    {
        return $this->helper->getAfterTextColor();
    }

    /**
     * Get Customer Id
     *
     * @return string
     */
    public function getCustomerId()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomer()->getId();
        }
        return null;
    }

    /**
     * Render Block
     *
     * @return string
     */
    public function getAdditionalInfoHtml()
    {
        return $this->_layout->renderElement('order.success.additional.info');
    }

    /**
     * Format Price
     *
     * @param float $value
     * @return float
     */
    public function formatPrice($value)
    {
        return $this->formatPrice->currency($value, true, false);
    }

    /**
     * Get Re-Order
     *
     * @return string
     */
    public function getReorder()
    {
        $order = $this->getOrder();
        $orderID = $order -> getId();
        $reorder = $this->getBaseUrl().'sales/order/view/order_id/'.$orderID;
        return $reorder;
    }

    /**
     * Get Print Order
     *
     * @return string
     */
    public function getPrint()
    {
        $order = $this->getOrder();
        $orderID = $order -> getId();
        $print = $this->getBaseUrl().'sales/order/print/order_id/'.$orderID;
        return $print;
    }

    /**
     * Can View Re-Order
     *
     * @return bool
     */
    public function canViewReorder()
    {
        if ($this->helper->isEnableReOrderLink() && $this->helper->isEnableReOrder() && $this->getCustomerId()) {
            return true;
        }
            return false;
    }

    /**
     * Can View Print Order
     *
     * @return bool
     */
    public function canViewPrint()
    {
        if ($this->helper->isEnablePrintOrderLink() && $this->getCustomerId()) {
            return true;
        }
        return false;
    }

    /**
     * Format Shipping Address
     *
     * @return string
     */
    public function formatShipping()
    {
        $order = $this->getOrder();
        if ($order->getShippingAddress()) {
            return $this->render->format($order->getShippingAddress(), 'html');
        }
            return false;
    }

    /**
     * Format Billing Address
     *
     * @return string
     */
    public function formatBilling()
    {
            $order = $this->getOrder();
            return $this->render->format($order->getBillingAddress(), 'html');
    }

    /**
     * Format date
     *
     * @param string $date
     * @param string $format
     * @param bool $showTime
     * @param string $timezone
     * @param string $pattern
     * @return string
     */
    public function formatDate(
        $date = null,
        $format = \IntlDateFormatter::SHORT,
        $showTime = false,
        $timezone = null,
        $pattern = 'd MMM Y'
    ) {
        
            $date = $date instanceof \DateTimeInterface;
            return $this->_localeDate->formatDateTime(
                $date,
                $format,
                $showTime ? $format : \IntlDateFormatter::NONE,
                null,
                $timezone,
                $pattern
            );
    }

    /**
     * Return Opptions Configurable Product
     *
     * @param object $item
     * @return array
     */
    public function getItemOptions($item)
    {
        $result = [];
        $option = $item->getProductOptions();
        if ($option) {
            if (isset($option['options'])) {
                    $result = array_merge($result, $option['options']);
            }
            if (isset($option['additional_options'])) {
                    $result = array_merge($result, $option['additional_options']);
            }
            if (isset($option['attributes_info'])) {
                    $result = array_merge($result, $option['attributes_info']);
            }
        }
        return $result;
    }

    /**
     * Return Opptions Bundle Product
     *
     * @param object $item
     * @return array
     */
    public function getBundleItemOptions($item)
    {
        $result = [];
        $option = $item->getProductOptions();
        if ($option) {
            if (isset($option['bundle_options'])) {
                $result = array_merge($result, $option['bundle_options']);
            }
        }
        return $result;
    }
}
