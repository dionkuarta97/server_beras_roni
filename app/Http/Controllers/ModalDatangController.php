<?php

namespace App\Http\Controllers;

use App\Models\ModalDatang;
use App\Models\RiwayatModalDatang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ModalDatangController extends Controller
{
    //

    public function createModalDatang(Request $request)
    {
        try {
            //code...
            $validation = Validator::make($request->all(), [
                'keterangan' => 'required',
                'harga' => 'required',
                'idModal' => 'required',
            ], [
                'keterangan.required' => "keterangan tidak boleh kosong",
                'harga.required' => 'harga tidak boleh kosong',

            ]);
            $user = $request->get('user');
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $modalDatang = ModalDatang::create([
                'keterangan' => $request->keterangan,
                'harga' => $request->harga,
                'idModal' => $request->idModal,
                'nama_pembuat' => $user['nama']
            ]);
            return response()->json($modalDatang, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getModalDatang(Request $request)
    {
        try {
            //code...
            $idModal = $request->route('idModal');
            $status = $request->query('status');
            $where = [['idModal', '=', $idModal]];

            if ($status) $where = [...$where, ['status', '=', $status]];

            $modalDatang = ModalDatang::with('riwayat_modal_datang')->where($where)->get();

            return response()->json($modalDatang, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function updateModalDatang(Request $request)
    {
        try {
            //code...
            $id = $request->route('id');
            $modalDatang = ModalDatang::find($id);
            $user = $request->get('user');
            $validation = Validator::make($request->all(), [
                'keterangan' => 'required',
                'harga' => 'required',
                'idModal' => 'required',
            ], [
                'keterangan.required' => "keterangan tidak boleh kosong",
                'harga.required' => 'harga tidak boleh kosong',

            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $checkUpdate = 0;
            if ($modalDatang['keterangan'] != $request->keterangan) $checkUpdate += 1;
            if ($modalDatang['harga'] != $request->berat) $checkUpdate += 1;

            if ($checkUpdate > 0) {
                DB::beginTransaction();
                try {

                    $modalDatang->update([
                        'keterangan' => $request->keterangan,
                        'harga' => $request->harga,
                        'idModal' => $request->idCategory,
                        'nama_pembuat' => $user['nama'],
                        'status' => $request->status

                    ]);
                    $riwayatModal = RiwayatModalDatang::create([
                        'keterangan' => $modalDatang['keterangan'],
                        'harga' => $modalDatang['harga'],
                        'idModalDatang' => $modalDatang['id'],
                        'nama_pembuat' => $modalDatang['nama_pembuat'],
                        'status' => $modalDatang['status'],
                        'nama_pengubah' => $user['nama'],

                    ]);
                    DB::commit();
                    return response()->json(['message' => 'data berhasil di ubah'], 200);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return response()->json($th, 500);
                }
            } else {
                return response()->json(['message' => 'tidak ada perubahan data'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
