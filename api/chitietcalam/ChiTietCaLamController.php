<?php

namespace App\Http\Controllers;

use App\Models\ChiTietCaLam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChiTietCaLamController extends Controller
{
    // Lấy danh sách tất cả chi tiết ca làm
    public function index()
    {
        return response()->json(ChiTietCaLam::all());
    }

    // Thêm chi tiết ca làm mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'thuTrongTuan' => 'required|integer|min:1|max:7',
            'tgBatDau' => 'required|date_format:H:i',
            'tgKetThuc' => 'required|date_format:H:i',
            'tgBatDauNghi' => 'nullable|date_format:H:i',
            'tgKetThucNghi' => 'nullable|date_format:H:i',
            'heSoLuong' => 'required|numeric|min:1|max:50',
            'tienThuong' => 'numeric|min:0',
            'maCL' => 'required|exists:calam,maCL'
        ]);

        $chiTietCaLam = ChiTietCaLam::create($validated);
        return response()->json($chiTietCaLam, 201);
    }

    // Lấy thông tin một chi tiết ca làm theo ID
    public function show(string $id)
    {
        $chiTietCaLam = ChiTietCaLam::findOrFail($id);
        return response()->json($chiTietCaLam, 200);
    }

    // Cập nhật chi tiết ca làm
    public function update(Request $request, string $id)
    {
        $chiTietCaLam = ChiTietCaLam::findOrFail($id);
        $data = $request->only([
            'tgBatDau',
            'tgKetThuc',
            'tgBatDauNghi',
            'tgKetThucNghi',
            'heSoLuong',
            'tienThuong'
        ]);

        $chiTietCaLam->update($data);
        return response()->json($chiTietCaLam, 200);
    }

    // Xóa chi tiết ca làm
    public function destroy($id)
    {
        $chiTietCaLam = ChiTietCaLam::find($id);
        $chiTietCaLam->delete();
        return response()->json(['message' => 'Xóa chi tiết ca làm thành công'], 204);
    }
}