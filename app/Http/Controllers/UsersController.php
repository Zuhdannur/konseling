<?php namespace App\Http\Controllers;

use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller {

    public function login(Request $request){
       $user = $this->checking($request->username);
       if($user){
           if(Hash::check($request->password,$user->password)){
               $apiKey = base64_encode(str_random(40));
               \App\User::where('username',$request->username)->update([
                   'api_token' => $apiKey
               ]);
               return [
                 "message" => 'success',
                 "api_token" => $apiKey
               ];
           }
       } else {
           return [
               'message'=>'Username Not Found'
           ];
       }
    }

    public function register(Request $request){

        if(!$this->checking($request->username)){
            $insert = new \App\User;
            $insert->name = $request->name;
            $insert->username = $request->username;
            $insert->password = Hash::make($request->password);
            $insert->role = $request->role;
            $insert->save();
            return [
              'message' => 'register successfully'
            ];
        } else {
            return [
                'message' => 'Duplicate Username'
            ];
        }
    }

    public function checking($username){
        $check = \App\User::where('username',$username)->first();
        if($check){ return $check;}
        else return null;
    }
}
