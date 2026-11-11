<?php
/**
 * Test fixture: intentional violations for Norma Priority Advisor (commonly_neglected).
 * Rule: adobe-empty-catch-block
 */
declare(strict_types=1);

namespace NormaTest\PriorityAdvisor\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class HostCollector
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var string[]
     */
    private $hosts = [];

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * Collect hosts of every store.
     *
     * @return string[]
     */
    public function collect(): array
    {
        foreach ($this->storeManager->getStores() as $store) {
            try {
                $this->hosts[] = $store->getBaseUrl(UrlInterface::URL_TYPE_STATIC);
            } catch (\UnexpectedValueException $e) {}

            try {
                $this->hosts[] = $store->getWebsite()->getDefaultStore()->getBaseUrl();
            } catch (NoSuchEntityException $e) {}
        }

        try {
            $this->hosts[] = $this->storeManager->getDefaultStoreView()->getBaseUrl();
        } catch (\Exception $e) {
        }

        return array_unique($this->hosts);
    }
}
