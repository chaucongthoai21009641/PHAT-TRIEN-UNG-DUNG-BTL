<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hộp thoại</title>
</head>
<body>
    <!-- Nút Thêm hộp thoại -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addHopThoaiModal">
        Thêm hộp thoại
    </button>

    <div id="hopthoai_container" class="row">
    <!-- Dữ liệu sẽ được đổ vào đây bằng AJAX -->
    </div>

    <!-- Modal Thêm tài liệu -->
    <div class="modal fade" id="addHopThoaiModal" tabindex="-1" aria-labelledby="addHopThoaiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addHopThoaiForm">
                    <input type="hidden" id="addHopThoaiId">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm hộp thoại</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="hopThoaiTieuDe" class="form-label">
                                <span class="text-danger">*</span> Tiêu đề
                            </label>
                            <input type="text" class="form-control" id="hopThoaiTieuDe" required>
                        </div>

                        <div class="mb-3">
                            <label for="hopThoaiNoiDung" class="form-label">
                                <span class="text-danger">*</span> Nội dung
                            </label>
                            <input type="text" class="form-control" id="hopThoaiNoiDung" required>
                        </div>

                        <div class="mb-3">
                            <label for="hopThoaiTgBatDau" class="form-label">
                                Thời gian bắt đầu
                            </label>
                            <input type="datetime-local" class="form-control" id="hopThoaiTgBatDau">
                        </div>

                        <div class="mb-3">
                            <label for="hopThoaiTgKetThuc" class="form-label">
                                Thời gian kết thúc
                            </label>
                            <input type="datetime-local" class="form-control" id="hopThoaiTgKetThuc">
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

</body>
</html>









<!--<a href="" class="btn btn-primary mb-3">Thêm hộp thoại</a>

<div id="hopthoai_container" class="row"> -->
    <!-- Dữ liệu sẽ được đổ vào đây bằng AJAX -->
</div> 

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script src="/public/assets/js/hopthoai.js"></script> -->


<!-- <script>
    $(document).ready(function() {
        $.ajax({
            url: "http://127.0.0.1:8000/api/hopthoai", // API lấy danh sách hộp thoại
            type: "GET",
            dataType: "json",
            success: function(data) {
                let cards = "";
                data.forEach(hopThoai => {
                    cards += `
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h5 class="card-title d-flex align-items-center">
                                        <span class="me-2 text-success">✔️</span> ${hopThoai.tieuDe}
                                    </h5>
                                    <p class="card-text">${hopThoai.noiDung}</p>
                                    <small class="text-muted">Thời gian: ${hopThoai.tgBatDau} - ${hopThoai.tgKetThuc}</small>
                                    <div class="mt-3 d-flex justify-content-between">
                                        <button class="btn btn-secondary btn-sm">👁 Xem</button>
                                        <button class="btn btn-warning btn-sm">✏️ Sửa</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteHopThoai(${hopThoai.maHT})">🗑 Xóa</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $("#hopthoai_container").html(cards);
            },
            error: function() {
                alert("Không thể lấy dữ liệu từ API!");
            }
        });
    });

    function deleteHopThoai(id) {
        if (confirm("Bạn có chắc muốn xóa hộp thoại này không?")) {
            $.ajax({
                url: `http://127.0.0.1:8000/api/hopthoai/${id}`,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function() {
                    alert("Xóa thành công!");
                    location.reload();
                },
                error: function() {
                    alert("Xóa thất bại!");
                }
            });
        }
    }
</script> -->

<script src="/prjhrmthuan/public/assets/js/hopthoai.js"></script>