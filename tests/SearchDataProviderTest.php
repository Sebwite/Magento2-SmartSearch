<?php
namespace Sebwite\SmartSearch\Tests;




class BlaTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Magento\Search\Model\QueryFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $queryFactory;

    /**
     * @var \\Magento\Framework\Api\Search\SearchCriteriaFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $searchCriteriaFactory;

    public function setUp()
    {
        parent::setUp();

        $this->productRepository     = $this->getMockBuilder('Magento\Catalog\Api\ProductRepositoryInterface')->disableOriginalConstructor()->getMock();
        $this->image                 = $this->getMockBuilder('Magento\Catalog\Helper\Image')->disableOriginalConstructor()->getMock();
        $this->filterBuilder         = $this->getMockBuilder('Magento\Framework\Api\FilterBuilder')->disableOriginalConstructor()->getMock();
        $this->filterGroupBuilder    = $this->getMockBuilder('Magento\Framework\Api\Search\FilterGroupBuilder')->disableOriginalConstructor()->getMock();
        $this->searchCriteriaFactory = $this->getMockBuilder('Magento\Framework\Api\Search\SearchCriteriaFactory')->disableOriginalConstructor()->getMock();
        $this->search                = $this->getMockBuilder('Magento\Framework\Api\Search\SearchInterface')->disableOriginalConstructor()->getMock();
        $this->searchCriteriaBuilder = $this->getMockBuilder('Magento\Framework\Api\SearchCriteriaBuilder')->disableOriginalConstructor()->getMock();
        $this->priceCurrency         = $this->getMockBuilder('Magento\Framework\Pricing\PriceCurrencyInterface')->disableOriginalConstructor()->getMock();
        $this->dataProvider          = $this->getMockBuilder('Magento\Search\Model\Autocomplete\DataProviderInterface')->disableOriginalConstructor()->getMock();
        $this->itemFactory           = $this->getMockBuilder('Magento\Search\Model\Autocomplete\ItemFactory')->disableOriginalConstructor()->getMock();
        $this->queryFactory          = $this->getMockBuilder('Magento\Search\Model\QueryFactory')->disableOriginalConstructor()->getMock();
        $this->storeManager          = $this->getMockBuilder('Magento\Store\Model\StoreManagerInterface')->disableOriginalConstructor()->getMock();

    }

    protected function createSearchProvider(){

        return new \Sebwite\SmartSearch\Model\Autocomplete\SearchDataProvider(
            $this->queryFactory,
            $this->itemFactory,
            $this->search,
            $this->searchCriteriaFactory,
            $this->filterGroupBuilder, //searchFilterGroupBuilder,
            $this->filterBuilder, //FilterBuilder $filterBuilder,
            $this->productRepository, //ProductRepositoryInterface $productRepository,
            $this->searchCriteriaBuilder, //SearchCriteriaBuilder $searchCriteriaBuilder,
            $this->storeManager, //StoreManagerInterface $storeManager,
            $this->priceCurrency, //PriceCurrencyInterface $priceCurrency,
            $this->image //Image $imageHelper

        );

    }

    public function testConstruct()
    {
        $provider = $this->createSearchProvider();


    }

    public function testGetImage()
    {
        $queryFactory = $this->getMockBuilder('\Magento\Search\Model\QueryFactory')->disableOriginalConstructor()->getMock();
        $queryFactory->expects($this->once())->method('getQueryText')->will($this->returnValue('jlkajfasljdflaksdf'));
        $this->queryFactory->expects($this->once())->method('get')->will($this->returnValue($queryFactory));
    }

    public function testSearchProductsFullText()
    {

    }
}
