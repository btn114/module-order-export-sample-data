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
    private $file;

    /**
     * FreeShippingBar constructor.
     *
     * @param SampleDataContext $sampleDataContext
     * @param File $file
     * @param ProfileFactory $profileFactory
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        File $file,
        ProfileFactory $profileFactory
    ) {
        $this->fixtureManager         = $sampleDataContext->getFixtureManager();
        $this->csvReader              = $sampleDataContext->getCsvReader();
        $this->file                   = $file;
        $this->profileFactory = $profileFactory;
    }

    /**
     * @param array $fixtures
     *
     * @throws Exception
     */
    public function install(array $fixtures)
    {
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

                $this->profileFactory->create()
                    ->addData($row)
                    ->save();
            }
        }
    }
}
