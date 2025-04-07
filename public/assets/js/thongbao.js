let csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");

$(document).ready(function () {
  loadThongBao(); // Tải danh sách thông báo khi trang load

  // Hiển thị modal khi nhấn nút "Thêm thông báo"
  $("#addThongBaoBtn").on("click", function () {
    openAddThongBaoModal(); // Mở modal thêm thông báo
  });

  // Xử lý submit form thêm hoặc sửa thông báo
  $("#thongBaoForm").on("submit", function (e) {
    e.preventDefault(); // Ngăn trang reload

    let thoigian = $("#thoigianThongBao").val().split(" - "); // Tách khoảng thời gian

    let formData = {
      maTB: $("#thongBaoId").val(),
      tieuDe: $("#tieuDeThongBao").val(),
      url: $("#urlThongBao").val(),
      tgBatDau: thoigian[0],
      tgKetThuc: thoigian[1],
    };

    let action = $(this).data("action"); // Lấy giá trị action (add hoặc edit)

    if (action === "add") {
      // Gửi yêu cầu thêm thông báo
      $.ajax({
        url: "http://localhost/prjhrmthuan/api/thongbao", // Đổi đường dẫn tại đây
        type: "POST",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: JSON.stringify(formData), // Gửi dữ liệu dưới dạng JSON
        contentType: "application/json", // Đảm bảo gửi JSON
        success: function () {
          alert("Thêm thông báo thành công!");
          $("#thongBaoModal").modal("hide");
          $("#thongBaoForm")[0].reset();
          loadThongBao();
        },
        error: function (xhr) {
          console.error("Lỗi khi thêm:", xhr.responseText);
          alert("Thêm thất bại! " + xhr.responseText);
        },
      });
    } else if (action === "edit") {
      // Gửi yêu cầu sửa thông báo
      let id = $("#thongBaoId").val();
      $.ajax({
        url: `http://localhost/prjhrmthuan/api/thongbao/updatethongbao.php`, // Đảm bảo đường dẫn chính xác
        type: "PUT", // Phương thức PUT để sửa
        contentType: "application/json", // Đảm bảo gửi JSON
        headers: {
          "X-CSRF-TOKEN": csrfToken, // CSRF token nếu có
        },
        data: JSON.stringify(formData), // Gửi dữ liệu dưới dạng JSON
        success: function () {
          alert("Cập nhật thông báo thành công!");
          $("#thongBaoModal").modal("hide");
          loadThongBao();
        },
        error: function (xhr) {
          console.error("Lỗi khi cập nhật:", xhr.responseText);
          alert("Cập nhật thất bại! " + xhr.responseText);
        },
      });
    }
  });

  // Lấy danh sách thông báo từ API
  function loadThongBao() {
    $.ajax({
      url: "http://localhost/prjhrmthuan/api/thongbao/thongbao.php", // Đảm bảo đường dẫn chính xác
      type: "GET",
      dataType: "json",
      success: function (data) {
        let rows = "";
        data.forEach((thongBao) => {
          rows += `
                      <tr id="thongbao-${thongBao.maTB}">
                          <td>${thongBao.maTB}</td>
                          <td>${thongBao.tieuDe}</td>
                          <td><a href="${thongBao.url}" target="_blank">${thongBao.url}</a></td>
                          <td>${thongBao.tgBatDau} - ${thongBao.tgKetThuc}</td>
                          <td>
                              <button class="btn btn-warning" onclick="openEditThongBaoModal(${thongBao.maTB})">Sửa</button>
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

  // Mở modal sửa thông báo
  window.openEditThongBaoModal = function (id) {
    // Tìm dòng chứa thông báo với maTB tương ứng
    var row = $("#thongbao-" + id); // Giả sử id là maTB, tìm dòng có id là thongbao-{maTB}

    // Lấy các giá trị từ các thẻ td trong dòng đó
    var tieuDe = row.find("td:eq(1)").text(); // Lấy tiêu đề (cột thứ 2)
    var url = row.find("td:eq(2)").text(); // Lấy URL (cột thứ 3)
    var tgBatDauKetThuc = row.find("td:eq(3)").text(); // Lấy thời gian bắt đầu và kết thúc (cột thứ 4)

    // Tách tgBatDauKetThuc thành hai phần (bắt đầu và kết thúc)
    var thoigian = tgBatDauKetThuc.split(" - ");
    var tgBatDau = thoigian[0];
    var tgKetThuc = thoigian[1];

    // Điền thông tin vào form
    $("#thongBaoId").val(id); // Điền mã thông báo (maTB)
    $("#tieuDeThongBao").val(tieuDe); // Điền tiêu đề
    $("#urlThongBao").val(url); // Điền URL
    $("#thoigianThongBao").val(tgBatDau + " - " + tgKetThuc); // Điền thời gian

    // Cập nhật modal title và action
    $("#modalTitle").text("Sửa thông báo");
    $("#thongBaoForm").data("action", "edit");

    // Hiển thị modal
    new bootstrap.Modal($("#thongBaoModal")).show();
  };

  // Mở modal thêm thông báo
  window.openAddThongBaoModal = function () {
    $("#thongBaoForm")[0].reset(); // Reset form
    $("#modalTitle").text("Thêm thông báo");
    $("#thongBaoForm").data("action", "add"); // Set action là thêm
    new bootstrap.Modal($("#thongBaoModal")).show();
  };

  // Xóa thông báo
  window.deleteThongBao = function (id) {
    if (confirm("Bạn có chắc muốn xóa thông báo này không?")) {
      let csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"); // Lấy token CSRF

      $.ajax({
        url: `http://localhost/prjhrmthuan/api/thongbao/deletethongbao.php`,
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
        },
        data: {
          maTB: id,
        },
        success: function (response) {
          if (response && response.message === "Xóa thông báo thành công.") {
            alert("Xóa thành công!");
            $("#thongbao-" + id).remove();
          } else {
            alert("Xóa thất bại! " + (response.error || "Không rõ lý do"));
          }
        },
        error: function (xhr) {
          console.error("Lỗi khi xóa thông báo:", xhr.responseText);
          alert("Xóa thất bại! " + (xhr.responseText || "Không rõ lý do"));
        },
      });
    }
  };

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
    },
  });
});
