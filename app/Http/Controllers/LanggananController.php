<?php

namespace App\Http\Controllers;

use App\Models\Langganan;
use Illuminate\Http\Request;

class LanggananController extends Controller
{
    //
    public function getAllLangganan(Request $request)
    {
        try {
            //code...
            $nama = $request->query('nama');
            $limit = $request->limit ? $request->limit : 10;
            $where = [];
            if ($nama) $where = [...$where, ['nama', 'like', '%' . $nama . '%']];
            $langganan = Langganan::where($where)->orderBy('id', 'DESC')->paginate($limit);
            return response()->json($langganan, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
