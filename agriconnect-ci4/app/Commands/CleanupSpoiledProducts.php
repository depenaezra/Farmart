<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\ProductModel;

class CleanupSpoiledProducts extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Maintenance';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'cleanup:spoiled-products';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Automatically delete spoiled products that haven\'t been discounted after the grace period.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'cleanup:spoiled-products [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--dry-run' => 'Show what would be deleted without actually deleting',
        '--days' => 'Number of days grace period (default: 5)',
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $productModel = new ProductModel();
        $graceDays = (int) CLI::getOption('days') ?: 5;
        $dryRun = CLI::getOption('dry-run');

        CLI::write('Starting spoiled products cleanup...', 'yellow');
        CLI::write("Grace period: {$graceDays} days", 'cyan');

        if ($dryRun) {
            CLI::write('DRY RUN MODE - No products will be deleted', 'yellow');
        }

        // Run the cleanup
        $deletedCount = $productModel->cleanupSpoiledProducts($graceDays);

        if ($dryRun) {
            CLI::write("Would delete {$deletedCount} spoiled products", 'cyan');
        } else {
            CLI::write("Successfully deleted {$deletedCount} spoiled products", 'green');
        }

        CLI::write('Cleanup completed.', 'green');
    }
}
