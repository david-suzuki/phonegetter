<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Phone;
use App\Models\User;
use App\Models\PhoneHistory;
use Auth;

class PickingController extends Controller
{
    public function list($amount)
    {   
        if ($amount == -1) 
            $phones = Phone::where('status', 'normal')->get();
        else if ($amount > -1)
            $phones = Phone::where('status', 'normal')->inRandomOrder()->limit($amount)->get();
        else
            return response()->json("amount_error");
        
        $rows = [];
        $phone_ids = [];

        foreach ($phones as $phone) {
            $phone_ids[] = $phone->id;

            $row = [];

            $row['id'] = $phone->id;
            $row['pnumber'] = $phone->pnumber;
            $row['cname'] = $phone->cname;

            $rows[] = $row;
        }

        // Saving the ids in current picking list
        $user = Auth::user();
        if ($amount > -1) 
        {
            $user->picking_list = json_encode($phone_ids);
            $user->save();
        }
        return response()->json($rows);
    }

    public function mark(Request $request)
    {
        $phone_id = $request->phoneId;
        $tag_id = $request->tagId;
        $user_id = $request->userId;

        $history = new PhoneHistory;
        $history->phone_id = $phone_id;
        $history->tag_id = $tag_id;
        $history->user_id = $user_id;

        $history->save();

        // change the status of phone record
        $phone = Phone::find($phone_id);
        $phone->status = "locked";
        $phone->save();

        // upgrade the user's picking list
        $user = Auth::user();
        $picking_list = json_decode($user->picking_list);

        if (($key = array_search($phone_id, $picking_list)) !== false) {
            unset($picking_list[$key]);
        }
        $values = [];
        foreach ($picking_list as $value) {
            $values[] = $value;
        }

        $user->picking_list = json_encode($values);
        $user->save();

        return response()->json('success');
    }

    public function test()
    {
        $user = User::find(7);
        $picking_list = json_decode($user->picking_list);

        if (($key = array_search(31, $picking_list)) !== false) {
            unset($picking_list[$key]);
        }

        $values = [];
        foreach ($picking_list as $value) {
            $values[] = $value;
        }

        dd(json_encode($picking_list));
    }

    public function pickinglist()
    {
        $user = Auth::user();

        $rows = [];

        $pickable = $user->pickable;
        // if ($pickable == -1)
        // {
        //     $phones = Phone::where('status', 'normal')->get();
        //     foreach ($phones as $phone) {
        //         $row = [];
        //         $row['id'] = $phone->id;
        //         $row['pnumber'] = $phone->pnumber;
        //         $row['cname'] = $phone->cname;

        //         $rows[] = $row;         
        //     }
        // }
        if ($pickable > -1)
        {
            $picks = json_decode($user->picking_list);

            if (!is_null($picks))
            {
                foreach ($picks as $pid) {
                    $phone = Phone::find($pid);

                    $row = [];
                    $row['id'] = $phone->id;
                    $row['pnumber'] = $phone->pnumber;
                    $row['cname'] = $phone->cname;

                    $rows[] = $row;
                }
            }
        }

        return response()->json($rows);
    }
}
