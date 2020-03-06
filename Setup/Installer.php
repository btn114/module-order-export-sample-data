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

namespace Mageplaza\OrderExportSampleData\Setup;

use Exception;
use Magento\Framework\Setup;
use Mageplaza\OrderExportSampleData\Model\OrderExport;

/**
 * Class Installer
 * @package Mageplaza\OrderExportSampleData\Setup
 */
class Installer implements Setup\SampleData\InstallerInterface
{
    /**
     * @var OrderExport
     */
    private $orderExport;

    /**
     * Installer constructor.
     *
     * @param OrderExport $orderExport
     */
    public function __construct(
        OrderExport $orderExport
    ) {
        $this->orderExport = $orderExport;
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function install()
    {
        $this->orderExport->install(['Mageplaza_OrderExportSampleData::fixtures/order_export_profile.csv']);
    }
}
