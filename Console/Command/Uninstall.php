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

namespace Mageplaza\OrderExportSampleData\Console\Command;

use Mageplaza\OrderExportSampleData\Setup\Patch\Data\InstallOrderExportSampleData;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Uninstall
 * @package Mageplaza\OrderExportSampleData\Console\Command
 */
class Uninstall extends CleanData
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('order-export-sample-data:uninstall')
            ->setDescription('Uninstall OrderExport Sample Data');
    }

    /**
     * Remove data from patch_list
     *
     * @param OutputInterface $output
     */
    public function removePathList($output)
    {
        if ($this->patchHistory->isApplied(InstallOrderExportSampleData::class)) {
            $this->patchHistory->revertPatchFromHistory(InstallOrderExportSampleData::class);
            $output->writeln(
                '<info>Patch has been already cleaned</info>'
            );
        } else {
            $output->writeln(
                '<info>Patch is not applied</info>'
            );
        }
    }

    /**
     * @param OutputInterface $output
     */
    public function uninstall($output)
    {
        parent::uninstall($output);
        $this->removePathList($output);
        $output->writeln(
            '<info>Sample Data uninstalled successful. Now you can remove its source code or disable this module</info>'
        );
    }
}
