<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Catalog\KeyBuilder\ProductResourceKeyBuilder;
use Spryker\Client\Catalog\Model\Builder\FacetAggregationBuilder;
use Spryker\Client\Catalog\Model\Builder\FilterBuilder;
use Spryker\Client\Catalog\Model\Builder\NestedFilterBuilder;
use Spryker\Client\Catalog\Model\Catalog as ModelCatalog;
use Spryker\Client\Catalog\Model\Extractor\FacetExtractor;
use Spryker\Client\Catalog\Model\Extractor\RangeExtractor;
use Spryker\Client\Catalog\Model\FacetConfig;
use Spryker\Client\Catalog\Model\FacetFilterHandler;
use Spryker\Client\Catalog\Model\FacetSearch;
use Spryker\Client\Catalog\Model\FulltextSearch;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\HttpFoundation\Request;

class CatalogFactory extends AbstractFactory
{

    /**
     * @var \Generated\Client\Ide\FactoryAutoCompletion\Catalog
     */
    protected $factory;

    /**
     * @return Model\Catalog
     */
    public function createCatalogModel()
    {
        return new ModelCatalog(
            $this->getProductKeyBuilder(),
            $this->createStorage(),
            Store::getInstance()->getCurrentLocale()
        );
    }

    /**
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function createStorage()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::KVSTORAGE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $category
     *
     * @return Model\FacetSearch
     */
    public function createFacetSearch(Request $request, array $category)
    {
        $facetConfig = $this->createFacetConfig();

        return new FacetSearch(
            $request,
            $facetConfig,
            $this->getSearchIndex(),
            $this->getFacetAggregationBuilder(),
            $this->createFacetFilterHandler($facetConfig),
            $this->getFacetExtractor(),
            $this->getRangeExtractor(),
            $this->createCatalogModel(),
            $category
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Model\FulltextSearch
     */
    public function createFulltextSearch(Request $request)
    {
        $facetConfig = $this->createFacetConfig();

        return new FulltextSearch(
            $request,
            $facetConfig,
            $this->getSearchIndex(),
            $this->getFacetAggregationBuilder(),
            $this->createFacetFilterHandler($facetConfig),
            $this->getFacetExtractor(),
            $this->getRangeExtractor(),
            $this->createCatalogModel()
        );
    }

    /**
     * @return \Spryker\Client\Catalog\Model\FacetConfig
     */
    public function createFacetConfig()
    {
        return new FacetConfig();
    }

    /**
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     *
     * @return Model\FacetFilterHandler
     */
    protected function createFacetFilterHandler(FacetConfig $facetConfig)
    {
        return new FacetFilterHandler(
            $this->createNestedFilterBuilder(),
            $facetConfig
        );
    }

    /**
     * @return \Elastica\Index
     */
    protected function getSearchIndex()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::INDEX);
    }

    /**
     * @return Model\Builder\FacetAggregationBuilder
     */
    protected function getFacetAggregationBuilder()
    {
        return new FacetAggregationBuilder();
    }

    /**
     * @return Model\Extractor\FacetExtractor
     */
    protected function getFacetExtractor()
    {
        return new FacetExtractor();
    }

    /**
     * @return Model\Extractor\RangeExtractor
     */
    protected function getRangeExtractor()
    {
        return new RangeExtractor();
    }

    /**
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected function getProductKeyBuilder()
    {
        return new ProductResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Catalog\Model\Builder\NestedFilterBuilder
     */
    protected function createNestedFilterBuilder()
    {
        return new NestedFilterBuilder(
            $this->createFilterBuilder()
        );
    }

    /**
     * @return \Spryker\Client\Catalog\Model\Builder\FilterBuilder
     */
    protected function createFilterBuilder()
    {
        return new FilterBuilder();
    }

}
