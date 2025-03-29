<?php

namespace App\Console\Commands;

use App\Services\StockCacheService;
use Illuminate\Console\Command;

class UpdateStockCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:cache:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the warehouse stock cache';

    protected $stockCacheService;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(StockCacheService $stockCacheService)
    {
        parent::__construct();
        $this->stockCacheService = $stockCacheService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->stockCacheService->updateStockCache();
        $this->info('Stock cache updated successfully.');
    }
}
