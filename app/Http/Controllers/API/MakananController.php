<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Makanan;
use App\Menu;
use Carbon\Carbon;
use App\MenuCategory;
use App\MenuPlan;
use App\MenuPlanDetail;
use App\MenuPackage;
use Illuminate\Support\Facades\Input;
use PhpParser\Node\Stmt\ElseIf_;

class MakananController extends Controller
{

    public function data()
    {
        if (Input::has('page') && Input::has('totalperpage')) return $this->data_page(Input::get('totalperpage'), Input::get('page'));

        $data = Menu::get();

        if (Input::has('category_id'))
            $data = Menu::where('menu_category_id', Input::get('category_id'))->get();
        $limit = 0;
        if (Input::has('limit')) {
            $limit = Input::get('limit');
            if ($limit == "all") {
                $limit = 9999999999;
            }
        } else {
            $limit = 9999999999;
        }
        if (Input::has('isActive')) {
            $data = Menu::where('isActive', Input::get('isActive'))->get();
        }
        if (Input::has('menuplan')) {
            $tommorow = Carbon::tomorrow()->toDateString();
            $plan = MenuPlan::whereDate('date', "$tommorow")->get();
            if ($plan->isEmpty()) {
                $no = 1;
                do {
                    $no++;
                    $days = Carbon::today()->addDays($no)->toDateString();
                    $plan = MenuPlan::whereDate('date', "$days")->get();
                } while ($plan->isEmpty() && $no <= 6);
                // dd($no);
                if ($plan->isEmpty()) {
                    return [
                        'status' => false,
                        'message' => 'Gagal mendapatkan menu plan',
                        'data' =>  [],
                    ];
                }
            }
            return $plan->map(function ($item) {
                return [
                    'status' => true,
                    'message' => 'Berhasil mendapatkan menu plan',
                    'data' =>  [
                        'id'   => $item['id'],
                        'name' =>  $item['name'],
                        'date' =>  date('d-m-Y', strtotime($item['date'])),
                        'menu_plan_detail' => $item->detail->map(function ($item) {
                            return [
                                'id' => $item->menu->id,
                                'status' => $item['status'],
                                'menu' => [
                                    'id'        => $item->menu->id,
                                    'menu_name' => $item->menu->name,
                                    'description' => $item->menu->description,
                                    'price' => $item->menu->price,
                                    'image' => url('/storage/images/' . $item->menu->image),
                                    'category' => [
                                        'id' => $item->menu->categories->id,
                                        'name' => $item->menu->categories->name
                                    ],
                                ],
                            ];
                        }),
                    ],
                ];
            });
        }

        $now = 0;
        if ($data) {
            $data_result = null;
            foreach ($data as $dt) {
                if ($now == $limit) break;

                // Menu Package
                $menu_package = [];

                $mp = MenuPackage::where('parent_id', $dt->id)->get();
                if ($mp->count() == 0) {
                    $menu_package = null;
                } else {
                    foreach ($mp as $dts) {
                        $menu = Menu::find($dts->menu_id);
                        if ($menu) {
                            $menu_package[] = ([
                                'menu'      => [
                                    'id'                => $menu->id,
                                    'name'              => $menu->name,
                                    'description'       => $menu->description,
                                    'price'             => $menu->price,
                                    'image'             => url('/storage/images/' . $menu->image),
                                    'menu_category_id'  => [
                                        'id'    => $menu->menu_category_id,
                                        'name'  => MenuCategory::find($menu->menu_category_id)->name
                                    ],
                                    'vendor'            => [
                                        'id'        => $menu->vendor_id,
                                        'name'      => Vendor::find($menu->vendor_id)->name,
                                        'address'   => Vendor::find($menu->vendor_id)->address,
                                        'tagline'   => Vendor::find($menu->vendor_id)->tagline
                                    ]
                                ]
                            ]);
                        }
                    }
                }
                // End

                $now++;
                $data_result[] = ([
                    'id' => $dt->id,
                    'name' => $dt->name,
                    'description' => $dt->description,
                    'price' => $dt->price,
                    'image' => url('/storage/images/' . $dt->image),
                    'menu_category_id' => [
                        'id' => $dt->menu_category_id,
                        'name' => MenuCategory::find($dt->menu_category_id)->name
                    ],
                    'menu_package' => $menu_package,
                    'vendor' => [
                        'id' => $dt->vendor_id,
                        'name' => Vendor::find($dt->vendor_id)->name,
                        'address' => Vendor::find($dt->vendor_id)->address,
                        'tagline' => Vendor::find($dt->vendor_id)->tagline
                    ]
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Success get food data',
                'data' => $data_result
            ]);
        } else return response()->json(['error' => 'Gagal mengambil data makanan'], 401);
    }
    public function data_page($total_per_page, $page)
    {
        $data = Makanan::get();
        if ($data) {
            $data_result = null;
            $current_page = intval($page) * intval($total_per_page) - intval($total_per_page);
            $max_page = intval($page) * intval($total_per_page);
            $no = 0;
            for ($a = 1; $a <= $total_per_page; $a++) {
                if ($current_page == count($data) || $current_page >= count($data)) break;
                if ($current_page == $max_page) break;
                $no++;
                $data_result[] = ([
                    'no' => $no,
                    'id' => $data[$current_page]['id'],
                    'name' => $data[$current_page]['name'],
                    'description' => $data[$current_page]['description'],
                    'price' => $data[$current_page]['price'],
                    'image' => url('/storage/images/' . $data[$current_page]['image']),
                    'isPackage' => $data[$current_page]['isPackage'],
                    'category' => [
                        'id' => $data[$current_page]['menu_category_id'],
                        'name' => MenuCategory::find($data[$current_page]['menu_category_id'])->name
                    ],
                    'vendor' => [
                        'id' => $data[$current_page]['vendor_id'],
                        'name' => Vendor::find($data[$current_page]['vendor_id'])->name,
                        'address' => Vendor::find($data[$current_page]['vendor_id'])->address,
                        'tagline' => Vendor::find($data[$current_page]['vendor_id'])->tagline
                    ]
                ]);
                $current_page++;
            }
            $total_page = intval(count($data) / $total_per_page);
            if ($total_page == 1) $total_page = 2;
            if ($total_page == 0) $total_page = 1;
            if (count($data) == $total_per_page) $total_page = 1;


            return response()->json([
                'page' => $page,
                'status' => true,
                'message' => 'Success get food data by page',
                'data' => $data_result,
                'total data' => count($data),
                'total page' => $total_page
            ]);
        } else return response()->json(['error' => 'Gagal mengambil data makanan'], 401);
    }

    public function datamenuplan(){
        $tommorow = Carbon::tomorrow()->toDateString();
        $plan = MenuPlan::whereDate('date', "$tommorow")->first();
        if ($plan === null) {
            $no = 1;
            do {
                $no++;
                $days = Carbon::today()->addDays($no)->toDateString();
                $plan = MenuPlan::whereDate('date', "$days")->first();
            } while ($plan === null && $no <= 6);
            // dd($no);
            if ($plan === null) {
                return [
                    'status' => false,
                    'message' => 'Gagal mendapatkan menu plan',
                    'data' =>  null,
                ];
            }
        }

        return [
            'status' => true,
            'message' => 'Berhasil mendapatkan menu plan',
            'data' => [
                'id'   => $plan['id'],
                'name' =>  $plan['name'],
                'date' =>  date('d-m-Y', strtotime($plan['date'])),
                'menu_plan_detail' => $plan->detail->map(function ($item) {
                    return [
                        'id' => $item->menu->id,
                        'status' => $item['status'],
                        'menu' => [
                            'id'        => $item->menu->id,
                            'menu_name' => $item->menu->name,
                            'description' => $item->menu->description,
                            'price' => $item->menu->price,
                            'image' => url('/storage/images/' . $item->menu->image),
                            'category' => [ 
                                'id' => $item->menu->categories->id,
                                'name' => $item->menu->categories->name
                            ],
                        ],
                    ];
                }),
            ]
        ];        
    }
}
