<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PhoneHistory;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function list()
    {
        $admins = User::where('role', 'admin')->get();

        $records = [];
        foreach($admins as $admin)
        {
            $record = [];
            $record['id'] = $admin->id;
            $record['name'] = $admin->uname;
            $record['account'] = $admin->account;
            $record['status'] = $admin->status;
            $record['pickable'] = "-";
            if ($admin->pickable > -1)
                $record['pickable'] = $admin->pickable;

            $records[] = $record;
        }

        return response()->json($records);
    }

    public function create(Request $request)
    {
        $uname = $request->uname;
        $account = $request->account;
        $status = $request->status;
        $pickable = $request->pickable;

        $user = new User;
        $user->uname = $uname;
        $user->account = $account;
        $user->role = 'admin';
        $user->status = $status;
        $user->password = Hash::make($request->password);
        if ($pickable < -1)
            $user->pickable = -1;
        else
            $user->pickable = $pickable;

        $user->save();

        return response()->json('success');
    }

    public function fetch($id) 
    {
        $user = User::find($id);

        $admin = [];
        $admin['name'] = $user->uname;     
        $admin['account'] = $user->account;     
        $admin['status'] = $user->status;
        $admin['pickable'] = $user->pickable;

        return response()->json($admin);     
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $admin = $request->admin;

        $uname = $admin['name'];
        $account = $admin['account'];
        $status = $admin['status'];
        $password = $admin['password'];
        $pickable = $admin['pickable'];

        $user = User::find($id);
        $user->uname = $uname;
        $user->account = $account;
        $user->status = $status;
        $user->password = Hash::make($password);
        $user->pickable = $pickable;

        $user->save();

        return response()->json('success');
    }

    public function delete($id)
    {
        // before deleting, checking the foreign key
        $histories = PhoneHistory::where('user_id', $id)->get();
        foreach ($histories as $history) {
            $history->delete();
        }

        $admin = User::find($id);
        $admin->delete();

        return response()->json('success');
    }

    public function histories($id)
    {
        $histories = PhoneHistory::where('user_id', $id)->get();

        $records = [];
        foreach ($histories as $history) {
            $record = [];

            $record['id'] = $history->id;
            $record['pnumber'] = $history->phone->pnumber;
            $record['cname'] = $history->phone->cname;
            $record['status'] = $history->phone->status;
            $record['tname'] = $history->tag->tname;

            $created = explode(" ", $history->created_at);
            $record['created'] = $created[0] . " " . $created[1];

            $records[] = $record;
        }

        return response()->json($records);
    }

    public function deleteHistory($id)
    {
        $history = PhoneHistory::find($id);

        $history->delete();

        return response()->json('success');
    }
}
