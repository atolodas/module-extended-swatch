<?php
namespace Muhammedv\ExtendedSwatch\Block\Product\Renderer;

use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Framework\Json\EncoderInterface;
use Magento\ConfigurableProduct\Helper\Data;
use Magento\Catalog\Helper\Product as CatalogProduct;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use Magento\Swatches\Helper\Data as SwatchData;
use Magento\Swatches\Helper\Media;
use Magento\Swatches\Model\SwatchAttributesProvider;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Configurable extends \Magento\Swatches\Block\Product\Renderer\Configurable
{

    public function __construct(
        Context $context,
        ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        Data $helper,
        CatalogProduct $catalogProduct,
        CurrentCustomer $currentCustomer,
        PriceCurrencyInterface $priceCurrency,
        ConfigurableAttributeData $configurableAttributeData,
        SwatchData $swatchHelper,
        Media $swatchMediaHelper,
        array $data = [],
        SwatchAttributesProvider $swatchAttributesProvider = null,
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;

        parent::__construct(
            $context,
            $arrayUtils,
            $jsonEncoder,
            $helper,
            $catalogProduct,
            $currentCustomer,
            $priceCurrency,
            $configurableAttributeData,
            $swatchHelper,
            $swatchMediaHelper,
            $data,
            $swatchAttributesProvider
        );
    }


    /**
     * @var string
     */
    private $swatchTemplate = 'Muhammedv_ExtendedSwatch::product/view/configurable/static-child-info.phtml';

    /**
     * @var string
     */
    private $configurableTemplate = 'Muhammedv_ExtendedSwatch::product/view/configurable/static-child-info.phtml';

    /**
     * Set swatch template
     *
     * @param string $swatchTemplate
     * @return $this
     */
    public function setSwatchTemplate($swatchTemplate)
    {
        $this->swatchTemplate = $swatchTemplate;
        return $this;
    }

    /**
     * Get swatch template
     *
     * @return string
     */
    public function getSwatchTemplate()
    {
        return $this->swatchTemplate;
    }

    /**
     * Set configurable template
     *
     * @param  string $configurableTemplate
     * @return $this
     */
    public function setConfigurableTemplate($configurableTemplate)
    {
        $this->configurableTemplate = $configurableTemplate;
        return $this;
    }

    /**
     * Get configurable template
     *
     * @return string
     */
    public function getConfigurableTemplate()
    {
        return $this->configurableTemplate;
    }

    /**
     * Get renderer template
     *
     * @return string
     */
    protected function getRendererTemplate()
    {
        return $this->isProductHasSwatchAttribute ?
            $this->swatchTemplate : $this->configurableTemplate;
    }

    /**
     * Get selected swatches values as JSON
     *
     * @return array
     */
    public function getJsonChildConfig()
    {
        $config = [
            'additionalAttributes' => [],
            'descriptions' => [],
            'configDescription' => $this->getProduct()->getDescription()
        ];
        $attributesToSelect = ['description'];
        $allowAttributeIds = $this->getAllowAttributes()->getAllIds();

        $allowProducts = $this->getAllowProducts();
        foreach($allowProducts as $productId => $product) {
            $attributes = $product->getAttributes();
            foreach($attributes as $attributeCode => $attribute){
                if ($attribute->getIsVisibleOnFront()) {
                    $attributesToSelect[] = $attributeCode;
                }
            }
            break;
        }

        $productCollection = $this->collectionFactory->create();
        $productCollection->addAttributeToFilter('entity_id', ['in' => array_keys($this->getAllowProducts())]);
        $productCollection->addAttributeToSelect($attributesToSelect);
        foreach ($productCollection as $product){
            $attributes = $product->getAttributes();
            foreach($attributes as $attributeCode => $attribute){
                if ($attribute->getIsVisibleOnFront()) {
                    $value = $attribute->getFrontend()->getValue($product);
                    $config['additionalAttributes'][$product->getId()][$attribute->getId()] = [
                        'label' => $attribute->getFrontendLabel(),
                        'value' => $value ? $value : __('N/A')
                    ];
                }
                if ($attribute->getAttributeCode() == 'description') {
                    $config['descriptions'][$product->getId()] = $attribute->getFrontend()->getValue($product);
                }
            }
        }

        return $config;
    }
}