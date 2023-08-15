<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateScheduledOrderStatusDelivering extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-scheduled-order-status-delivering';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status to delivering for orders where the scheduled date matches today';

     /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');

        // Update orders with scheduled date matching today to delivering
        Order::whereDate('schedule_date', $today)
            ->whereNotNull('rider_id')
            ->update(['status' => 'delivering']);

        $this->info('Order status updated successfully.');
    }
}
