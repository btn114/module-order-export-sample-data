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

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Console\Cli;
use Magento\Framework\Setup\Patch\PatchHistory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class CleanData
 * @package Mageplaza\OrderExportSampleData\Console\Command
 */
class CleanData extends Command
{
    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var PatchHistory
     */
    protected $patchHistory;

    /**
     * Uninstall constructor.
     *
     * @param ResourceConnection $resource
     * @param PatchHistory $patchHistory
     * @param null $name
     */
    public function __construct(
        ResourceConnection $resource,
        PatchHistory $patchHistory,
        $name = null
    ) {
        $this->resource = $resource;
        $this->patchHistory = $patchHistory;

        parent::__construct($name);
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('order-export-sample-data:clean')
            ->setDescription('Clean OrderExport Sample Data');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper   = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'Note that all data of this module will be delete.Are you sure to remove the data from database?[y/N]',
            false
        );

        if (!$helper->ask($input, $output, $question) && $input->isInteractive()) {
            return Cli::RETURN_FAILURE;
        }

        $this->uninstall($output);

        return Cli::RETURN_SUCCESS;
    }

    /**
     * Delete data from database
     *
     * @param OutputInterface $output
     */
    protected function uninstall($output)
    {
        $connection = $this->resource->getConnection();
        $table      = $this->resource->getTableName('mageplaza_orderexport_profile');

        $connection->delete($table);
        $output->writeln(
            '<info>Data has been already cleaned</info>'
        );
    }
}
