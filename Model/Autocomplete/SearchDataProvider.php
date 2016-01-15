<?php
/*
|--------------------------------------------------------------------------
|   Autocomplete SearchDataProvider
|--------------------------------------------------------------------------
|
|   Autocomplete
|
|   @author Sebwite
|   @date 07 augustus 2015
|   @time 17:35
*/
namespace Sebwite\SmartSearch\Model\Autocomplete;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\Search\SearchCriteriaFactory as FullTextSearchCriteriaFactory;
use Magento\Framework\Api\Search\SearchInterface as FullTextSearchApi;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Search\Model\Autocomplete\DataProviderInterface;
use Magento\Search\Model\Autocomplete\ItemFactory;
use Magento\Search\Model\QueryFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Full text search implementation of autocomplete.
 */
class SearchDataProvider implements DataProviderInterface
{
    const PRODUCTS_NUMBER_IN_SUGGEST = 5;

    /** @var QueryFactory */
    protected $queryFactory;

    /** @var ItemFactory */
    protected $itemFactory;

    /** @var \Magento\Framework\Api\Search\SearchInterface */
    protected $fullTextSearchApi;

    /** @var FullTextSearchCriteriaFactory */
    protected $fullTextSearchCriteriaFactory;

    /** @var FilterGroupBuilder */
    protected $searchFilterGroupBuilder;

    /** @var FilterBuilder */
    protected $filterBuilder;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;
    /**
     * @var ProductHelper
     */
    private $productHelper;

    /**
     * Initialize dependencies.
     *
     * @param QueryFactory                                      $queryFactory
     * @param ItemFactory                                       $itemFactory
     * @param FullTextSearchApi                                 $search
     * @param FullTextSearchCriteriaFactory                     $searchCriteriaFactory
     * @param FilterGroupBuilder                                $searchFilterGroupBuilder
     * @param FilterBuilder                                     $filterBuilder
     * @param ProductRepositoryInterface                        $productRepository
     * @param SearchCriteriaBuilder                             $searchCriteriaBuilder
     * @param StoreManagerInterface                             $storeManager
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        QueryFactory $queryFactory,
        ItemFactory $itemFactory,
        FullTextSearchApi $search,
        FullTextSearchCriteriaFactory $searchCriteriaFactory,
        FilterGroupBuilder $searchFilterGroupBuilder,
        FilterBuilder $filterBuilder,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManagerInterface $storeManager,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->queryFactory = $queryFactory;
        $this->itemFactory = $itemFactory;
        $this->fullTextSearchApi = $search;
        $this->fullTextSearchCriteriaFactory = $searchCriteriaFactory;
        $this->filterBuilder = $filterBuilder;
        $this->searchFilterGroupBuilder = $searchFilterGroupBuilder;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $result = [];
        $query = $this->queryFactory->get()->getQueryText();
        $productIds = $this->searchProductsFullText($query);

        // Check if products are found
        if ($productIds) {
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id', $productIds, 'in')->create();
            $products = $this->productRepository->getList($searchCriteria);

            $baseUrl = $this->storeManager->getStore()->getBaseUrl();

            // Loop through products

            foreach ($products->getItems() as $product) {

                $resultItem = $this->itemFactory->create([
                    'title'             => $product->getName(), 'price' => $this->priceCurrency->format($product->getFinalPrice(), false),
                    'special_price'     => $this->priceCurrency->format($product->getSpecialPrice(), false),
                    'has_special_price' => $product->getSpecialPrice() > 0 ? true : false,
                    'image'             => str_replace('index.php/', '', $baseUrl) . '/pub/media/catalog/product' . $product->getImage(),
                    'url'               => $product->getProductUrl()
                ]);
                $result[] = $resultItem;
            }
        }

        return $result;
    }

    /**
     * Perform full text search and find IDs of matching products.
     *
     * @param string
     * @return int[]
     */
    private function searchProductsFullText($query)
    {
        $searchCriteria = $this->fullTextSearchCriteriaFactory->create();

        /** To get list of available request names see Magento/CatalogSearch/etc/search_request.xml */
        $searchCriteria->setRequestName('quick_search_container');
        $filter = $this->filterBuilder->setField('search_term')->setValue($query)->setConditionType('like')->create();
        $filterGroup = $this->searchFilterGroupBuilder->addFilter($filter)->create();
        $currentPage = 1;
        $searchCriteria->setFilterGroups([$filterGroup])
            ->setCurrentPage($currentPage)
            ->setPageSize(self::PRODUCTS_NUMBER_IN_SUGGEST);
        $searchResults = $this->fullTextSearchApi->search($searchCriteria);
        $productIds = [];
        /**
         * Full text search returns document IDs (in this case product IDs),
         * so to get products information we need to load them using filtration by these IDs
         */
        foreach ($searchResults->getItems() as $searchDocument) {
            $productIds[] = $searchDocument->getId();
        }
        return $productIds;
    }
}