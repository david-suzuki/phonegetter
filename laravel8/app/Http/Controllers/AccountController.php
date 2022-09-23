<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginNumber;
use App\Models\PhoneHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Helper;

class AccountController extends Controller
{
    public function setAccount(Request $request)
    {   
        $uid = $request->uid;
        $uname = $request->uname;
        $account= $request->account;
        $role = $request->role;

        $user = User::find($uid);
        $user->uname = $uname;
        $user->account = $account;
        $user->role = $role;

        $user->save();

        $user = [];

        $user['id'] = Auth::user()->id;
        $user['uname'] = Auth::user()->uname;
        $user['account'] = Auth::user()->account;
        $user['role'] = Auth::user()->role;
        $user['token'] = '';

        return response()->json($user);
    }

    public function setPassword(Request $request)
    {
        $uid = $request->uid;
        $currentPassword = $request->curpass;
        $newPassword = $request->newpass;

        $user = User::find($uid);
        if (Hash::check($currentPassword, $user->password))
        {
            $user->password = Hash::make($newPassword);
            $user->save();
            return response()->json("success");
        } else {
            return response()->json("current_pass_error");
        }
        // return
        return response()->json("fail");
    }

    public function getDashboardData()
    {
        $result = [];

        $user_id = Auth::user()->id;
        $cdate = Helper::getDate();
        $days_ago = date('Y-m-d', strtotime('-1 days', strtotime($cdate)));

        $loginNumber = LoginNumber::where('user_id', $user_id)->where('login_date', $days_ago)->first();
        
        $result['login_number'] = 0;        
        if (!is_null($loginNumber))
            $result['login_number'] = $loginNumber->login_number;

        $historyNumber = PhoneHistory::where('user_id', $user_id)->where('created_at', 'like', "$days_ago%")->count();

        $result['history_number'] = $historyNumber;  

        return response()->json($result);        
    }

    public function getChartData()
    {
        $result = [];

        $user_id = Auth::user()->id;
        $cdate = Helper::getDate();

        for($i = -7; $i < 0; $i++)
        {
            $days_ago = date('Y-m-d', strtotime("$i days", strtotime($cdate)));
            $historyNumber = PhoneHistory::where('user_id', $user_id)->where('created_at', 'like', "$days_ago%")->count();

            $result[] = $historyNumber;
        }

        return response()->json($result);
    }
}
