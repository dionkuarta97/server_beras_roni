<?php

namespace App\Http\Controllers;

use App\Models\ModalCampuran;
use App\Models\ModalKelola;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ModalKelolaController extends Controller
{
    //
    public function createModalKelola(Request $request)
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
            $modalDatang = ModalKelola::create([
                'keterangan' => $request->keterangan,
                'harga' => $request->harga,
                'idBerasKelola' => $request->idBerasKelola,
                'nama_pembuat' => $user['nama']
            ]);
            return response()->json($modalDatang, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function createModalKelolaCampuran(Request $request)
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
            $campuran = $request->campuran;
            $berat = $request->berat;
            $user = $request->get('user');
            if ($validation->fails()) return response()->json($validation->errors(), 400);

            DB::beginTransaction();
            try {
                $modalDatang = ModalKelola::create([
                    'keterangan' => $request->keterangan,
                    'harga' => $request->harga,
                    'idBerasKelola' => $request->idBerasKelola,
                    'nama_pembuat' => $user['nama']
                ]);
                $temp = 0;
                foreach ($campuran as $key) {
                    $temp += $key['perbandingan'];
                }

                foreach ($campuran as $key) {
                    $hargaModal = $request->harga / $temp;
                    $modalCampuran = ModalCampuran::create([
                        'idBerasKelola' => $key['idBerasCampur'],
                        'idModalKelola' => $modalDatang['id'],
                        'idCampuran' => $key['id'],
                        'harga' => $hargaModal * $key['perbandingan'],
                        'berat' => $berat,
                        'perbandingan' => $key['perbandingan'],
                        'nama_pembuat' => $user['nama']
                    ]);
                }

                DB::commit();
                return response()->json(['message' => 'data berhasil di tambahkan'], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json($th, 500);
            }

            return response()->json($modalDatang, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
