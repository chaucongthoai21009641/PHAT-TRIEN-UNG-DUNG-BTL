let csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");

$(document).ready(function () {
  loadEmployees();

  $("#addEmployeeBtn").on("click", () =>
    new bootstrap.Modal($("#addEmployeeModal")).show()
  );

  $("#addEmployeeForm").on("submit", function (e) {
    e.preventDefault();
    $.post({
      url: "http://localhost/prjhrmthuan/api/taikhoan/taikhoan.php",
      headers: { "X-CSRF-TOKEN": csrfToken },
      data: { tenTaiKhoan: $("#taiKhoan").val(), matKhau: $("#matKhau").val() },
      success: () => {
        alert("Thêm nhân viên thành công!");
        $("#addEmployeeModal").modal("hide");
        loadEmployees();
      },
      error: () => alert("Thêm nhân viên thất bại!"),
    });
  });

  $("#editFieldForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: `http://127.0.0.1:8000/api/nhanvien/${$("#editRecordId").val()}`,
      type: "PUT",
      headers: { "X-CSRF-TOKEN": csrfToken },
      data: { [$("#editFieldName").val()]: $("#fieldValue").val() },
      success: () => {
        alert("Cập nhật thành công!");
        $("#editFieldModal").modal("hide");
        loadEmployees();
      },
      error: () => alert("Cập nhật thất bại!"),
    });
  });
});

function loadEmployees() {
  $.getJSON(
    "http://localhost/prjhrmthuan/api/NhanVien/nhanvien.php",
    function (data) {
      let rows = data
        .map((nv) => {
          let isActive = nv.trangThai == 1;
          let icon = isActive
            ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="green" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 3 3m0 0 3-3m-3 3v-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>`;

          return `
                <tr>
                    <td class="d-flex align-items-center">
                        <span class="me-2">${nv.maNV}</span>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle status-btn p-1" type="button" data-bs-toggle="dropdown">
                                ${icon}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item change-status" href="#" data-id="${
                                  nv.maNV
                                }" data-status="1">Hoạt động</a></li>
                                <li><a class="dropdown-item change-status" href="#" data-id="${
                                  nv.maNV
                                }" data-status="0">Không hoạt động</a></li>
                            </ul>
                        </div>
                    </td>
                    <td>${nv.tai_khoan?.tenTaiKhoan || ""}</td>
                    ${generateEditableCells(nv)}
                    <td><button class="btn btn-danger" onclick="deleteNhanVien(${
                      nv.maNV
                    })">Xóa</button></td>
                </tr>
            `;
        })
        .join("");

      $("#nhanvien-table").html(rows);
      bindEditableCells();
    }
  ).fail(() => alert("Không thể lấy dữ liệu từ API!"));
}

function generateEditableCells(nv) {
  let fields = [
    "hoTen",
    "chucDanh",
    "ngayVaoLam",
    "tienLuong",
    "soDienThoai",
    "email",
  ];
  return fields
    .map(
      (field) => `
        <td class="editable-cell" data-field="${field}" data-id="${nv.maNV}">
            <span class="field-value">${nv[field]}</span>
            <i class="fa fa-pencil edit-icon" style="display:none; cursor:pointer;"></i>
        </td>`
    )
    .join("");
}

function bindEditableCells() {
  // Định nghĩa tên cột ánh xạ từ data-field
  const columnNames = {
    hoTen: "Họ tên",
    chucDanh: "Chức danh",
    ngayVaoLam: "Ngày vào làm",
    tienLuong: "Lương",
    soDienThoai: "Điện Thoại",
    email: "Email",
  };

  $(".editable-cell").hover(
    function () {
      if ($(this).data("field") !== "trangThai") {
        $(this).find(".field-value").css("text-decoration", "underline");
        $(this).find(".edit-icon").show();
      }
    },
    function () {
      $(this).find(".field-value").css("text-decoration", "none");
      $(this).find(".edit-icon").hide();
    }
  );

  // Khi click vào chữ hoặc icon, mở popup chỉnh sửa
  $(".editable-cell").on("click", function () {
    let cell = $(this);
    let fieldName = cell.data("field");
    let columnName = columnNames[fieldName] || fieldName; // Lấy tên tiếng Việt hoặc giữ nguyên nếu không có

    // Cập nhật cả tiêu đề modal và nội dung label
    $("#editFieldModalLabel").text(`Chỉnh sửa ${columnName}`);
    $("#editFieldModalLabelContent").text(columnName);

    // Điền thông tin hiện tại vào modal
    $("#fieldValue").val(cell.find(".field-value").text().trim());
    $("#editFieldName").val(fieldName);
    $("#editRecordId").val(cell.data("id"));

    // Hiển thị modal
    $("#editFieldModal").modal("show");
  });
}

$(document).on("click", ".change-status", function (e) {
  e.preventDefault();
  $.ajax({
    url: `http://127.0.0.1:8000/api/nhanvien/${$(this).data("id")}`,
    type: "PUT",
    headers: { "X-CSRF-TOKEN": csrfToken },
    data: { trangThai: $(this).data("status") },
    success: () => {
      alert("Cập nhật trạng thái thành công!");
      loadEmployees();
    },
    error: () => alert("Cập nhật trạng thái thất bại!"),
  });
});

function deleteNhanVien(id) {
  if (confirm("Bạn có chắc muốn xóa nhân viên này không?")) {
    $.ajax({
      url: `http://127.0.0.1:8000/api/nhanvien/${id}`,
      type: "DELETE",
      headers: { "X-CSRF-TOKEN": csrfToken },
      success: () => {
        alert("Xóa thành công!");
        loadEmployees();
      },
      error: () => alert("Xóa thất bại!"),
    });
  }
}
