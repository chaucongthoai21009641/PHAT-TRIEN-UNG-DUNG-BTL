$(document).ready(function () {
  loadHopThoai(); // Gọi hàm loadHopThoai khi trang được tải

  function loadHopThoai() {
    $.ajax({
      url: "http://localhost/prjhrmthuan/api/hopthoai/hopthoai.php", // API lấy danh sách hộp thoại
      type: "GET",
      dataType: "json",
      success: function (data) {
        let cards = "";
        data.forEach((hopThoai) => {
          cards += `
            <div class="col-md-4 mb-3" id="hopthoai-${hopThoai.maHT}">
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
        $("#hopthoai_container").html(cards); // Cập nhật lại danh sách vào phần tử chứa
      },
      error: function () {
        alert("Không thể lấy dữ liệu từ API!");
      },
    });
  }
});

function deleteHopThoai(id) {
  if (confirm("Bạn có chắc muốn xóa hộp thoại này không?")) {
    let csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content"); // Lấy token

    $.ajax({
      url: `http://localhost/prjhrmthuan/api/hopthoai/deletehopthoai.php`,
      method: "POST", // Sử dụng phương thức POST
      headers: {
        "X-CSRF-TOKEN": csrfToken,
      },
      data: {
        maHT: id, // Gửi tham số maHT
      },
      success: function () {
        alert("Xóa thành công!");
        // Loại bỏ phần tử trong DOM ngay lập tức
        $("#hopthoai-" + id).remove();
      },
      error: function () {
        alert("Xóa thất bại!");
      },
    });
  }
}
