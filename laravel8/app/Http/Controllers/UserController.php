<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PhoneHistory;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function list()
    {
        $users = User::where('role', 'user')->get();

        $records = [];
        foreach($users as $user)
        {
            $record = [];
            $record['id'] = $user->id;
            $record['name'] = $user->uname;
            $record['account'] = $user->account;
            $record['status'] = $user->status;
            $record['pickable'] = "-";
            if ($user->pickable > -1)
                $record['pickable'] = $user->pickable;

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
        $user->role = 'user';
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

        $row = [];
        $row['name'] = $user->uname;     
        $row['account'] = $user->account;     
        $row['status'] = $user->status;
        $row['pickable'] = $user->pickable;

        return response()->json($row);     
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $user = $request->user;

        $uname = $user['name'];
        $account = $user['account'];
        $status = $user['status'];
        $password = $user['password'];
        $pickable = $user['pickable'];

        $raw = User::find($id);
        $raw->uname = $uname;
        $raw->account = $account;
        $raw->status = $status;
        $raw->password = Hash::make($password);
        $raw->pickable = $pickable;

        $raw->save();

        return response()->json('success');
    }

    public function delete($id)
    {
        // before deleting, checking the foreign key
        $histories = PhoneHistory::where('user_id', $id)->get();
        foreach ($histories as $history) {
            $history->delete();
        }

        $user = User::find($id);
        $user->delete();

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
