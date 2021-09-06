<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UserLocationController extends Controller
{


    public function newLocation(Request $request){
        $request->validate([
            'latitude'=>'required|numeric',
            'longitude'=>'required|numeric',
        ]);
        $user=Auth::guard('sanctum')->user();

    $location=UserLocation::create([
            'latitude'=>$request->post('latitude'),
            'longitude'=>$request->post('longitude'),
            'user_id'=>$user->id
        ]);


        return Response::json([
            'message'=>'save new location'
        ],200);
    }



}
