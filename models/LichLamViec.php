<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichLamViec extends Model
{
    use HasFactory;

    protected $table = 'lichlamviec';
    protected $primaryKey = 'maLLV';
    public $timestamps = true;

    protected $fillable = [
        'tenCa',
        'ngayLamViec',
        'tgBatDau',
        'tgKetThuc',
        'tgBatDauNghi',
        'tgKetThucNghi',
        'tgCheckInSom',
        'tgCheckOutMuon',
        'heSoLuong',
        'tienThuong',
        'maNV',
        // 'maNL',
        // 'maCTCL',
    ];

    public function nhanvien()
    {
        return $this->belongsTo(NhanVien::class, 'maNV', 'maNV');
    }

    // public function ngayle()
    // {
    //     return $this->belongsTo(NgayLe::class, 'maNL', 'maNL');
    // }

    // public function chitietcalam()
    // {
    //     return $this->hasOne(ChiTietCaLam::class, 'maCTCL', 'maCTCL');
    // }
}
