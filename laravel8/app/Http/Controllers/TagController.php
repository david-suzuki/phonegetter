<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function list()
    {
        $tags = Tag::all();

        $records = [];

        foreach ($tags as $tag) {
            $record = [];

            $record['id'] = $tag->id;
            $record['tname'] = $tag->tname;

            $records[] = $record;
        }

        return response()->json($records);
    }

    public function create(Request $request)
    {
        $tname = $request->tname;

        $tag = new Tag;
        $tag->tname = $tname;

        $tag->save();

        return response()->json('success');
    }

    public function fetch($id) 
    {
        $tag = Tag::find($id);

        $row = [];
        $row['tname'] = $tag->tname;     

        return response()->json($row);     
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $tag = $request->tag;

        $tname = $tag['tname'];

        $raw = Tag::find($id);
        $raw->tname = $tname;

        $raw->save();

        return response()->json('success');
    }

    public function delete($id)
    {
        $tag = Tag::find($id);

        try {
            $tag->delete();
        } catch(\Illuminate\Database\QueryException $ex) { 
            return response()->json($ex->getMessage()); 
        }

        return response()->json('success');
    }
}
