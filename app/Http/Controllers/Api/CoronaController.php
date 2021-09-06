<?php

namespace App\Http\Controllers\Api;

use App\Events\GetUsersContacts;
use App\Events\SendNotifecation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CoronaController extends Controller
{
    public function changeStatus(Request $request){
        $request->validate([
            'status'=>'required|string|in:not_infected,infected'
        ]);
        $user=Auth::guard('sanctum')->user();
        $status=$request->post('status');
//        if ($status==$user->status)
//            return;
        $user->status=$status;
        $user->save();
          if ($status=='infected')
              event(new GetUsersContacts($user));
        return Response::json([
            'message'=>'status in updated'
        ],200);

    }

}
