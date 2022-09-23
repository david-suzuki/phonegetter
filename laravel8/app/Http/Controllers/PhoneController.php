<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PhoneImport;
use App\Models\Phone;
use App\Models\Tag;
use App\Models\PhoneHistory;

class PhoneController extends Controller
{
    public function import(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new PhoneImport, $request->file('file'));    
        } catch(\Illuminate\Database\QueryException $ex) { 
            return response()->json($ex->getMessage()); 
        }
        
        return response()->json('success');
    }

    public function list()
    {
        $phones = Phone::all();

        $records = [];

        foreach ($phones as $phone) {
            $record = [];

            $record['id'] = $phone->id;
            $record['pnumber'] = $phone->pnumber;
            $record['cname'] = $phone->cname;
            $record['status'] = $phone->status;

            $records[] = $record;
        }

        return response()->json($records);
    }

    public function fetch($id) 
    {
        $phone = Phone::find($id);

        $row = [];
        $row['pnumber'] = $phone->pnumber;     
        $row['cname'] = $phone->cname;     
        $row['status'] = $phone->status;

        return response()->json($row);     
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $phone = $request->phone;

        $pnumber = $phone['pnumber'];
        $cname = $phone['cname'];
        $status = $phone['status'];

        $raw = Phone::find($id);
        $raw->pnumber = $pnumber;
        $raw->cname = $cname;
        $raw->status = $status;

        $raw->save();

        return response()->json('success');
    }

    public function delete($id)
    {
        $phone = Phone::find($id);
        $phone->delete();

        return response()->json('success');
    }
}
