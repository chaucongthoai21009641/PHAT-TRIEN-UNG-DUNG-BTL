$(document).ready(function () {
  loadThongBao(); // Tải danh sách thông báo khi trang load

  // Hiển thị modal khi nhấn nút "Thêm thông báo"
  $("#addThongBaoBtn").on("click", function () {
    new bootstrap.Modal($("#addThongBaoModal")).show();
  });

  // Xử lý submit form thêm thông báo
  $("#addThongBaoForm").on("submit", function (e) {
    e.preventDefault(); // Ngăn trang reload

    let thoigian = $("#thoigianThongBao").val().split(" - "); // Tách khoảng thời gian

    let formData = {
      tieuDe: $("#tieuDeThongBao").val(),
      url: $("#urlThongBao").val(),
      tgBatDau: thoigian[0],
      tgKetThuc: thoigian[1],
    };

    $.ajax({
      url: "http://127.0.0.1:8000/api/thongbao",
      type: "POST",
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
      data: formData,
      success: function () {
        alert("Thêm thông báo thành công!");
        $("#addThongBaoModal").modal("hide");
        $("#addThongBaoForm")[0].reset();
        loadThongBao();
      },
      error: function (xhr) {
        console.error("Lỗi khi thêm:", xhr.responseText);
        alert("Thêm thất bại! " + xhr.responseText);
      },
    });
  });

  // Lấy danh sách thông báo từ API
  function loadThongBao() {
    $.ajax({
      url: "http://localhost/prjhrmthuan/api/thongbao/thongbao.php",
      type: "GET",
      dataType: "json",
      success: function (data) {
        
        let rows = "";
        data.forEach((thongBao) => {
          rows += `
                        <tr>
                            <td>${thongBao.maTB}</td>
                            <td>${thongBao.tieuDe}</td>
                            <td><a href="${thongBao.url}" target="_blank">${thongBao.url}</a></td>
                            <td>${thongBao.tgBatDau} - ${thongBao.tgKetThuc}</td>
                            <td>
                                <button class="btn btn-warning" onclick="editThongBao(${thongBao.maTB})">Sửa</button>
                                <button class="btn btn-danger" onclick="deleteThongBao(${thongBao.maTB})">Xóa</button>
                            </td>
                        </tr>
                    `;
        });
        
        $("#thongbao_table").html(rows);
      },
      error: function () {
        alert("Không thể lấy dữ liệu từ API!");
      },
    });
  }

  // Xóa thông báo
  window.deleteThongBao = function (id) {
    if (confirm("Bạn có chắc muốn xóa thông báo này không?")) {
      $.ajax({
        url: `http://127.0.0.1:8000/api/thongbao/${id}`,
        type: "DELETE",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function () {
          alert("Xóa thành công!");
          loadThongBao();
        },
        error: function (xhr) {
          console.error("Lỗi khi xóa:", xhr.responseText);
          alert("Xóa thất bại! " + xhr.responseText);
        },
      });
    }
  };

  // Chỉnh sửa thông báo
  window.editThongBao = function (id) {
    $.ajax({
      url: `http://127.0.0.1:8000/api/thongbao/${id}`,
      type: "GET",
      dataType: "json",
      success: function (thongBao) {
        $("#editThongBaoId").val(thongBao.maTB);
        $("#editTieuDeThongBao").val(thongBao.tieuDe);
        $("#editUrlThongBao").val(thongBao.url);
        $("#editThoigianThongBao").val(
          `${thongBao.tgBatDau} - ${thongBao.tgKetThuc}`
        );

        new bootstrap.Modal($("#editThongBaoModal")).show();
      },
      error: function (xhr) {
        console.error("Lỗi khi tải dữ liệu:", xhr.responseText);
        alert("Không thể tải dữ liệu thông báo!");
      },
    });
  };

  // Xử lý cập nhật thông báo
  $("#editThongBaoForm").on("submit", function (e) {
    e.preventDefault();

    let id = $("#editThongBaoId").val();
    let thoigian = $("#editThoigianThongBao").val().split(" - ");

    let formData = {
      tieuDe: $("#editTieuDeThongBao").val(),
      url: $("#editUrlThongBao").val(),
      tgBatDau: thoigian[0],
      tgKetThuc: thoigian[1],
    };

    $.ajax({
      url: `http://127.0.0.1:8000/api/thongbao/${id}`,
      type: "PUT",
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
      data: formData,
      success: function () {
        alert("Cập nhật thông báo thành công!");
        $("#editThongBaoModal").modal("hide");
        loadThongBao();
      },
      error: function (xhr) {
        console.error("Lỗi khi cập nhật:", xhr.responseText);
        alert("Cập nhật thất bại! " + xhr.responseText);
      },
    });
  });

  // Kích hoạt Date Range Picker
  $(".thoigian-picker").daterangepicker({
    timePicker: true,
    timePicker24Hour: true,
    locale: {
      format: "YYYY-MM-DD HH:mm",
      applyLabel: "Chọn",
      cancelLabel: "Hủy",
      fromLabel: "Từ",
      toLabel: "Đến",
      customRangeLabel: "Tùy chỉnh",
      daysOfWeek: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
      monthNames: [
        "Tháng 1",
        "Tháng 2",
        "Tháng 3",
        "Tháng 4",
        "Tháng 5",
        "Tháng 6",
        "Tháng 7",
        "Tháng 8",
        "Tháng 9",
        "Tháng 10",
        "Tháng 11",
        "Tháng 12",
      ],
      firstDay: 1,
    },
  });
});
