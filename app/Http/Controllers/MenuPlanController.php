<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Menu;
use App\MenuPackage;
use App\MenuPlan;
use App\MenuPlanDetail;
use App\MenuCategory;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\LengthAwarePaginator;

class MenuPlanController extends Controller
{
    public function index(Request $request)
    {
        $mutable = Carbon::now();
        $result = [];
        if ($request->search == null) {        
         $data = MenuPlan::orderBy('date')->get();
         
         foreach ($data as $key => $value) {
             if(!$value->date->isSameMonth($mutable)){
                 unset($data[$key]);
             }
         }
        } else {
            $data = MenuPlan::orderBy('date')->filter($request)->get();
        }


        foreach ($data as $dt) {
            $data_detail = [];
            $datad = MenuPlanDetail::where('menu_plan_id', $dt->id)->get();
            foreach ($datad as $dts) {
                $data_detail[] = ([
                    'id'        => $dts->id,
                    'menu_id'   => $dts->menu_id,
                    'isDefault' => $dts->status
                ]);
            }

            $result[] = ([
                'id' => $dt->id,
                'name' => $dt->name,
                'date' => $dt->date,
                'detail' => $data_detail
            ]);
        }

        // Convert Array menjadi Collection
        $itemCollection = collect($result);

        // Tentukan Berapa Data Dalam 1 Page
        $perPage = 12;

        // Mengambil Page Yang Sekarang
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Memotong / filter Beberapa Data Yang Hanya dalam $currentPage (Misal Jika dalam page 5 hanya 4 data maka yang ditampilkan adalah 4 data dari page ke 5)
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Membuat Paginator dari Data Sudah Di potong / filter diatas
        $result = new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
  
        // Menentukan Url Saat generate Link
        $result->setPath($request->url());

        return view('menu_plan.index', compact('result'));
    }

    public function tambah_page()
    {
        $result = [];
        $list_kategori = "";
        $data_kategori = MenuCategory::get();

        //get all category for checking
        $categories = MenuCategory::where('name','!=','Paket')->get(); //exclude category paket for unknown reason ._.
        $arrayCategories = [];
        foreach ($categories as $category) {
            $list_kategori .= $category->id . ',';
        }

        foreach ($data_kategori as $dt) {
            $makanan = [];
            $data_makanan = Menu::where('menu_category_id', $dt->id)->orderBy('name', 'asc')->get();
            if ($data_makanan) {
                foreach ($data_makanan as $dts) {
                    $makanan[] = ([
                        'id'            => $dts->id,
                        'nama_makanan'  => $dts->name,
                        'harga'         => number_format($dts->price),
                        'harga_asli'    => $dts->price
                    ]);
                    // if (!$menu_package) {
                    //     $makanan[] = ([
                    //         'id'            => $dts->id,
                    //         'nama_makanan'  => $dts->name,
                    //         'harga'         => number_format($dts->price),
                    //         'harga_asli'    => $dts->price
                    //     ]);
                    // }
                }
            }
            $result[] = ([
                'id_kategori'    => $dt->id,
                'nama_kategori'  => $dt->name,
                'makanan'        => $makanan
            ]);
        }
        $new_result = collect($result)->where('nama_kategori', '!=', 'Paket')->chunk(2);           
        return view('menu_plan.tambah', compact('new_result', 'list_kategori'));
    }

    public function tambah(request $request)
    {                
        $date = $request->date;

        if($date >= date('Y-m-d')){          
        } else { 
            Session::flash('error', 'Gagal Menambahkan data! Tidak bisa membuat Rencana Menu sebelum hari ini');
            return redirect('/menu_plan');
        }
        $plan = MenuPlan::whereDate('date', $date)->get();

        if ($plan->isNotEmpty()) {            
            $menu_plan = MenuPlan::whereDate('date', $date)->value('id');
            $lm = $request->list_menu;
            $lms = explode(',', $lm);
            for ($i = 0; $i < count($lms) - 1; $i++) {
                if($lms[$i] != "")
                {
                    $id = explode('|', $request->get('menu' . $lms[$i]))[0];                    
                    $menu = Menu::find($id);
                    if ($menu) {
                        $menu_plan_detail                        = new MenuPlanDetail();
                        $menu_plan_detail->menu_plan_id           = $menu_plan;
                        $menu_plan_detail->status                = $request->get('status' . $lms[$i]);
                        $menu_plan_detail->menu_id               = $id;
                        $menu_plan_detail->save();
                    }
                }
            }
            Session::flash('success', 'Berhasil Menambahkan Rencana Menu Baru');
            return redirect('/menu_plan');
        } else {            
            $plan = MenuPlan::whereDate('date', $date)->get();            
                $menu_plan = new MenuPlan;
                $menu_plan->name = $request->name;
                $menu_plan->date = $request->date;
                $menu_plan->save();

                $lm = $request->list_menu;
                $lms = explode(',', $lm);
                for ($i = 0; $i < count($lms) - 1; $i++) {
                    if($lms[$i] != "")
                    {
                        $id = explode('|', $request->get('menu' . $lms[$i]))[0];                        
                        $menu = Menu::find($id);
                        if ($menu) {
                            $menu_plan_detail                        = new MenuPlanDetail();
                            $menu_plan_detail->menu_plan_id          = $menu_plan->id;
                            $menu_plan_detail->status                = $request->get('status' . $lms[$i]);
                            $menu_plan_detail->menu_id               = $id;
                            $menu_plan_detail->save();
                        }
                    }
                }

                Session::flash('success', 'Berhasil Menambahkan Rencana Menu Baru');
                return redirect('/menu_plan');
            //}
        }
    }

    

    public function ubah_page($id)
    {
        $menu = MenuPlan::find($id);

        if($menu)
        {
            $list_kategori = "";
            $data_kategori = MenuCategory::get();
            foreach ($data_kategori as $dt) {
                $list_kategori .= $dt->id . ',';
            }

            foreach ($data_kategori as $dt) {
                $makanan = [];
                $data_makanan = Menu::where('menu_category_id', $dt->id)->orderBy('name', 'asc')->get();
                if ($data_makanan) {
                    foreach ($data_makanan as $dts) {
                        
                        $makanan[] = ([
                            'id'            => $dts->id,
                            'nama_makanan'  => $dts->name,
                            'harga'         => number_format($dts->price),
                            'harga_asli'    => $dts->price
                        ]);
                        // $menu_package = MenuPackage::where('parent_id', $dts->id)->first();
                        // if (!$menu_package) {
                        //     $makanan[] = ([
                        //         'id'            => $dts->id,
                        //         'nama_makanan'  => $dts->name,
                        //         'harga'         => number_format($dts->price),
                        //         'harga_asli'    => $dts->price
                        //     ]);
                        // }
                    }
                }
                $result[] = ([
                    'id_kategori'    => $dt->id,
                    'nama_kategori'  => $dt->name,
                    'makanan'        => $makanan
                ]);
            }
            $list_menu = MenuPlanDetail::where('menu_plan_id', $menu->id)->get();
            $list_menu_result = "";
            foreach($list_menu as $dt)
            {
                $list_menu_result .= $dt->menu_id . '|' . Menu::find($dt->menu_id)->price . '|' . 
                                     Menu::find($dt->menu_id)->menu_category_id . '|' . $dt->status . ';';
            }
            return view('menu_plan.ubah', compact('menu', 'list_menu_result', 'list_kategori', 'result'));
        } else return redirect()->back();
        
    }

    public function ubah(request $request)
    {                
        $menu_plan = MenuPlan::find($request->id);
        if($menu_plan)
        {
            $menu_plan->date    = $request->date;
            $menu_plan->name    = $request->name;
            $menu_plan->save();
            $mpd = MenuPlanDetail::where('menu_plan_id', $menu_plan->id)->get();
            foreach($mpd as $dt)
            { $dt->delete(); }

            $lm = $request->list_menu;
            $lms = explode(',', $lm);
            for ($i = 0; $i < count($lms) - 1; $i++) {
                if($lms[$i] != "")
                {
                    $id = explode('|', $request->get('menu' . $lms[$i]))[0];                        
                    $menu = Menu::find($id);
                    if ($menu) {
                        $menu_plan_detail                        = new MenuPlanDetail();
                        $menu_plan_detail->menu_plan_id          = $menu_plan->id;
                        $menu_plan_detail->status                = $request->get('status' . $lms[$i]);
                        $menu_plan_detail->menu_id               = $id;
                        $menu_plan_detail->save();
                    }
                }
            }

            Session::flash('success', 'Berhasil Mengubah Rencana Menu Baru');
            return redirect('/menu_plan');
            
        } else return redirect()->back();
    }

    public function delete(request $request)
    {

        $menu_plan = MenuPlan::find($request->id);
        // $old_menu_plan_detail = MenuPlanDetail::where('menu_plan_id', $menu_plan->id)->delete();
        $menu_plan->delete();

    }

    public function list_menu(request $request)
    {
        $menu = Menu::get();
        $result = [];
        foreach($menu as $dt)
        {
            $result[] = (
                [
                    'id'        => $dt->id,
                    'nama'      => $dt->name,
                    'price'     => $dt->price,
                    'price_f'   => number_format($dt->price)
                ]
            );
        }
        return response()->json(['result' => $result]);
    }    
}
