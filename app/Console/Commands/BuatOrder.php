<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\Employee;
use App\EmployeeMenu;
use App\Contract;
use App\ContractDetail;
use App\Corporate;
use App\ContractEmployee;
use App\MenuPlan;
use App\Menu;
use Carbon\Carbon;
use App\OrderDetail;


class BuatOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buat:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat Order Pada Jam 23:59';

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
     *
     * @return mixed
     */

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        // $tomorrow = Carbon::create(2019, 7, 24, 12)->toDateString(); 
        $menu_plan = MenuPlan::where('date', $tomorrow)->get();
        if ($menu_plan->isEmpty()) {
            $this->info('tidak ada menu plan untuk besok');
            return;
        }

        $contract =  Contract::where('status', 1)->pluck('id');
        for ($i = 0; $i < count($contract); $i++) {
            $cd = ContractDetail::where('contract_id', $contract[$i])->get();
            foreach ($cd as $dt) {
                $ce = ContractEmployee::where('contract_detail_id', $dt->id)->where('isActive', true)->get();
                foreach ($ce as $dt_ce) {
                    $order              = new Order();
                    $order->datetime    = $tomorrow;
                    $order->contract_id = $contract[$i];
                    $order->total_cost  = 0; // Sementara
                    $order->total_extra = 0; // Sementara
                    $order->employee_id = $dt_ce->employee_id;
                    $order->contract_detail_id = $dt->id;
                    $order->save();

                    $em = EmployeeMenu::where('employee_id', $dt_ce->employee_id)->get();
                    foreach ($em as $dts) {
                        $od             = new OrderDetail();
                        $od->order_id   = $order->id;
                        $od->price      = Menu::find($dts->menu_id)->price;
                        $od->notes      = $dts->notes;
                        $od->quantity   = $dts->quantity;
                        $od->menu_id    = $dts->menu_id;
                        $od->isExtra    = $dts->isExtra;
                        $od->save();
                    }

                    $total_cost = 0;
                    $total_extra = 0;

                    $od_list = OrderDetail::where('order_id', $order->id)->get();
                    foreach ($od_list as $dts) {
                        $total_cost += intval($dts->price) * intval($dts->quantity);
                    }

                    $total_extra = intval($total_cost) - intval($dt->budget);
                    if ($total_extra <= 0) {
                        $total_extra = 0;
                    }
                    $order->total_extra     = $total_extra;
                    $order->total_cost      = $total_cost;
                    $order->save();
                }
            }
        }
        $orders = $order->where('datetime', $tomorrow)->get()->count();
        $this->info(date('Y-m-d H:i:s') . 'command buat:order berhasil di jalankan dan  membuat ' . $orders . ' order');
    }
}
