<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Menu;
use App\ScheduleMenu;
use Illuminate\Console\Scheduling\Schedule;

class UbahMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ubah:menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengubah Menu pada jam yang di tentukan';

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
        $data_sch = ScheduleMenu::get();
        if($data_sch->count() != 0)
        {
            $data = Menu::get();
            foreach($data as $dt)
            {
                $dt->isActive = 0;
                $dt->save();
            }

            
            foreach($data_sch as $dt)
            {
                $menu = Menu::find($dt->menu_id);
                if($menu)
                {
                    $menu->isActive = 1;
                    $menu->save();
                    $dt->delete();
                }
            }
        }
        $this->info(date('Y-m-d H:i:s').' berhasil menjalankan command ubah:menu');
    }
}
