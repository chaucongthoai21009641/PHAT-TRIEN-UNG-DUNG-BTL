$(document).ready(function () {
    loadTaiLieu(); // Tải danh sách tài liệu khi trang load

    // Hiển thị modal khi nhấn nút "Thêm tài liệu"
    $('#addTaiLieuBtn').on('click', function () {
        new bootstrap.Modal($('#addTaiLieuModal')).show();
    });

    // Xử lý submit form thêm tài liệu
    $('#addTaiLieuForm').on('submit', function (e) {
        e.preventDefault(); // Ngăn trang reload

        let thoigian = $('#taiLieuThoigian').val().split(" - "); // Tách khoảng thời gian thành 2 phần

        let formData = {
            tieuDe: $('#taiLieuTieuDe').val(),
            url: $('#taiLieuUrl').val(),
            tgBatDau: thoigian[0], // Thời gian bắt đầu
            tgKetThuc: thoigian[1]  // Thời gian kết thúc
        };

        $.ajax({
            url: "http://127.0.0.1:8000/api/tailieu",
            type: "POST",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            data: formData,
            success: function () {
                alert("Thêm tài liệu thành công!");
                $('#addTaiLieuModal').modal('hide'); // Ẩn modal sau khi thêm
                $('#addTaiLieuForm')[0].reset(); // Reset form
                loadTaiLieu(); // Reload danh sách
            },
            error: function (xhr) {
                console.error("Lỗi khi thêm:", xhr.responseText);
                alert("Thêm thất bại! " + xhr.responseText);
            }
        });
    });

    // Lấy danh sách tài liệu từ API
    function loadTaiLieu() {
        $.ajax({
            url: "http://localhost/prjhrmthuan/api/tailieu/tailieu.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                let rows = "";
                data.forEach((taiLieu) => {
                    rows += `
                        <tr>
                            <td>${taiLieu.maTL}</td>
                            <td>${taiLieu.tieuDe}</td>
                            <td><a href="${taiLieu.url}" target="_blank">${taiLieu.url}</a></td>
                            <td>${taiLieu.tgBatDau} - ${taiLieu.tgKetThuc}</td>
                            <td>
                                <button class="btn btn-warning" onclick="editTaiLieu(${taiLieu.maTL})">Sửa</button>
                                <button class="btn btn-danger" onclick="deleteTaiLieu(${taiLieu.maTL})">Xóa</button>
                            </td>
                        </tr>
                    `;
                });
                $("#tailieu_table").html(rows);
            },
            error: function () {
                alert("Không thể lấy dữ liệu từ API!");
            }
        });
    }

    // Xóa tài liệu
    window.deleteTaiLieu = function (id) {
        if (confirm("Bạn có chắc muốn xóa tài liệu này không?")) {
            $.ajax({
                url: `http://127.0.0.1:8000/api/tailieu/${id}`,
                type: "DELETE",
                headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                success: function () {
                    alert("Xóa thành công!");
                    loadTaiLieu(); // Reload danh sách
                },
                error: function (xhr) {
                    console.error("Lỗi khi xóa:", xhr.responseText);
                    alert("Xóa thất bại! " + xhr.responseText);
                }
            });
        }
    };

    // Chỉnh sửa tài liệu
    window.editTaiLieu = function (id) {
        $.ajax({
            url: `http://127.0.0.1:8000/api/tailieu/${id}`,
            type: "GET",
            dataType: "json",
            success: function (taiLieu) {
                $('#editTaiLieuId').val(taiLieu.maTL);
                $('#editTaiLieuTieuDe').val(taiLieu.tieuDe);
                $('#editTaiLieuUrl').val(taiLieu.url);
                $('#editTaiLieuThoigian').val(`${taiLieu.tgBatDau} - ${taiLieu.tgKetThuc}`);

                new bootstrap.Modal($('#editTaiLieuModal')).show();
            },
            error: function (xhr) {
                console.error("Lỗi khi tải dữ liệu:", xhr.responseText);
                alert("Không thể tải dữ liệu tài liệu!");
            }
        });
    };

    // Xử lý cập nhật tài liệu
    $('#editTaiLieuForm').on('submit', function (e) {
        e.preventDefault();

        let id = $('#editTaiLieuId').val();
        let thoigian = $('#editTaiLieuThoigian').val().split(" - ");

        let formData = {
            tieuDe: $('#editTaiLieuTieuDe').val(),
            url: $('#editTaiLieuUrl').val(),
            tgBatDau: thoigian[0],
            tgKetThuc: thoigian[1]
        };

        $.ajax({
            url: `http://127.0.0.1:8000/api/tailieu/${id}`,
            type: "PUT",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            data: formData,
            success: function () {
                alert("Cập nhật tài liệu thành công!");
                $('#editTaiLieuModal').modal('hide');
                loadTaiLieu();
            },
            error: function (xhr) {
                console.error("Lỗi khi cập nhật:", xhr.responseText);
                alert("Cập nhật thất bại! " + xhr.responseText);
            }
        });
    });

    // Kích hoạt Date Range Picker
    $('.thoigian-picker').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        locale: {
            format: 'YYYY-MM-DD HH:mm',
            applyLabel: "Chọn",
            cancelLabel: "Hủy",
            fromLabel: "Từ",
            toLabel: "Đến",
            customRangeLabel: "Tùy chỉnh",
            daysOfWeek: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
            monthNames: [
                "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6",
                "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
            ],
            firstDay: 1
        }
    });
});
