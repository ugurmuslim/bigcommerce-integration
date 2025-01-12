<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProjectInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:project-init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize project by running migrations, seeding, and syncing categories and products.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Step 1: Check if there are any pending migrations
        $this->info('Checking for pending migrations...');

        $pendingMigrations = $this->getPendingMigrations();

        if ($pendingMigrations) {
            $this->info('Migrating database...');
            Artisan::call('migrate');

            // Step 2: Seed the database after migration
            $this->info('Seeding database...');
            Artisan::call('db:seed');
        } else {
            $this->info('No pending migrations found.');
        }

        // Step 3: Run category-sync and product-sync commands
        $this->info('Syncing categories...');
        Artisan::call('app:category-sync');

        $this->info('Syncing products...');
        Artisan::call('app:product-sync');

        $this->info('Project initialization completed.');
    }

    /**
     * Check for any pending migrations.
     *
     * @return bool
     */
    private function getPendingMigrations()
    {
        // Check if the migrations table exists
        if (!Schema::hasTable('migrations')) {
            $this->error('Migrations table does not exist.');
            return true;
        }

        $pendingMigrations = DB::table('migrations')->whereNull('batch')->get();

        return $pendingMigrations->isNotEmpty();
    }
}
