$(document).ready(function () {
    // Lấy token CSRF
    function getCsrfToken() {
        return $('meta[name="csrf-token"]').attr("content");
    }
    
    loadCaLam(); // Tải danh sách ca làm khi trang load

    // Hiển thị modal khi nhấn nút "Thêm ca làm"
    $('#addShiftBtn').on('click', function () {
        resetForm();
        new bootstrap.Modal($('#addShiftModal')).show();
    });

    // Xử lý chọn thứ làm việc
    $(document).on("click", ".shift-day", function () {
        $(this).toggleClass("btn-primary btn-outline-primary");
    });

    // Xử lý submit form thêm ca làm
    $('#addShiftForm').on('submit', function (e) {
        e.preventDefault();
        let shiftData = getShiftData();

        $.ajax({
            url: "http://localhost/prjhrmthuan/api/calam/calam.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(shiftData),
            headers: { "X-CSRF-TOKEN": getCsrfToken() },
            success: function (response) {
                addChiTietCaLam(response.maCL);
            },
            error: function (xhr) {
                showAlert("Thêm thất bại!", xhr.responseText);
            }
        });
    });

    // Xóa ca làm
    $(document).on("click", ".delete-shift", function () {
        let id = $(this).data("id");
        if (confirm("Bạn có chắc muốn xóa ca làm này không?")) {
            deleteShift(id);
        }
    });

    // Hiển thị modal chỉnh sửa ca làm khi nhấn "Chỉnh sửa"
    $(document).on("click", ".edit-shift", function () {
        let shiftId = $(this).data("id");

        $.ajax({
            url: `http://localhost/prjhrmthuan/api/calam/calam.php/${shiftId}`,
            type: "GET",
            success: function (data) {
                $("#editShiftId").val(shiftId);
                $("#editShiftName").val(data.tenCa);
                $("#editCheckInEarly").val(data.gioCheckInSom);
                $("#editCheckOutLate").val(data.gioCheckOutMuon);

                new bootstrap.Modal($('#editShiftModal')).show();
            },
            error: function (xhr) {
                alert("Không thể tải dữ liệu ca làm: " + xhr.responseText);
            }
        });
    });

    // Xử lý submit form chỉnh sửa ca làm
    $('#editShiftForm').on('submit', function (e) {
        e.preventDefault();
        
        let shiftId = $("#editShiftId").val();
        let shiftData = {
            tenCa: $("#editShiftName").val(),
            gioCheckInSom: parseInt($("#editCheckInEarly").val()),
            gioCheckOutMuon: parseInt($("#editCheckOutLate").val())
        };

        $.ajax({
            url: `/api/calam/${shiftId}`,
            type: "PUT",
            contentType: "application/json",
            data: JSON.stringify(shiftData),
            headers: { "X-CSRF-TOKEN": getCsrfToken() },
            success: function () {
                alert("Cập nhật thành công!");
                $('#editShiftModal').modal('hide');
                loadCaLam();
            },
            error: function (xhr) {
                alert("Cập nhật thất bại: " + xhr.responseText);
            }
        });
    });

    // Hàm reset form khi mở modal
    function resetForm() {
        $('#addShiftForm')[0].reset();
        $('.shift-day').removeClass('btn-primary').addClass('btn-outline-primary');
    }

    // Lấy dữ liệu ca làm từ form
    function getShiftData() {
        return {
            tenCa: $("#shiftName").val(),
            gioCheckInSom: parseInt($("#checkInEarly").val()),
            gioCheckOutMuon: parseInt($("#checkOutLate").val())
        };
    }

    // Hiển thị thông báo
    function showAlert(title, message) {
        alert(title + " " + message);
    }

    // Thêm chi tiết ca làm
    function addChiTietCaLam(maCL) {
        let selectedDays = $(".shift-day.btn-primary").map(function () {
            return parseInt($(this).data("day"));
        }).get();

        let requests = selectedDays.map(day => {
            return $.ajax({
                url: "/api/chitietcalam",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    thuTrongTuan: day,
                    tgBatDau: $("#startTime").val(),
                    tgKetThuc: $("#endTime").val(),
                    tgBatDauNghi: $("#breakStartTime").val() || null,
                    tgKetThucNghi: $("#breakEndTime").val() || null,
                    heSoLuong: parseFloat($("#salaryMultiplier").val()),
                    tienThuong: parseFloat($("#bonus").val()),
                    maCL: maCL
                }),
                headers: { "X-CSRF-TOKEN": getCsrfToken() }
            });
        });

        $.when(...requests).done(function () {
            alert("Thêm ca làm và chi tiết thành công!");
            $('#addShiftModal').modal('hide');
            loadCaLam();
        }).fail(function (xhr) {
            alert("Thêm chi tiết ca làm thất bại: " + xhr.responseText);
        });
    }

    // Xóa ca làm
    function deleteShift(id) {
        $.ajax({
            url: `/api/calam/${id}`,
            type: "DELETE",
            headers: { "X-CSRF-TOKEN": getCsrfToken() },
            success: function () {
                showAlert("Thành công!", "Ca làm đã được xóa.");
                loadCaLam();
            },
            error: function (xhr) {
                showAlert("Xóa thất bại!", xhr.responseText);
            }
        });
    }

    // Tải danh sách ca làm từ API
    function loadCaLam() {
        $.when($.get("http://localhost/prjhrmthuan/api/calam/calam.php"), $.get("http://localhost/prjhrmthuan/api/chitietcalam/chitietcalam.php"))
            .done(function (calamData, chitietData) {
                renderScheduleTable(calamData[0], chitietData[0]);
            })
            .fail(function () {
                showAlert("Lỗi!", "Không thể tải dữ liệu ca làm.");
            });
    }

    $(document).on("click", ".edit-timeframe-btn, .add-timeframe-btn", function () {
        let thuTrongTuan = $(this).data("thu");  // Lấy thứ trong tuần
        let shiftID = $(this).data("shift");  // Lấy mã ca làm
        let timeFrameId = $(this).hasClass("edit-timeframe-btn") ? $(this).data("id") : "";
    
        // Gán dữ liệu vào modal (chú ý: sử dụng input ẩn)
        $("#editTimeFrameId").val(timeFrameId);
        $("#editShiftId").val(shiftID);
        $("#editSelectedDay").val(thuTrongTuan); // Lưu giá trị đúng vào input ẩn
    
        if (timeFrameId) {
            // Nếu là chỉnh sửa -> Lấy dữ liệu từ API
            $.ajax({
                url: `http://localhost/prjhrmthuan/api/chitietcalam/chitietcalam.php/${timeFrameId}`,
                type: "GET",
                success: function (data) {
                    $("#editStartTime").val(data.tgBatDau);
                    $("#editEndTime").val(data.tgKetThuc);
                    $("#editBreakStart").val(data.tgBatDauNghi || "");
                    $("#editBreakEnd").val(data.tgKetThucNghi || "");
                    $("#editSalaryFactor").val(data.heSoLuong);
                    $("#editBonus").val(data.tienThuong || 0);
                    $("#editTimeFrameModalLabel").text("Chỉnh sửa khung giờ");
                    $("#deleteTimeFrame").show();
                    new bootstrap.Modal($("#editTimeFrameModal")).show();
                },
                error: function (xhr) {
                    alert("Không thể tải dữ liệu khung giờ: " + xhr.responseText);
                }
            });
        } else {
            // Nếu là thêm mới
            $("#editStartTime, #editEndTime, #editBreakStart, #editBreakEnd").val("");
            $("#editSalaryFactor").val("1");
            $("#editBonus").val("0");
            $("#editTimeFrameModalLabel").text("Thêm khung giờ");
            $("#deleteTimeFrame").hide();
            new bootstrap.Modal($("#editTimeFrameModal")).show();
        }
    });

    // Tới đây
    // Xử lý submit form thêm / chỉnh sửa khung giờ
    $("#editTimeFrameForm").on("submit", function (e) {
        e.preventDefault();
    
        let timeFrameId = $("#editTimeFrameId").val();
        let thuTrongTuan = $("#editSelectedDay").val();
        let shiftID = $("#editShiftId").val();  
    
        console.log("Thêm/Sửa khung giờ:", { thuTrongTuan, timeFrameId, shiftID });
    
        let timeFrameData = {
            thuTrongTuan: thuTrongTuan,
            tgBatDau: $("#editStartTime").val(),
            tgKetThuc: $("#editEndTime").val(),
            tgBatDauNghi: $("#editBreakStart").val() || null,
            tgKetThucNghi: $("#editBreakEnd").val() || null,
            heSoLuong: parseFloat($("#editSalaryFactor").val()),
            tienThuong: parseFloat($("#editBonus").val()),
            maCL: shiftID
        };
    
        let requestType = timeFrameId ? "PUT" : "POST";
        let requestUrl = timeFrameId ? `http://localhost/prjhrmthuan/api/chitietcalam/chitietcalam.php/${timeFrameId}` : "http://localhost/prjhrmthuan/api/chitietcalam/chitietcalam.php";
    
        $.ajax({
            url: requestUrl,
            type: requestType,
            contentType: "application/json",
            data: JSON.stringify(timeFrameData),
            headers: { "X-CSRF-TOKEN": getCsrfToken() },
            success: function () {
                alert(timeFrameId ? "Cập nhật thành công!" : "Thêm khung giờ thành công!");
    
                $("#editTimeFrameModal").modal("hide");
    
                // 🔥 GỌI LẠI API LẤY DANH SÁCH CA LÀM SAU KHI THÊM
                loadCaLam();
            },
            error: function (xhr) {
                alert("Lỗi: " + xhr.responseText);
            }
        });
    });

    // Hiển thị bảng danh sách ca làm
    function renderScheduleTable(caLamList, chiTietList) {
        let groupedShifts = groupChiTietCaLam(chiTietList);
        let tableContent = "";
    
        caLamList.forEach(ca => {
            let row = `<tr>
                <td>
                    <div><strong>${ca.tenCa}</strong></div>
                    <div>Check-in: -${ca.gioCheckInSom} giờ</div>
                    <div>Check-out: +${ca.gioCheckOutMuon} giờ</div>
                    <button class="btn btn-warning edit-shift" data-id="${ca.maCL}">Chỉnh sửa</button>
                    <button class="btn btn-danger delete-shift" data-id="${ca.maCL}">Xóa</button>
                </td>`;
    
            for (let i = 1; i <= 7; i++) {
                let shifts = groupedShifts[ca.maCL]?.filter(s => s.thuTrongTuan == i) || [];
                let shiftButtons = shifts.map(shift => `
                    <div class="my-2">
                        <button class="btn btn-primary btn-sm edit-timeframe-btn" 
                            data-id="${shift.maCTCL}" 
                            data-thu="${i}" 
                            data-shift="${ca.maCL}">
                            ${shift.tgBatDau.slice(0, 5)} - ${shift.tgKetThuc.slice(0, 5)}
                        </button>
                    </div>
                `).join("");
    
                row += `<td>
                    ${shiftButtons}
                    <div class="my-2">
                        <button class="btn btn-outline-secondary btn-sm add-timeframe-btn" 
                        data-thu="${i}" 
                        data-shift="${ca.maCL}">+</button>
                    </div>
                </td>`;
            }
    
            row += "</tr>";
            tableContent += row;
    });
    
        $("#scheduleTable").html(tableContent);
    }

     // Xử lý nút xóa khung giờ
     $("#deleteTimeFrame").on("click", function () {
        let timeFrameId = $("#editTimeFrameId").val();
        if (confirm("Bạn có chắc muốn xóa khung giờ này?")) {
            $.ajax({
                url: `/api/chitietcalam/${timeFrameId}`,
                type: "DELETE",
                headers: { "X-CSRF-TOKEN": getCsrfToken() },
                success: function () {
                    alert("Đã xóa khung giờ!");
                    $("#editTimeFrameModal").modal("hide");
                    loadCaLam();
                },
                error: function (xhr) {
                    alert("Lỗi khi xóa: " + xhr.responseText);
                }
            });
        }
    });

    // Gom nhóm chi tiết ca làm theo mã ca
    function groupChiTietCaLam(chiTietList) {
        return chiTietList.reduce((acc, shift) => {
            if (!acc[shift.maCL]) acc[shift.maCL] = [];
            acc[shift.maCL].push(shift);
            return acc;
        }, {});
    }
});
