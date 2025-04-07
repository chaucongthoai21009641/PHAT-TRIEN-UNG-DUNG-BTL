<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ThanhToan extends Model
{
    use HasFactory;

    protected $table = 'thanhtoan';
    protected $primaryKey = 'maTT';
    public $timestamps = true;

    protected $fillable = [
        'tenDVhoacNH',
        'soDThoacSTK',
        'tenChuTaiKhoan',
        'hinhAnh'
    ];

    

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'maNV', 'maNV');
    }
}
