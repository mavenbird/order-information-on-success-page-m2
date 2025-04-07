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

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;
use Magento\Framework\Registry;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Sales\Model\Order\Config;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Sales\Model\OrderFactory;


class Details extends Template
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var AddressRenderer
     */
    protected $addressRenderer;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var Image
     */
    protected $imageHelper;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var PriceHelper
     */
    protected $priceHelper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Config
     */
    protected $orderConfig;

    /**
     * @var CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var TimezoneInterface
     */
    protected $localeDate;

    /**
     * Factory for orders
     *
     * @var [type]
     */
    protected $orderFactory;

    /**
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     * @param AddressRenderer $addressRenderer
     * @param Registry $registry
     * @param CustomerSession $customerSession
     * @param Image $imageHelper
     * @param ProductRepository $productRepository
     * @param PriceHelper $priceHelper
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $orderConfig
     * @param CollectionFactory $orderCollectionFactory
     * @param TimezoneInterface $localeDate
     * @param array $data
     */
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        AddressRenderer $addressRenderer,
        Registry $registry,
        CustomerSession $customerSession,
        Image $imageHelper,
        ProductRepository $productRepository,
        PriceHelper $priceHelper,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        Config $orderConfig,
        CollectionFactory $orderCollectionFactory,
        TimezoneInterface $localeDate,
        OrderFactory $orderFactory,
        array $data = []
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->addressRenderer = $addressRenderer;
        $this->coreRegistry = $registry;
        $this->customerSession = $customerSession;
        $this->imageHelper = $imageHelper;
        $this->productRepository = $productRepository;
        $this->priceHelper = $priceHelper;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->orderConfig = $orderConfig;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->localeDate = $localeDate;
        $this->orderFactory = $orderFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get current order
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->checkoutSession->getLastRealOrder();
    }

    /**
     * Format price
     *
     * @param float $price
     * @param bool $includeContainer
     * @param bool $format
     * @return float
     */
    public function formatPrice($price, $includeContainer = true, $format = true)
    {
        return $this->priceHelper->currency($price, $includeContainer, $format);
    }

    /**
     * Format shipping address
     *
     * @return string|null
     */
    public function formatShipping()
    {
        $order = $this->getOrder();
        $shipping = $order->getShippingAddress();
        return $shipping ? $this->addressRenderer->format($shipping, 'html') : null;
    }

    /**
     * Format billing address
     *
     * @return string|null
     */
    public function formatBilling()
    {
        $order = $this->getOrder();
        $billing = $order->getBillingAddress();
        return $billing ? $this->addressRenderer->format($billing, 'html') : null;
    }

    /**
     * Get product image URL
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @return string
     */
    public function getImageUrl($product, $imageId = 'product_thumbnail_image')
    {
        try {
            return $this->imageHelper->init($product, $imageId)->getUrl();
        } catch (\Exception $e) {
            return $this->imageHelper->getDefaultPlaceholderUrl('thumbnail');
        }
    }

    /**
     * Get item options
     *
     * @param \Magento\Sales\Model\Order\Item $item
     * @return array
     */
    public function getItemOptions($item)
    {
        $result = [];
        $options = $item->getProductOptions();
        if ($options) {
            if (isset($options['options'])) {
                $result = array_merge($result, $options['options']);
            }
            if (isset($options['additional_options'])) {
                $result = array_merge($result, $options['additional_options']);
            }
            if (isset($options['attributes_info'])) {
                $result = array_merge($result, $options['attributes_info']);
            }
        }
        return $result;
    }

    /**
     * Get bundle item options
     *
     * @param \Magento\Sales\Model\Order\Item $item
     * @return array
     */
    public function getBundleItemOptions($item)
    {
        $result = [];
        $options = $item->getProductOptions();
        if ($options) {
            if (isset($options['bundle_options'])) {
                $result = $options['bundle_options'];
            }
        }
        return $result;
    }

    /**
     * Get customer ID
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->customerSession->isLoggedIn() ? $this->customerSession->getCustomerId() : null;
    }

    /**
     * Get reorder URL
     *
     * @return string
     */
    public function getReorder()
    {
        $order = $this->getOrder();
        return $this->getUrl('sales/order/view', ['order_id' => $order->getId()]);
    }

    /**
     * Get print URL
     *
     * @return string
     */
    public function getPrint()
    {
        $order = $this->getOrder();
        return $this->getUrl('sales/order/print', ['order_id' => $order->getId()]);
    }

    /**
     * Check if details are enabled
     *
     * @return bool
     */
    public function isEnableDetails()
    {
        return (bool)$this->scopeConfig->getValue(
            'order_details/general/enable',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if order product details are enabled
     *
     * @return bool
     */
    public function isEnableOrderProductDetails()
    {
        return (bool)$this->scopeConfig->getValue(
            'order_details/general/show_order_product',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if shipping address is enabled
     *
     * @return bool
     */
    public function isEnableShippingAddressDetails()
    {
        return (bool)$this->scopeConfig->getValue(
            'order_details/general/show_shipping_address',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if shipping method is enabled
     *
     * @return bool
     */
    public function isEnableShippingMethodDetails()
    {
        return (bool)$this->scopeConfig->getValue(
            'order_details/general/show_shipping_method',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if billing address is enabled
     *
     * @return bool
     */
    public function isEnableBillingAddressDetails()
    {
        return (bool)$this->scopeConfig->getValue(
            'order_details/general/show_billing_address',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if payment method is enabled
     *
     * @return bool
     */
    public function isEnablePaymentMethodDetails()
    {
        return (bool)$this->scopeConfig->getValue(
            'order_details/general/show_payment_method',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if print order link is enabled
     *
     * @return bool
     */
    public function canViewPrint()
    {
        return (bool)$this->scopeConfig->getValue(
            'order_details/general/show_print_order_link',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if reorder link is enabled
     *
     * @return bool
     */
    public function canViewReorder()
    {
        $isReorderEnabled = $this->scopeConfig->getValue(
            'sales/reorder/allow',
            ScopeInterface::SCOPE_STORE
        );
        $isReorderLinkEnabled = $this->scopeConfig->getValue(
            'order_details/general/show_reorder_link',
            ScopeInterface::SCOPE_STORE
        );
        
        return $this->customerSession->isLoggedIn() && $isReorderEnabled && $isReorderLinkEnabled;
    }

    /**
     * Check if order status is enabled
     *
     * @return bool
     */
    public function isEnableOrderStatusDetails()
    {
        return (bool)$this->scopeConfig->getValue(
            'order_details/general/show_order_status',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get thank you message
     *
     * @return string
     */
    public function getThankMessegerDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/thank_cofig/thank_messager',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get before text
     *
     * @return string
     */
    public function getBeforeTextDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/before_config/text_before',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get after text
     *
     * @return string
     */
    public function getAfterTextDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/after_config/text_after',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get thank you message size
     *
     * @return string
     */
    public function getThankMessegerSizeDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/thank_cofig/size_thanks',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get before text size
     *
     * @return string
     */
    public function getBeforeTextSizeDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/before_config/size_before_text',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get after text size
     *
     * @return string
     */
    public function getAfterTextSizeDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/after_config/size_after_text',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get thank you message color
     *
     * @return string
     */
    public function getThankMessegerColorDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/thank_cofig/color_thanks',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get before text color
     *
     * @return string
     */
    public function getBeforeTextColorDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/before_config/color_text_before',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Text After Color
     *
     * @return string
     */
    public function getAfterTextColorDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/after_config/color_text_after',
        ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if CMS block is enabled
     *
     * @return bool
     */
    public function isCmsBlockEnabled()
    {
        return (bool)$this->scopeConfig->getValue(
            'order_details/custom_block_config/enable_cms_block_1',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get CMS block identifier
     *
     * @return string
     */
    public function getCmsBlockDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/custom_block_config/cms_block',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if second CMS block is enabled
     *
     * @return bool
     */
    public function isSecondCmsBlockEnabled()
    {
        return (bool)$this->scopeConfig->getValue(
            'order_details/custom_block_config/enable_cms_block_2',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get second CMS block identifier
     *
     * @return string
     */
    public function getSecondCmsBlockDetails()
    {
        return $this->scopeConfig->getValue(
            'order_details/custom_block_config/second_cms_block',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get order totals
     *
     * @return array
     */
    public function getTotals()
    {
        $order = $this->getOrder();
        if (!$order) {
            return [];
        }
        
        $totals = [];
        
        // Add subtotal
        $totals['subtotal'] = new \Magento\Framework\DataObject([
            'code' => 'subtotal',
            'value' => $order->getSubtotal(),
            'label' => __('Subtotal')
        ]);
        
        // Add shipping
        if (!$order->getIsVirtual() && ((float)$order->getShippingAmount() || $order->getShippingDescription())) {
            $totals['shipping'] = new \Magento\Framework\DataObject([
                'code' => 'shipping',
                'value' => $order->getShippingAmount(),
                'label' => __('Shipping & Handling')
            ]);
        }
        
        // Add discount
        if ((float)$order->getDiscountAmount() != 0) {
            $totals['discount'] = new \Magento\Framework\DataObject([
                'code' => 'discount',
                'value' => $order->getDiscountAmount(),
                'label' => __('Discount')
            ]);
        }
        
        // Add grand total
        $totals['grand_total'] = new \Magento\Framework\DataObject([
            'code' => 'grand_total',
            'value' => $order->getGrandTotal(),
            'label' => __('Grand Total'),
            'strong' => true
        ]);
        
        return $totals;
    }

    /**
     * Format total value based on order currency
     *
     * @param \Magento\Framework\DataObject $total
     * @return string
     */
    public function formatValue($total)
    {
        if (!$total->getIsFormated()) {
            return $this->formatPrice($total->getValue());
        }
        return $total->getValue();
    }

    public function getOrderId()
    {
        $order = $this->getOrder();
        return $order ? $order->getIncrementId() : null;
    }

    public function getOrderData()
    {
        $orderId = $this->getOrderId();
        if (!$orderId) {
            return [];
        }

        $order = $this->orderFactory->create()->load($orderId);
        $items = [];

        foreach ($order->getAllVisibleItems() as $item) {
            $items[] = [
                'name' => $item->getName(),
                'qty' => (int)$item->getQtyOrdered(),
                'price' => $order->formatPrice($item->getPrice())
            ];
        }

        return ['items' => $items];
    }
}
