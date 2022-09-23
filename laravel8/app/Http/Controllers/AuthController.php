<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;

use App\Models\User;
use App\Models\LoginNumber;

use Helper;

class AuthController extends Controller
{
    // use ApiResponser;

    public $token;

    public function login(Request $request)
    {
        $attr = $request->validate([
            'account' => 'required|string',
            'password' => 'required|string|min:3'
        ]);

        $user = [];

        if (!Auth::attempt($attr)) {
            return response()->json($user);
        }


        $this->token = auth()->user()->createToken('API Token')->plainTextToken;

        $user['id'] = Auth::user()->id;
        $user['uname'] = Auth::user()->uname;
        $user['account'] = Auth::user()->account;
        $user['role'] = Auth::user()->role;
        $user['pickable'] = Auth::user()->pickable;
        $user['token'] = $this->token;

        // handle the users's number of login
        $cdate = Helper::getDate();
        $loginNumber = LoginNumber::where('user_id', $user['id'])->where('login_date', $cdate)->first();
        if (is_null($loginNumber))
        {
            $loginNumber = new LoginNumber;
            $loginNumber->user_id = Auth::user()->id;
            $loginNumber->login_number = 1;
            $loginNumber->login_date = $cdate;

            $loginNumber->save();
        }
        else
        {
            $loginNumber->login_number = $loginNumber->login_number + 1;
            $loginNumber->save();
        }

        // removing the record 2 days ago
        $days_ago = date('Y-m-d', strtotime('-2 days', strtotime($cdate)));
        $loginNumber = LoginNumber::where('user_id', $user['id'])->where('login_date', $days_ago)->first();
        if (!is_null($loginNumber))
            $loginNumber->delete();

        return response()->json($user);
    }

    public function verify()
    {
        $user = [];

        // $token = auth()->user()->createToken('API Token')->plainTextToken;

        $user['id'] = Auth::user()->id;
        $user['uname'] = Auth::user()->uname;
        $user['account'] = Auth::user()->account;
        $user['role'] = Auth::user()->role;
        $user['pickable'] = Auth::user()->pickable;
        $user['token'] = $this->token;

        return response()->json($user);
    }
}
