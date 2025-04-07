$(document).ready(function () {
  $.ajax({
    url: "http://localhost/prjhrmthuan/api/hopthoai/hopthoai.php", // API lấy danh sách hộp thoại
    type: "GET",
    dataType: "json",
    success: function (data) {
      let cards = "";
      data.forEach((hopThoai) => {
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
    error: function () {
      alert("Không thể lấy dữ liệu từ API!");
    },
  });
});

function deleteHopThoai(id) {
  if (confirm("Bạn có chắc muốn xóa hộp thoại này không?")) {
    let csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content"); // Lấy token

    $.ajax({
      url: `http://127.0.0.1:8000/api/hopthoai/${id}`,
      type: "DELETE",
      headers: {
        "X-CSRF-TOKEN": csrfToken,
      },
      success: function () {
        alert("Xóa thành công!");
        location.reload();
      },
      error: function () {
        alert("Xóa thất bại!");
      },
    });
  }
}
