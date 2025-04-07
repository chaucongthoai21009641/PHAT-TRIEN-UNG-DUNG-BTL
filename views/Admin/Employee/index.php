<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách nhân viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>

    <h2>Danh sách nhân viên</h2>
    <!-- Nút mở modal Thêm nhân viên -->
    <a href="#" id="addEmployeeBtn" class="btn btn-primary">Thêm nhân viên</a>

    <table class="table table-hover mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Tài khoản</th>
                <th>Họ tên</th>
                <th>Chức danh</th>
                <th>Ngày vào làm</th>
                <th>Lương</th>
                <th>Số điện thoại</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody id="nhanvien-table">
            <!-- Dữ liệu sẽ được đổ vào đây bằng AJAX -->
        </tbody>
    </table>

    <!-- Modal Thêm nhân viên -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addEmployeeForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Thêm nhân viên mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="taiKhoan" class="form-label">
                                <span class="text-danger">*</span> Tài khoản
                            </label>
                            <input type="text" class="form-control" id="taiKhoan" name="taiKhoan" required>
                        </div>
                        <div class="mb-3">
                            <label for="matKhau" class="form-label">
                                <span class="text-danger">*</span> Mật khẩu
                            </label>
                            <input type="password" class="form-control" id="matKhau" name="matKhau" required>
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

    <!-- Modal chỉnh sửa trường -->
    <div class="modal fade" id="editFieldModal" tabindex="-1" aria-labelledby="editFieldModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editFieldForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFieldModalLabel">Chỉnh sửa thông tin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fieldValue" class="form-label" id="editFieldModalLabelContent">Giá trị
                                mới:</label>
                            <input type="text" id="fieldValue" name="fieldValue" class="form-control">
                        </div>
                        <!-- Lưu trữ ẩn record id và tên trường cần chỉnh sửa -->
                        <input type="hidden" id="editRecordId" name="editRecordId">
                        <input type="hidden" id="editFieldName" name="editFieldName">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="../public/assets/js/employee.js"></script>

</body>

</html>