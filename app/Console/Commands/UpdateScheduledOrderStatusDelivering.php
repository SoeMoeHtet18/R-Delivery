<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
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
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        $user = User::orderBy('id','asc')->first();

        // Update orders with scheduled date matching today to delivering
        $orders = Order::whereDate('schedule_date', $today)
            ->whereNotIn('status',['success','cancel'])
            ->whereNotNull('rider_id')
            ->get();
        
        foreach($orders as $order) {
            $this->orderService->changeStatus($order, 'delivering', $user, User::class);
        }

        $this->info('Order status updated successfully.');
    }
}
