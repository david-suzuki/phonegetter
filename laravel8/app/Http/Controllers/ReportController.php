<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PhoneHistory;
use App\Models\Tag;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

use DB;

class ReportController extends Controller
{
    public function list()
    {
        $histories = PhoneHistory::all();

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

        $result = [];
        $result['history'] = $records;

        $tags = Tag::all();
        $result['tag'] = $tags;                

        return response()->json($result);    
    }

    public function search(Request $request)
    {
        $startDateTime = $request->start;
        $endDateTime = $request->end;
        $tag = $request->tag;
        $status = $request->status;

        if ($startDateTime != "")
            $sdt = $this->convert($startDateTime);
        else
            $sdt = "";
        
        // date_default_timezone_set("Asia/Hong_Kong");
        if ($endDateTime == "")
            $edt = date("Y-m-d H:i:s");
        else
            $edt = $this->convert($endDateTime);    

        $query = "SELECT l.id, l.created_at, r1.pnumber, r1.cname, r1.`status`, r2.tname FROM phone_histories l LEFT JOIN phones r1 ON l.phone_id=r1.id LEFT JOIN tags r2 ON l.tag_id=r2.id where l.created_at BETWEEN '$sdt' AND '$edt'";
        
        if ($tag != "")
        {
            $tagId = intval($tag);
            $query .= " AND l.tag_id=$tagId";
        }

        if ($status != "")
            $query .= " AND r1.`status`='$status'";

        // return response()->json($query);

        $histories = DB::select($query);

        $records = [];
        foreach ($histories as $history) {
            $record = [];
            $record['id'] = $history->id;
            $record['pnumber'] = $history->pnumber;
            $record['cname'] = $history->cname;
            $record['status'] = $history->status;
            $record['tname'] = $history->tname;
            $record['created'] = $history->created_at;

            $records[] = $record;
        }

        return response()->json($records);
    }

    public function excel(Request $request)
    {
        $startDateTime = $request->start;
        $endDateTime = $request->end;
        $tag = $request->tag;
        $status = $request->status;

        if ($startDateTime != "")
            $sdt = $this->convert($startDateTime);
        else
            $sdt = "";
        
        // date_default_timezone_set("Asia/Hong_Kong");
        if ($endDateTime == "")
            $edt = date("Y-m-d H:i:s");
        else
            $edt = $this->convert($endDateTime);    

        $query = "SELECT l.id, l.created_at, r1.pnumber, r1.cname, r1.`status`, r2.tname FROM phone_histories l LEFT JOIN phones r1 ON l.phone_id=r1.id LEFT JOIN tags r2 ON l.tag_id=r2.id where l.created_at BETWEEN '$sdt' AND '$edt'";
        
        if ($tag != "")
        {
            $tagId = intval($tag);
            $query .= " AND l.tag_id=$tagId";
        }

        if ($status != "")
            $query .= " AND r1.`status`='$status'";

        $histories = DB::select($query);

        $records = [];

        $firstRow = [];
        $firstRow['no'] = "No";
        $firstRow['id'] = "Id";
        $firstRow['pnumber'] = "Phone";
        $firstRow['cname'] = "Client";
        $firstRow['status'] = "Status";
        $firstRow['tname'] = "Tag";
        $firstRow['created'] = "Created_at";

        $records[] = $firstRow;

        $i = 1;
        foreach ($histories as $history) {
            $record = [];

            $record['no'] = $i;
            $record['id'] = $history->id;
            $record['pnumber'] = $history->pnumber;
            $record['cname'] = $history->cname;
            $record['status'] = $history->status;
            $record['tname'] = $history->tname;
            $record['created'] = $history->created_at;

            $records[] = $record;
            $i++;
        }

        $export = new ReportExport($records);

        return Excel::download($export, 'report.xlsx');
    }

    private function convert($str)
    {
        $mnths = [
            'Jan'=>"01",
            'Feb'=>"02",
            'Mar'=>"03",
            'Apr'=>"04",
            'May'=>"05",
            'Jun'=>"06",
            'Jul'=>"07",
            'Aug'=>"08",
            'Sep'=>"09",
            'Oct'=>"10",
            'Nov'=>"11",
            'Dec'=>"12"
        ];
        $items = explode(" ", $str);

        $date = implode("-", [$items[3], $mnths[$items[1]], $items[2]]);
        $time = $items[4];

        return $date . " " . $time;
    }
}
