<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_OrderExportSampleData
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\OrderExportSampleData\Model;

use Exception;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\Setup\SampleData\FixtureManager;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Mageplaza\OrderExport\Helper\Data;
use Mageplaza\OrderExport\Model\ProfileFactory;

/**
 * Class OrderExport
 * @package Mageplaza\OrderExportSampleData\Model
 */
class OrderExport
{
    /**
     * @var FixtureManager
     */
    private $fixtureManager;

    /**
     * @var Csv
     */
    protected $csvReader;

    /**
     * @var ProfileFactory
     */
    protected $profileFactory;

    /**
     * @var File
     */
    protected $file;
    /**
     * @var CollectionFactory
     */
    protected $orderCollectionFactory;
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * FreeShippingBar constructor.
     *
     * @param SampleDataContext $sampleDataContext
     * @param File $file
     * @param CollectionFactory $orderCollectionFactory
     * @param ProfileFactory $profileFactory
     * @param Data $helperData
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        File $file,
        CollectionFactory $orderCollectionFactory,
        ProfileFactory $profileFactory,
        Data $helperData
    ) {
        $this->fixtureManager         = $sampleDataContext->getFixtureManager();
        $this->csvReader              = $sampleDataContext->getCsvReader();
        $this->file                   = $file;
        $this->profileFactory = $profileFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->helperData = $helperData;
    }

    /**
     * @param array $fixtures
     *
     * @throws Exception
     */
    public function install(array $fixtures)
    {
        $firstOrderId = $this->orderCollectionFactory->create()->getFirstItem()->getId();
        foreach ($fixtures as $fileName) {
            $fileName = $this->fixtureManager->getFixture($fileName);
            if (!$this->file->isExists($fileName)) {
                continue;
            }

            $rows   = $this->csvReader->setEnclosure("'")->getData($fileName);
            $header = array_shift($rows);

            foreach ($rows as $row) {
                $data = [];
                foreach ($row as $key => $value) {
                    $data[$header[$key]] = $value;
                }
                $row = $data;

                $profile = $this->profileFactory->create()
                    ->addData($row)
                    ->save();
                $profile->setMatchingIds([$firstOrderId]);
                $this->helperData->generateProfile($profile, true);
            }
        }
    }
}
