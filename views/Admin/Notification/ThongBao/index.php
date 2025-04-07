<!-- Nút Thêm thông báo -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#thongBaoModal">
    Thêm thông báo
</button>

<!-- Bảng hiển thị thông báo -->
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
    <tbody id="thongbao_table">
        <!-- Dữ liệu từ AJAX sẽ được đổ vào đây -->
    </tbody>
</table>

<!-- Modal Thêm/Sửa thông báo -->
<div class="modal fade" id="thongBaoModal" tabindex="-1" aria-labelledby="thongBaoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="thongBaoForm">
                <input type="hidden" id="thongBaoId">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Thêm thông báo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tieuDeThongBao" class="form-label"><span class="text-danger">*</span> Tiêu đề</label>
                        <input type="text" class="form-control" id="tieuDeThongBao" name="tieuDeThongBao" required>
                    </div>
                    <div class="mb-3">
                        <label for="urlThongBao" class="form-label"><span class="text-danger">*</span> URL</label>
                        <input type="url" class="form-control" id="urlThongBao" name="urlThongBao" required>
                    </div>
                    <div class="mb-3">
                        <label for="thoigianThongBao" class="form-label"><span class="text-danger">*</span> Thời gian</label>
                        <div class="input-group">
                            <input type="text" class="form-control thoigian-picker" id="thoigianThongBao" name="thoigianThongBao" required>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/prjhrmthuan/public/assets/js/thongbao.js"></script>
