<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Vendor;
use App\Menu;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function menuku()
    {
        $vendor_id = Vendor::where('user_id', Auth::user()->id)->first()->id;
        $menu = Menu::where('vendor_id', $vendor_id)->get();
        return view('vendor_menuku.index', compact('menu'));
    }
}
