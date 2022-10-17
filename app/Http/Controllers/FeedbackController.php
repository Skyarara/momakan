<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Feedback;
use App\Order;
use App\OrderDetail;
use App\Employee;
use App\http\Requests;

class FeedbackController extends Controller
{
    public function index(){

        $feedback = Feedback::with('employee')->paginate(10);
        // $orders = [];

        // foreach($order as $dt)
        // {
        //     $orders[] = (
        //         [
        //             $employee_order => $dt->employee_id
        //         ]

        //         );
        // }
        return view('feedback.index',compact('feedback'));
    }
}
