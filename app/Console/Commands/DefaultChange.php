<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MenuPlan;
use App\MenuPlanDetail;
use App\EmployeeMenu;
use App\Employee;
use Carbon\Carbon;
use App\Contract;
use App\ContractDetail;
use App\ContractEmployee;

class DefaultChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat Employee Menu Sesuai Dengan Menu Plan';

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
        $days = Carbon::today()->addDays(2)->toDateString();
        $time = date('Y-m-d');
        $menu_plan = MenuPlan::with('detail')->where('date', "$days")->value('id');
        if ($menu_plan == null) {
            $no = 2;
            do {
                $no++;
                $days = Carbon::today()->addDays($no)->toDateString();
                $menu_plan = MenuPlan::with('detail')->where('date', "$days")->value('id');
            } while ($menu_plan == null && $no <= 6);
            // dd($no);
            if ($menu_plan == null) {
                return 'fail';
            }
        }
        $menu_plan_menu = MenuPlanDetail::where('menu_plan_id', "$menu_plan")->where('status', 1)->pluck('menu_id');

        $old_employee_menu = EmployeeMenu::truncate();

        $contract =  Contract::where('status', 1)->pluck('id');
        // dd($contract);
        for ($i = 0; $i < count($contract); $i++) {
        
            $cd = ContractDetail::where('contract_id', $contract[$i])->get();
            // dd($cd);
            foreach ($cd as $dt) {
                $ce = ContractEmployee::where('contract_detail_id', $dt->id)->get();
                for ($a = 0; $a < count($menu_plan_menu); $a++) {
                    // dd($contract[$a]);
                    foreach ($ce as $dt_ce) {
                        $employee_menu              = new EmployeeMenu();
                        $employee_menu->employee_id = $dt_ce->employee_id;
                        $employee_menu->menu_id     = $menu_plan_menu[$a];
                        $employee_menu->quantity    = 1;
                        $employee_menu->save();
                    }
                }
            }
        }
        $this->info(date('Y-m-d H:i:s').'command change:default berhasil di jalankan');
    }
}
