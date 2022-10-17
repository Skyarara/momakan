<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Feedback;
use Validator;
use App\Employee;

class FeedbackController extends Controller
{
    public function add(Request $request)
    {
        $rules = array(
            'rating' => 'required|numeric',
            'order_id' => 'required|numeric',
            'description' => 'required',
        );
        $messages = [
            'rating.required' => 'rating Kosong',
            'rating.numeric' => 'rating harus angka',
            'order_id.required' => 'id Order Kosong',
            'order_id.numeric' => 'id Order harus angka',
            'description.required' => 'deskripsi kosong',
        ];
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()], 401);
        }
        $user = auth()->user()->id;
        $employee = Employee::where('user_id', $user)->value('id');

        $feedback = new Feedback();
        $feedback->rating = $request->rating;
        $feedback->order_id = $request->order_id;
        $feedback->description = $request->description;
        $feedback->employee_id = $employee;
        $feedback->save();

        return response()->json([
            'status' => true,
            'message' => 'Success Menambahkan Feedback',
            'data'    => [
                'id' => $feedback->id,
                'rating' => $feedback->rating,
                'deskripsi' => $feedback->description,
                'employee_id' => $feedback->employee_id,
            ],
        ]);
    }
}
