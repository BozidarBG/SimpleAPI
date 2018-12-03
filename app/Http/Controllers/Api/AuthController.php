<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function register(Request $request){
        \Log::info($request->all());
        $this->validate($request, [
            'email'=>'required|unique:users',
            'name'=>'required',
            'password'=>'required|min:6'
        ]);

        $user=User::firstOrCreate([
            'email'=>$request->email,
            'name'=>$request->name,
            'password'=>Hash::make($request->password)
        ]);

        $http=new \GuzzleHttp\Client();

        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '2',
                'client_secret' => 'h88aAceTVBHyPyeWmQLeAM08uPXpY6eK1IXZVax6',
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ],
        ]);

        return response(['auth'=>json_decode((string) $response->getBody(), true), 'user'=>$user]);
    }

    public function login(Request $request){
        $this->validate($request, [
            'email'=>'required',
            'password'=>'required'
        ]);

        $user=User::where('email', $request->email)->first();
        if(!$user){
            return response(['status'=>'error', 'message'=>'User not found!']);
        }

        if(Hash::check($request->password, $user->password)){
            $http=new \GuzzleHttp\Client();

            $response = $http->post(url('oauth/token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => '2',
                    'client_secret' => 'h88aAceTVBHyPyeWmQLeAM08uPXpY6eK1IXZVax6',
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]);

            return response(['auth'=>json_decode((string) $response->getBody(), true), 'user'=>$user]);
        }
    }
}
