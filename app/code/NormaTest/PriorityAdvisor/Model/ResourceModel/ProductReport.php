<?php
/**
 * Test fixture: intentional violations for Norma Priority Advisor (quick_win).
 * Rule: php-scl-memory-exhaustion-via-monolithic-result-set-loading-pdostatement-fetchall-in-loops
 */
declare(strict_types=1);

namespace NormaTest\PriorityAdvisor\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class ProductReport
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @param ResourceConnection $resource
     */
    public function __construct(ResourceConnection $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Product ids grouped by website.
     *
     * @param int[] $productIds
     * @return array
     */
    public function getWebsitesByProduct(array $productIds): array
    {
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from($this->resource->getTableName('catalog_product_website'), ['product_id', 'website_id'])
            ->where('product_id IN (?)', $productIds, \Zend_Db::INT_TYPE);
        $rowset = $connection->fetchAll($select);

        $result = [];
        foreach ($rowset as $row) {
            $result[$row['product_id']][] = $row['website_id'];
        }

        return $result;
    }

    /**
     * Product SKUs keyed by entity id.
     *
     * @return array
     */
    public function getAllSkus(): array
    {
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from($this->resource->getTableName('catalog_product_entity'), ['entity_id', 'sku']);
        $rows = $connection->fetchAll($select);

        $skus = [];
        foreach ($rows as $row) {
            $skus[$row['entity_id']] = $row['sku'];
        }

        return $skus;
    }

    /**
     * Total quantity ordered per product.
     *
     * @return array
     */
    public function getOrderedQuantities(): array
    {
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from($this->resource->getTableName('sales_order_item'), ['product_id', 'qty_ordered']);
        $items = $connection->fetchAll($select);

        $totals = [];
        foreach ($items as $item) {
            $productId = (int)$item['product_id'];
            $totals[$productId] = ($totals[$productId] ?? 0) + (float)$item['qty_ordered'];
        }

        return $totals;
    }
}
