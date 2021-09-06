<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AccessTokensController extends Controller
{
    public function store(Request $request){
        // dd($request->all());
        $request->validate([
            'phone'=>'required|numeric',
            'password'=>['required'],
            'device_name'=>['nullable']

        ]);
        $user=User::where('phone',$request->phone)->first();

        if (!$user||($request->password==$user->password)){
            return Response::json([
                'message'=>'Invalid phone and password'
            ],401);
        }


        $token=  $user->createToken($request->device_name??'any device');
        return Response::json([
            'token'=>$token->plainTextToken,
            'user'=>$user
        ]);
    }
    public function getUser(Request $request){
        $user=Auth::guard('sanctum')->user();
        return $user;
    }
    public function destroy(){
        $user=Auth::guard('sanctum')->user();

        $user->currentAccessToken()->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Logout success'
        ]);
    }
    public function register(Request $request){

        $this->validate($request, [
            'name' => 'required|min:3',
            'phone' => 'required|numeric|unique:users,phone',
            'password' => 'required|min:6',
        ]);

        if ($request->hasFile('url')) {
            $file = $request->file('url');

            $image_path = $file->store('/users', [
                'disk' => 'uploads'
            ]);

            $request->merge([
                'image' => $image_path,
            ]);
        }
        $user = User::create([
            'name' => $request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password' =>$request->password
        ]);

        if (is_null($user)){
            $message=[
                'status'=>404,
                'message'=>"User login attempt failed",

            ];
            return response()->json([
                'status'=>422,
                'message'=>'Something went wrong'
            ]);
        }
        Auth::login($user);
        $token = $user->createToken($request->device_name??'any device');
        return Response::json([
            'token'=>$token->plainTextToken,
            'user'=>$user
        ],201);
    }

    public function editUser(Request $request){
        $request->validate([
            'new_password'=>'string|min:6',
            'name'=>'string',
            'phone'=>'numeric'
        ]);
        $user=$request->user();
        $request->merge([
            'password'=> \Illuminate\Support\Facades\Hash::make($request->password)
        ]);
        $user->update($request->only(['name','password','phone']));
        $user->save();
        return Response::json([
            'user'=>$user,
            'message'=>'edit user success'
        ],200);
    }
}
