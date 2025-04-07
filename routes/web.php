<?php

require_once '../core/Route.php';
require_once '../app/controllers/NhanVienController.php';
require_once '../app/controllers/LuongController.php';
require_once '../app/controllers/ThanhToanController.php';
require_once '../app/controllers/TaiKhoanController.php';
require_once '../app/controllers/HopThoaiController.php';
require_once '../app/controllers/ThongBaoController.php';
require_once '../app/controllers/TaiLieuController.php';
require_once '../app/controllers/CaLamController.php';
require_once '../app/controllers/ChiTietCaLamController.php';
require_once '../app/controllers/NgayLeController.php';
require_once '../app/controllers/LichLamViecController.php';

// Api nhân viên
Route::prefix('/api/nhanvien', function () {
    Route::get('/', 'NhanVienController@index');
    Route::post('/', 'NhanVienController@store');
    Route::get('/{id}', 'NhanVienController@show');
    Route::put('/{id}', 'NhanVienController@update');
    Route::delete('/{id}', 'NhanVienController@destroy');
});

// Api Lương
Route::prefix('/api/luong', function () {
    Route::get('/', 'LuongController@index');
    Route::post('/', 'LuongController@store');
    Route::get('/{id}', 'LuongController@show');
    Route::put('/{id}', 'LuongController@update');
    Route::delete('/{id}', 'LuongController@destroy');
});

// Api Thanh toán
Route::prefix('/api/thanhtoan', function () {
    Route::get('/', 'ThanhToanController@index');
    Route::post('/', 'ThanhToanController@store');
    Route::get('/{id}', 'ThanhToanController@show');
    Route::put('/{id}', 'ThanhToanController@update');
    Route::delete('/{id}', 'ThanhToanController@destroy');
});

// Api tài khoản
Route::prefix('/api/taikhoan', function () {
    Route::get('/', 'TaiKhoanController@index');
    Route::post('/', 'TaiKhoanController@store');
    Route::get('/{id}', 'TaiKhoanController@show');
    Route::put('/{id}', 'TaiKhoanController@update');
    Route::delete('/{id}', 'TaiKhoanController@destroy');
});

// Api hộp thoại
Route::prefix('/api/hopthoai', function () {
    Route::get('/', 'HopThoaiController@index');
    Route::post('/', 'HopThoaiController@store');
    Route::get('/{id}', 'HopThoaiController@show');
    Route::put('/{id}', 'HopThoaiController@update');
    Route::delete('/{id}', 'HopThoaiController@destroy');
});

// Api thông báo
Route::prefix('/api/thongbao', function () {
    Route::get('/', 'ThongBaoController@index');
    Route::post('/', 'ThongBaoController@store');
    Route::get('/{id}', 'ThongBaoController@show');
    Route::put('/{id}', 'ThongBaoController@update');
    Route::delete('/{id}', 'ThongBaoController@destroy');
});

// Api tài liệu
Route::prefix('/api/tailieu', function () {
    Route::get('/', 'TaiLieuController@index');
    Route::post('/', 'TaiLieuController@store');
    Route::get('/{id}', 'TaiLieuController@show');
    Route::put('/{id}', 'TaiLieuController@update');
    Route::delete('/{id}', 'TaiLieuController@destroy');
});

// Api Ca làm
Route::prefix('/api/calam', function () {
    Route::get('/', 'CaLamController@index');
    Route::get('/{maCL}/chitiet', 'CaLamController@getChiTietCaLam');
    Route::post('/', 'CaLamController@store');
    Route::get('/{id}', 'CaLamController@show');
    Route::put('/{id}', 'CaLamController@update');
    Route::delete('/{id}', 'CaLamController@destroy');
});

// Api Chi tiết ca làm
Route::prefix('/api/chitietcalam', function () {
    Route::get('/', 'ChiTietCaLamController@index');
    Route::post('/', 'ChiTietCaLamController@store');
    Route::get('/{id}', 'ChiTietCaLamController@show');
    Route::put('/{id}', 'ChiTietCaLamController@update');
    Route::delete('/{id}', 'ChiTietCaLamController@destroy');
});

// Api Ngày lễ
Route::prefix('/api/ngayle', function () {
    Route::get('/', 'NgayLeController@index');
    Route::post('/', 'NgayLeController@store');
    Route::get('/{id}', 'NgayLeController@show');
    Route::put('/{id}', 'NgayLeController@update');
    Route::delete('/{id}', 'NgayLeController@destroy');
});

// Api Lịch làm việc
Route::prefix('/api/lichlamviec', function () {
    Route::get('/', 'LichLamViecController@index');
    Route::post('/', 'LichLamViecController@store');
    Route::get('/{id}', 'LichLamViecController@show');
    Route::put('/{id}', 'LichLamViecController@update');
    Route::delete('/{id}', 'LichLamViecController@destroy');
});

// Xử lý request
Route::dispatch();
