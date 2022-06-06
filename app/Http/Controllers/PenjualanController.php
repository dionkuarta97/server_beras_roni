<?php

namespace App\Http\Controllers;

use App\Models\BerasKelola;
use App\Models\ModalPenjualan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    //

    public function createPenjualanCampuran(Request $request)
    {
        try {
            $user = $request->get('user');
            $validation = Validator::make($request->all(), [
                'keterangan' => 'required',
                'bobot' => 'required',
                'harga_jual' => 'required',
                'jenis_pembayaran' => 'required'
            ], [
                'keterangan.required' => 'keterangan tidak boleh kosong',
                'bobot.required' => 'berat tidak boleh kosong',
                'harga_jual.required' => 'harga jual tidak boleh kosong',
                'jenis_pembayaran.required' => 'jenis pembayaran tidak boleh kosong'
            ]);

            if ($validation->fails()) return response()->json($validation->errors(), 400);


            $penjualan = Penjualan::create([
                'idBerasKelola' => $request->idBerasKelola,
                'idModal' => $request->idModal,
                'idKategori' => $request->idKategori,
                'keterangan' => $request->keterangan,
                'bobot' => $request->bobot,
                'harga_modal' => $request->harga_modal,
                'harga_jual' => $request->harga_jual,
                'tipe' => $request->tipe == "biasa" ? null : "campuran",
                'jenis_pembayaran' => $request->jenis_pembayaran,
                'nama_pembuat' => $user['nama'],
                'nama_pembeli' => $request->nama_pembeli
            ]);
            return response()->json($penjualan, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function detailPenjualanCampuran(Request $request)
    {
        try {
            $id = $request->route('id');
            $tipe = $request->query('tipe');
            $berasKelola = [];
            $penjualan = Penjualan::with('modal_penjualan')->where('id', '=', $id)->first();
            if ($tipe === 'campuran') {
                $berasKelola = BerasKelola::with(['campuran.modal_campuran', 'modal_kelola'])->where('id', '=', $penjualan['idBerasKelola'])->first();
            }


            return response()->json(['penjualan' => $penjualan, 'beras_kelola' => $berasKelola], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function createModalPenjualan(Request $request)
    {
        try {
            //code...
            $validation = Validator::make($request->all(), [
                'keterangan' => 'required',
                'harga' => 'required',
            ], [
                'keterangan.required' => "keterangan tidak boleh kosong",
                'harga.required' => 'harga tidak boleh kosong',

            ]);
            $user = $request->get('user');
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $modalPenjualan = ModalPenjualan::create([
                'keterangan' => $request->keterangan,
                'harga' => $request->harga,
                'idPenjualan' => $request->idPenjualan,
                'nama_pembuat' => $user['nama']
            ]);
            return response()->json($modalPenjualan, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getAllPenjualan(Request $request)
    {
        try {
            $from = $request->query('from');
            if ($from) $from .= " 00:00:00";
            $to = $request->query('to');
            if ($to) $to .= " 23:59:59";
            $status = $request->status;
            $tipe = $request->tipe;
            $limit = $request->limit ? $request->limit : 10;
            $where = [];
            if ($from && $to) $where = [...$where, ['created_at', ">=", date($from)], ['created_at', "<=", date($to)]];
            if ($status) $where = [...$where, ['status', '=', $status]];
            if ($tipe == 'campuran') {
                $where = [...$where, ['tipe', '=', $tipe]];
            } else if ($tipe == 'biasa') {
                $where = [...$where, ['tipe', '=', null]];
            };

            $modal = Penjualan::with(['modal_penjualan', 'category'])
                ->where($where)
                ->orderBy('id', 'DESC')
                ->paginate($limit);


            return response()->json($modal, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function updatePenjualan(Request $request)
    {
        try {
            $user = $request->get('user');
            $id = $request->route('id');
            $penjualan = Penjualan::find($id);
            $beras = $request->beras_kelola;
            $jual = $request->penjualan;
            $keuntungan = $request->keuntungan;
            $berasKelola = BerasKelola::find($jual['idBerasKelola']);
            if ($request->status == 'gagal') {
                $penjualan->update([
                    'status' => $request->status
                ]);
            } else {
                DB::beginTransaction();
                try {
                    $penjualan->update([
                        'status' => $request->status
                    ]);
                    $berasKelola->update([
                        'stock' => $berasKelola['stock'] - $jual['bobot']
                    ]);
                    if ($jual['tipe'] == 'campuran') {
                        $tempPer = 0;
                        foreach ($beras['campuran'] as $key) {
                            $tempPer += $key['perbandingan'];
                        }
                        $addBobot = $jual['bobot'] / $tempPer;
                        $keuntungan = $keuntungan / $jual['bobot'];
                        foreach ($beras['campuran'] as $key) {
                            $tempModal = 0;
                            foreach ($key['modal_campuran'] as $value) {
                                $tempModal += $value['harga'] * $value['berat'];
                            }
                            $addPenjualan = Penjualan::create([
                                'idBerasKelola' => $key['idBerasCampur'],
                                'idModal' => $key['idModal'],
                                'idKategori' => $key['idKategori'],
                                'keterangan' => 'dari beras campuran',
                                'bobot' => $addBobot * $key['perbandingan'],
                                'harga_modal' => $key['harga'] + ($tempModal / $key['berat']),
                                'harga_jual' => $key['harga'] + ($tempModal / $key['berat']) + $keuntungan,
                                'jenis_pembayaran' => $jual['jenis_pembayaran'],
                                'nama_pembuat' => $user['nama'],
                                'nama_pembeli' => $jual['nama_pembeli'],
                                'status' => 'success'
                            ]);
                        }
                    }
                    DB::commit();
                    return response()->json(['message' => 'data berhasil'], 200);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['message' => $e->getMessage(), 500]);
                }
            }
            return response()->json(['message' => 'data berhasil'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
