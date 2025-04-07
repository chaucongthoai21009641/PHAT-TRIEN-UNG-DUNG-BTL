<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Luong extends Model
{
    use HasFactory;

    protected $table = 'luong'; // Tên bảng
    protected $primaryKey = 'maLuong'; // Khóa chính
    public $timestamps = true; // Bật timestamps (created_at, updated_at)

    protected $fillable = [
        'kieuLuong'
    ]; // Cho phép mass assignment

    public function nhanvien()
    {
        return $this->hasOne(NhanVien::class, 'maLuong', 'maLuong');
    }
}
