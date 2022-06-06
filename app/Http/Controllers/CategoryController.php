<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Modal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function createCategory(Request $request)
    {
        try {
            //code...
            $validation = Validator::make($request->all(), [
                'nama' => 'required'
            ], [
                'nama.required' => 'nama tidak boleh kosong'
            ]);

            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $category = Category::create([
                'nama' => $request->nama
            ]);

            return response()->json($category, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getAllCategory(Request $request)
    {
        try {
            //code...
            $nama = $request->query('nama');
            $limit = $request->limit ? $request->limit : 10;
            $where = [];
            if ($nama) $where = [...$where, ['nama', 'like', '%' . $nama . '%']];;
            $category = Category::where($where)->orderBy('id', 'DESC')->paginate($limit);
            if ($category->count() == 0) return response()->json(['message' => 'data tidak di temukan'], 404);
            return response()->json($category, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getCategorySelect(Request $request)
    {
        try {
            //code...
            $category = Category::all();
            return response()->json($category, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function updateCategory(Request $request)
    {
        try {
            //code...
            $id = $request->route('id');
            $category = Category::find($id);
            if (!$category) return response()->json(['message' => 'data tidak di temukan'], 404);
            $validation = Validator::make($request->all(), [
                'nama' => 'required'
            ], [
                'nama.required' => 'nama tidak boleh kosong'
            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $category->update([
                'nama' => $request->nama
            ]);
            return response()->json(['message' => 'data berhasil di ubah'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function deleteCategory(Request $request)
    {
        try {
            $id = $request->route('id');
            $modal = Modal::where('idCategory', $id)->first();
            if ($modal) return response()->json(['message' => 'category tidak dapat dihapus karna ada data terkait'], 400);
            $category = Category::find($id);
            if (!$category) return response()->json(['message' => 'data tidak ditemukan'], 404);
            $category->delete();
            return response()->json(['message' => 'data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
