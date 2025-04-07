<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tài liệu</title>
</head>

<body>

    <!-- Nút Thêm tài liệu -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addTaiLieuModal">
        Thêm tài liệu
    </button>

    <!-- Bảng hiển thị tài liệu -->
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Tiêu đề</th>
                <th>URL</th>
                <th>Thời gian</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="tailieu_table">
            <!-- Dữ liệu từ AJAX sẽ được đổ vào đây -->
        </tbody>
    </table>

    <!-- Modal Thêm tài liệu -->
    <div class="modal fade" id="addTaiLieuModal" tabindex="-1" aria-labelledby="addTaiLieuModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addTaiLieuForm">
                    <input type="hidden" id="addTaiLieuId">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm tài liệu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="taiLieuTieuDe" class="form-label"><span class="text-danger">*</span> Tiêu
                                đề</label>
                            <input type="text" class="form-control" id="taiLieuTieuDe" required>
                        </div>
                        <div class="mb-3">
                            <label for="taiLieuUrl" class="form-label"><span class="text-danger">*</span> URL</label>
                            <input type="url" class="form-control" id="taiLieuUrl" required>
                        </div>
                        <div class="mb-3">
                            <label for="taiLieuThoigian" class="form-label"><span class="text-danger">*</span> Thời
                                gian</label>
                            <div class="input-group">
                                <input type="text" class="form-control thoigian-picker" id="taiLieuThoigian"
                                    required>
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Chỉnh sửa tài liệu -->
    <div class="modal fade" id="editTaiLieuModal" tabindex="-1" aria-labelledby="editTaiLieuModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editTaiLieuForm">
                    <input type="hidden" id="editTaiLieuId">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa tài liệu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editTaiLieuTieuDe" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="editTaiLieuTieuDe" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTaiLieuUrl" class="form-label">URL</label>
                            <input type="url" class="form-control" id="editTaiLieuUrl" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTaiLieuThoigian" class="form-label">Thời gian</label>
                            <div class="input-group">
                                <input type="text" class="form-control thoigian-picker" id="editTaiLieuThoigian"
                                    required>
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>

<script src="/prjhrmthuan/public/assets/js/tailieu.js"></script>