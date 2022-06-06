<?php

namespace App\Http\Controllers;

use App\Models\Modal;
use App\Models\RiwayatModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ModalController extends Controller
{
    //

    public function createModal(Request $request)
    {
        try {
            //code...

            $validation = Validator::make($request->all(), [
                'keterangan' => 'required',
                'berat' => 'required',
                'harga' => 'required',
                'idCategory' => 'required',

            ], [
                'keterangan.required' => 'keterangan tidak boleh kosong',
                'berat.required' => 'berat tidak boleh kosong',
                'harga.required' => 'harga tidak boleh kosong',
                'idCategory.required' => 'Kategori tidak boleh kosong',

            ]);

            $user = $request->get('user');
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $modal = Modal::create([
                'keterangan' => $request->keterangan,
                'berat' => $request->berat,
                'harga' => $request->harga,
                'idCategory' => $request->idCategory,
                'idUser' => $user['id'],
                'nama_pembuat' => $user['nama'],
                'stock' => $request->berat
            ]);

            return response()->json($modal, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function updateModal(Request $request)
    {
        try {
            //code...
            $tipe = $request->query('tipe');
            $id = $request->route('id');
            $modal = Modal::find($id);
            $user = $request->get('user');
            $stock = $request->stock;
            if (!$modal) return response()->json(['message' => 'data tidak di temukan'], 404);
            if ($tipe === "I") {
                if ($modal['berat'] !== $modal['stock'] && $modal['berat'] !== $request->berat) {
                    return response()->json(['message' => 'berat tidak bisa diubah karna stock sudah berkurang'], 400);
                }
                if ($modal['berat'] === $modal['stock']) {
                    $stock = $request->berat;
                }
            }
            $validation = Validator::make($request->all(), [
                'keterangan' => 'required',
                'berat' => 'required',
                'harga' => 'required',
                'idCategory' => 'required',
                'stock' => 'required'
            ], [
                'keterangan.required' => 'keterangan tidak boleh kosong',
                'berat.required' => 'berat tidak boleh kosong',
                'harga.required' => 'harga tidak boleh kosong',
                'idCategory.required' => 'Kategori tidak boleh kosong',
                'stock.required' => 'stock tidak boleh kosong',
            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $checkUpdate = 0;
            if ($modal['keterangan'] != $request->keterangan) $checkUpdate += 1;
            if ($modal['berat'] != $request->berat) $checkUpdate += 1;
            if ($modal['harga'] != $request->harga) $checkUpdate += 1;
            if ($modal['idCategory'] != $request->idCategory) $checkUpdate += 1;
            if ($checkUpdate > 0) {
                DB::beginTransaction();
                try {

                    $modal->update([
                        'keterangan' => $request->keterangan,
                        'berat' => $request->berat,
                        'harga' => $request->harga,
                        'idCategory' => $request->idCategory,
                        'idUser' => $user['id'],
                        'nama_pembuat' => $user['nama'],
                        'stock' => $stock,
                        'status' => $request->status

                    ]);
                    $riwayatModal = RiwayatModal::create([
                        'idModal' => $modal['id'],
                        'keterangan' => $modal['keterangan'],
                        'berat' => $modal['berat'],
                        'harga' => $modal['harga'],
                        'idCategory' => $modal['idCategory'],
                        'idUser' => $modal['idUser'],
                        'nama_pembuat' => $modal['nama_pembuat'],
                        'stock' => $modal['stock'],
                        'status' => $modal['status'],
                        'tipe_ubah' => $tipe

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

    public function getAllModal(Request $request)
    {
        try {
            //code...
            $from = $request->query('from');
            if ($from) $from .= " 00:00:00";
            $to = $request->query('to');
            if ($to) $to .= " 23:59:59";
            $stock = $request->stock;
            $status = $request->status;
            $idCategory = $request->category;
            $limit = $request->limit ? $request->limit : 10;
            $where = [];
            if ($from && $to) $where = [...$where, ['created_at', ">=", date($from)], ['created_at', "<=", date($to)]];
            if ($idCategory) $where = [...$where, ['idCategory', "=", $idCategory]];
            if ($stock) {
                if ($stock == "habis") {
                    $where = [...$where, ['stock', '=', 0]];
                } else if ($stock == "ada") {
                    $where = [...$where, ['stock', ">", 0]];
                }
            }
            if ($status) $where = [...$where, ['status', '=', $status]];
            $modal = Modal::with(['category', 'riwayat_modal',])
                ->where($where)
                ->orderBy('id', 'DESC')
                ->paginate($limit);


            return response()->json($modal, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getModalSelect(Request $request)
    {
        try {
            $idCategory = $request->route('idCategory');
            $modal = Modal::where('idCategory', '=', $idCategory)->orderBy('id', 'DESC')->get();
            return response()->json($modal, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    public function deleteModal(Request $request)
    {
        try {
            //code...
            $id = $request->route('id');
            $modal = Modal::find($id);
            if (!$modal) return response()->json(['message' => 'data tidak di temukan'], 404);
            $modal->delete();
            return response()->json(['message' => 'data berhasil di hapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getDetailModal(Request $request)
    {
        try {
            $id = $request->route('id');
            $modal = Modal::with(['category', 'riwayat_modal', 'penjualan.modal_penjualan'])->find($id);
            return response()->json($modal, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
