<?php

namespace App\Http\Controllers;

use App\Models\Campuran;
use App\Models\BerasKelola;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BerasKelolaController extends Controller
{
    //
    public function createBerasKelola(Request $request)
    {
        try {
            //code...
            $user = $request->get('user');
            $validation = Validator::make($request->all(), [
                'keterangan' => 'required',
                'berat' => 'required'
            ], [
                'keterangan.required' => 'keterangan tidak boleh kosong',
                'berat.required' => 'berat tidak boleh kosong'
            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $berasKelola = BerasKelola::create([
                'idModal' => $request->idModal,
                'keterangan' => $request->keterangan,
                'harga' => $request->harga,
                'berat' => $request->berat,
                'stock' => $request->berat,
                'tipe' => $request->tipe,
                'nama_pembuat' => $user['nama']
            ]);
            return response()->json($berasKelola, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getBerasKelola(Request $request)
    {
        try {
            //code...
            $idModal = $request->route('idModal');
            $status = $request->query('status');
            $where = [['idModal', '=', $idModal]];
            if ($status) $where = [...$where, ['status', '=', $status]];

            $berasKelola = BerasKelola::with(['modal_kelola', 'beras_campuran', 'modal_campuran', 'modal.category', 'penjualan'])->where($where)->get();

            return response()->json($berasKelola, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getBerasCampuran(Request $request)
    {
        try {
            //code...
            $status = $request->query('status');
            $where = [['tipe', '=', 'campuran']];
            $from = $request->query('from');
            $limit = $request->limit ? $request->limit : 10;
            if ($from) $from .= " 00:00:00";
            $to = $request->query('to');
            if ($to) $to .= " 23:59:59";
            if ($status) $where = [...$where, ['status', '=', $status]];
            if ($from && $to) $where = [...$where, ['created_at', ">=", date($from)], ['created_at', "<=", date($to)]];
            $berasKelola = BerasKelola::with(['campuran', 'modal_kelola', 'penjualan'])->where($where)
                ->orderBy('id', 'DESC')
                ->paginate($limit);
            return response()->json($berasKelola, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function createCampuran(Request $request)
    {
        try {
            //code...
            $validation = Validator::make($request->all(), [
                'berat' => 'required',
                'perbandingan' => 'required',
            ], [
                'berat.required' => 'berat tidak boleh kosong',
                'perbandingan.required' => 'perbandingan tidak boleh kosong'
            ]);
            $berasKelola = BerasKelola::find($request->idBerasCampur);
            $berasCampur = BerasKelola::with('campuran')->where('id', '=', $request->idBerasKelola)->first();
            $berat = $request->berat;
            $stock = $berasCampur['stock'];
            if ($stock == 0) {
                $stock = $request->berat;
            } else {
                $stock += $request->berat;
            }
            $harga = 0;
            if ($harga == 0) {
                $harga = $request->harga;
            }


            if (count($berasCampur['campuran']) > 0) {
                $harga = 0;
                foreach ($berasCampur['campuran'] as $key) {
                    $berat = $berat + $key['berat'];
                    $harga += $key['berat'] * $key['harga'];
                }
                $harga += $request->berat * $request->harga;

                $harga = $harga / $berat;
            };
            $cekCampuran = Campuran::where([['idBerasKelola', '=', $request->idBerasKelola], ['idBerasCampur', '=', $request->idBerasCampur]])->first();
            if ($cekCampuran) return response()->json(['ganda' => 'beras tersebut telah pernah di tambahkan'], 400);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            DB::beginTransaction();
            try {


                $campuran = Campuran::create([
                    'idBerasKelola' => $request->idBerasKelola,
                    'idBerasCampur' => $request->idBerasCampur,
                    'idModal' => $request->idModal,
                    'idKategori' => $request->idKategori,
                    'kategori' => $request->kategori,
                    'harga' => $request->harga,
                    'berat' => $request->berat,
                    'perbandingan' => $request->perbandingan,
                    'status' => 'active'
                ]);
                $berasKelola->update([
                    'stock' => $berasKelola['stock'] - $request->berat
                ]);
                $berasCampur->update([
                    'stock' => $stock,
                    'berat' => $berat,
                    'harga' => $harga
                ]);
                DB::commit();
                return response()->json(['message' => 'data berhasil di tambahkan'], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json($th, 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
