$(document).ready(function () {
    function getCsrfToken() {
        return $('meta[name="csrf-token"]').attr("content");
    }

    function loadLichLamViec() {
        const selectedMonth = $("#monthPicker").val();

        // Gọi cả 2 API: 1 cái lấy danh sách nhân viên, 1 cái lấy lịch làm việc
        $.when(
            $.ajax({
                url: "/api/lichlamviec",
                method: "GET",
                data: { month: selectedMonth },
                headers: { "X-CSRF-TOKEN": getCsrfToken() },
            }),
            $.ajax({
                url: "/api/nhanvien", // API này phải trả về danh sách nhân viên
                method: "GET",
                headers: { "X-CSRF-TOKEN": getCsrfToken() },
            })
        ).done(function (shiftsResponse, employeesResponse) {
            let shiftsData = shiftsResponse[0]; // Dữ liệu lịch làm việc
            let employeesData = employeesResponse[0]; // Danh sách nhân viên

            renderLichLamViec(shiftsData, employeesData, selectedMonth);
        });
    }

    function renderLichLamViec(shiftsData, employeesData, month) {
        let dates = getAllDaysInMonth(month);
        let tableContent = "";

        // Tạo tiêu đề cột ngày
        let headerRow = `<tr>
            <th class="fixed-column-x fixed-column employee-name-cell">Nhân viên</th>`;
        dates.forEach((date) => {
            headerRow += `<th class="date-header" data-date="${date}">${formatDate(
                date
            )}</th>`;
        });
        headerRow += `</tr>`;
        $("#scheduleHeader").html(headerRow);

        // Duyệt qua từng nhân viên trong danh sách
        employeesData.forEach((employee) => {
            let row = `<tr>
                <td class="fixed-column employee-name-cell align-middle">
                    <img src="/images/default-avatar.png" class="rounded-circle me-2" width="40" height="40" alt="Avatar">
                    ${employee.hoTen}
                </td>`;

            // Duyệt từng ngày trong tháng
            dates.forEach((date) => {
                let shiftsForDate = shiftsData.filter(
                    (shift) =>
                        shift.maNV === employee.maNV &&
                        shift.ngayLamViec === date
                );

                let shiftButtons = shiftsForDate
                    .map(
                        (shift) => `
                    <div class="my-1">
                        <button class="schedule-box edit-shift mx-auto" 
                            data-id="${shift.maLLV}" 
                            data-employee="${shift.maNV}" 
                            data-date="${shift.ngayLamViec}"
                            data-shift-name="${shift.tenCa || ""}"
                            data-start-time="${
                                shift.tgBatDau ? shift.tgBatDau.slice(0, 5) : ""
                            }"
                            data-end-time="${
                                shift.tgKetThuc
                                    ? shift.tgKetThuc.slice(0, 5)
                                    : ""
                            }">
                            ${
                                shift.tgBatDau ? shift.tgBatDau.slice(0, 5) : ""
                            } - ${
                            shift.tgKetThuc ? shift.tgKetThuc.slice(0, 5) : ""
                        }
                        </button> 
                    </div>
                `
                    )
                    .join("");

                row += `<td align="center" class="text-center">
                    ${shiftButtons}
                    <div class="my-1">
                        <button class="btn btn-outline-secondary add-shift" 
                            data-employee="${employee.maNV}" 
                            data-date="${date}">+
                        </button> 
                    </div>
                </td>`;
            });

            row += `</tr>`;
            tableContent += row;
        });

        $("#scheduleBody").html(tableContent);
    }

    function getAllDaysInMonth(month) {
        let dates = [];
        let yearMonth = month.split("-");
        let daysInMonth = new Date(yearMonth[0], yearMonth[1], 0).getDate();
        for (let day = 1; day <= daysInMonth; day++) {
            dates.push(`${month}-${String(day).padStart(2, "0")}`);
        }
        return dates;
    }

    function formatDate(dateStr) {
        let date = new Date(dateStr);
        let day = String(date.getDate()).padStart(2, "0"); // 01, 02, 03,...
        let month = String(date.getMonth() + 1).padStart(2, "0"); // 01, 02, 03,...

        let weekdayNames = [
            "Chủ nhật",
            "Thứ 2",
            "Thứ 3",
            "Thứ 4",
            "Thứ 5",
            "Thứ 6",
            "Thứ 7",
        ];
        let weekday = weekdayNames[date.getDay()]; // Lấy thứ của ngày

        return `${day}/${month}<br><small>${weekday}</small>`;
    }

    function groupBy(array, key) {
        return array.reduce((result, item) => {
            (result[item[key]] = result[item[key]] || []).push(item);
            return result;
        }, {});
    }

    $(document).on("click", ".add-shift, .edit-shift", function () {
        let shiftId = $(this).hasClass("edit-shift") ? $(this).data("id") : "";
        let employeeId = $(this).data("employee");
        let date = $(this).data("date");
        console.log(employeeId);

        $("#scheduleAddShiftForm")[0].reset();
        $("#scheduleAddShiftForm").data({
            id: shiftId,
            employee: employeeId,
            date,
        });

        if (shiftId) {
            $("#scheduleAddShiftModalLabel").text("Cập nhật lịch làm việc");
            $("#scheduleAddShiftSubmit")
                .text("Cập nhật")
                .removeClass("btn-success")
                .addClass("btn-primary");

            // Điền dữ liệu vào form
            $("#scheduleShiftName").val($(this).data("shift-name") || "");
            $("#scheduleCheckInEarly").val($(this).data("checkin-early") || "");
            $("#scheduleCheckOutLate").val($(this).data("checkout-late") || "");
            $("#scheduleStartTime").val($(this).data("start-time") || "");
            $("#scheduleEndTime").val($(this).data("end-time") || "");
            $("#scheduleBreakStartTime").val($(this).data("break-start") || "");
            $("#scheduleBreakEndTime").val($(this).data("break-end") || "");
            $("#scheduleSalaryMultiplier").val(
                $(this).data("salary-multiplier") || 1.0
            );
            $("#scheduleBonus").val($(this).data("bonus") || 0);
        } else {
            $("#scheduleAddShiftModalLabel").text("Thêm mới lịch làm việc");
            $("#scheduleAddShiftSubmit")
                .text("Thêm")
                .removeClass("btn-primary")
                .addClass("btn-success");
        }
        $("#scheduleAddShiftModal").modal("show");
    });

    $("#monthPicker").on("change", loadLichLamViec);
    loadLichLamViec();

    $(document).on("submit", "#scheduleAddShiftForm", function (e) {
        e.preventDefault();
        let shiftId = $(this).data("id");
        let employeeId = $(this).data("employee");
        let date = $(this).data("date");

        function formatTime(value) {
            return value ? value.slice(0, 5) : null; // Cắt bỏ giây nếu có
        }

        let requestData = {
            maNV: employeeId || $("#scheduleAddShiftForm").data("employee"),
            tenCa: $("#scheduleShiftName").val().trim(),
            ngayLamViec: date,
            tgBatDau: formatTime($("#scheduleStartTime").val()),
            tgKetThuc: formatTime($("#scheduleEndTime").val()),
            tgBatDauNghi: formatTime($("#scheduleBreakStartTime").val()),
            tgKetThucNghi: formatTime($("#scheduleBreakEndTime").val()),
            tgCheckInSom: formatTime($("#scheduleCheckInEarly").val()),
            tgCheckOutMuon: formatTime($("#scheduleCheckOutLate").val()),
            heSoLuong: parseFloat($("#scheduleSalaryMultiplier").val()) || 1.0,
            tienThuong: parseFloat($("#scheduleBonus").val()) || 0,
        };

        console.log("Dữ liệu gửi đi:", requestData); // Debug dữ liệu gửi đi

        let url = `/api/lichlamviec/${shiftId ? shiftId : ""}`;
        let method = shiftId ? "PUT" : "POST";

        $.ajax({
            url: url,
            method: method,
            headers: { "X-CSRF-TOKEN": getCsrfToken() },
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(requestData),
            success: function (response) {
                console.log("Phản hồi từ server:", response);
                $("#scheduleAddShiftModal").modal("hide");
                loadLichLamViec();
                alert("Cập nhật thành công!");
            },
            error: function (xhr) {
                console.error("Lỗi cập nhật:", xhr.responseJSON);
                alert(
                    "Lỗi cập nhật: " +
                        (xhr.responseJSON?.message ||
                            "Vui lòng kiểm tra lại dữ liệu")
                );
            },
        });
    });

    // Xóa
    $(document).on("click", ".add-shift, .edit-shift", function () {
        let shiftId = $(this).hasClass("edit-shift") ? $(this).data("id") : "";

        if (shiftId) {
            $("#scheduleDeleteShift").show(); // Hiện nút Xóa
        } else {
            $("#scheduleDeleteShift").hide(); // Ẩn nút Xóa nếu thêm mới
        }
    });

    $(document).on("click", "#scheduleDeleteShift", function () {
        let shiftId = $("#scheduleAddShiftForm").data("id");

        if (!shiftId) return;

        if (confirm("Bạn có chắc chắn muốn xóa ca làm việc này không?")) {
            $.ajax({
                url: `/api/lichlamviec/${shiftId}`,
                method: "DELETE",
                headers: { "X-CSRF-TOKEN": getCsrfToken() },
                success: function () {
                    $("#scheduleAddShiftModal").modal("hide");
                    loadLichLamViec();
                    alert("Xóa thành công!");
                },
                error: function (xhr) {
                    alert(
                        "Lỗi khi xóa: " +
                            (xhr.responseJSON.message || "Vui lòng thử lại.")
                    );
                },
            });
        }
    });

    $("#btnToday")
        .off("click")
        .on("click", function () {
            let today = new Date();
            let year = today.getFullYear();
            let month = String(today.getMonth() + 1).padStart(2, "0");
            let day = String(today.getDate()).padStart(2, "0");
            let todayStr = `${year}-${month}-${day}`; // Định dạng YYYY-MM-DD

            let todayCell = $(`#scheduleHeader th[data-date="${todayStr}"]`);
            if (todayCell.length === 0) {
                alert("Không tìm thấy lịch ngày hôm nay!");
                return;
            }

            let tableContainer = $(".table-responsive");
            let tableScrollLeft = tableContainer.scrollLeft();
            let tableOffsetLeft = tableContainer.offset().left;
            let todayOffsetLeft = todayCell.offset().left;
            let containerWidth = tableContainer.width();
            let cellWidth = todayCell.outerWidth();

            // Tính toán vị trí cuộn mục tiêu
            let targetScroll =
                todayOffsetLeft -
                tableOffsetLeft -
                containerWidth / 2 +
                cellWidth / 2;

            // Kiểm tra xem ngày hiện tại đã hiển thị trong khu vực cuộn chưa
            let isTodayInView =
                targetScroll >= tableScrollLeft &&
                targetScroll <= tableScrollLeft + containerWidth;

            // Nếu ngày hiện tại không có trong vùng nhìn thấy, cuộn đến ngày đó
            if (!isTodayInView) {
                tableContainer
                    .stop(true, true)
                    .animate({ scrollLeft: targetScroll }, 500);
            }

            // Highlight ngày hôm nay
            todayCell.addClass("highlight");
            setTimeout(() => todayCell.removeClass("highlight"), 3000);
        });

    function loadNhanVien() {
        $.ajax({
            url: "/api/nhanvien",
            method: "GET",
            headers: { "X-CSRF-TOKEN": getCsrfToken() },
            success: function (response) {
                let employeeSelect = $("#employee");
                employeeSelect
                    .empty()
                    .append('<option value="">Chọn nhân viên</option>');
                response.forEach((employee) => {
                    employeeSelect.append(
                        `<option value="${employee.maNV}">${employee.maNV} - ${employee.hoTen}</option>`
                    );
                });
            },
            error: function () {
                alert("Lỗi khi tải danh sách nhân viên!");
            },
        });
    }

    function loadCaLamViec() {
        $.ajax({
            url: "/api/calam",
            method: "GET",
            headers: { "X-CSRF-TOKEN": getCsrfToken() },
            success: function (response) {
                let shiftSelect = $("#shift");
                shiftSelect
                    .empty()
                    .append('<option value="">Chọn ca làm việc</option>');
                response.forEach((shift) => {
                    shiftSelect.append(
                        `<option value="${shift.maCL}">${shift.tenCa}</option>`
                    );
                });
            },
            error: function () {
                alert("Lỗi khi tải danh sách ca làm việc!");
            },
        });
    }

    // Gọi khi trang tải
    loadNhanVien();
    loadCaLamViec();

    function subtractHours(timeStr, hours) {
        if (!timeStr || hours === null || hours === undefined) return null;
        let [hour, minute] = timeStr.split(":").map(Number);
        let date = new Date();
        date.setHours(hour - hours, minute, 0);
        return date.toTimeString().slice(0, 5); // Trả về định dạng "HH:mm"
    }

    function addHours(timeStr, hours) {
        if (!timeStr || hours === null || hours === undefined) return null;
        let [hour, minute] = timeStr.split(":").map(Number);
        let date = new Date();
        date.setHours(hour + hours, minute, 0);
        return date.toTimeString().slice(0, 5); // Trả về định dạng "HH:mm"
    }

    $(document).on("submit", "#assignShiftForm", function (e) {
        e.preventDefault();

        let selectedMonth = $("#monthPicker").val(); // Lấy giá trị tháng từ input
        console.log("Tháng được chọn:", selectedMonth);

        let employeeId = $("#employee").val();
        let shiftId = $("#shift option:selected").val(); // Đảm bảo lấy đúng giá trị shift

        console.log("Shift ID được chọn:", shiftId);
        console.log("Employee ID được chọn:", employeeId);

        if (!employeeId || !shiftId) {
            alert("Vui lòng chọn nhân viên và ca làm việc.");
            return;
        }

        // Lấy danh sách chi tiết ca làm từ API
        $.ajax({
            url: `/api/calam/${shiftId}/chitiet`,
            method: "GET",
            headers: { "X-CSRF-TOKEN": getCsrfToken() },
            success: function (shiftDetails) {
                console.log("Chi tiết ca làm việc nhận được:", shiftDetails);

                if (
                    !shiftDetails ||
                    !shiftDetails.chi_tiet_ca_lams ||
                    shiftDetails.chi_tiet_ca_lams.length === 0
                ) {
                    alert("Không có chi tiết ca làm việc nào cho ca này.");
                    return;
                }

                let requests = [];

                function formatTime(value) {
                    if (!value) return null; // Nếu value là null, undefined, hoặc falsy, trả về null
                    return String(value).slice(0, 5); // Ép kiểu về chuỗi trước khi cắt
                }

                shiftDetails.chi_tiet_ca_lams.forEach((detail) => {
                    let workingDates = getAllDatesByWeekdayInMonth(
                        detail.thuTrongTuan,
                        selectedMonth
                    );

                    workingDates.forEach((ngayLamViec) => {
                        let requestData = {
                            maNV: employeeId,
                            tenCa: shiftDetails.tenCa,
                            ngayLamViec: ngayLamViec,
                            tgBatDau: formatTime(detail.tgBatDau),
                            tgKetThuc: formatTime(detail.tgKetThuc),
                            tgBatDauNghi:
                                formatTime(detail.tgBatDauNghi) || null,
                            tgKetThucNghi:
                                formatTime(detail.tgKetThucNghi) || null,
                            tgCheckInSom: subtractHours(
                                formatTime(detail.tgBatDau),
                                shiftDetails.gioCheckInSom
                            ),
                            tgCheckOutMuon: addHours(
                                formatTime(detail.tgKetThuc),
                                shiftDetails.gioCheckOutMuon
                            ),
                            heSoLuong: parseFloat(detail.heSoLuong) || 1.0,
                            tienThuong: parseFloat(detail.tienThuong) || 0.0,
                        };

                        requests.push(
                            $.ajax({
                                url: "/api/lichlamviec",
                                method: "POST",
                                data: requestData,
                                headers: { "X-CSRF-TOKEN": getCsrfToken() },
                            })
                        );
                    });
                });

                // Chờ tất cả request hoàn tất
                Promise.all(requests)
                    .then(() => {
                        alert("Gán ca thành công!");
                        $("#assignShiftModal").modal("hide");
                        loadLichLamViec();
                    })
                    .catch((err) => {
                        console.error("Lỗi khi gán ca làm việc:", err);
                        alert("Lỗi khi gán ca làm việc. Vui lòng thử lại.");
                    });
            },
            error: function (xhr) {
                console.error("Lỗi khi tải chi tiết ca làm:", xhr.responseText);
                alert("Lỗi khi tải chi tiết ca làm việc!");
            },
        });
    });

    function getAllDatesByWeekdayInMonth(weekday, month) {
        let yearMonth = month.split("-");
        let year = parseInt(yearMonth[0]);
        let monthIndex = parseInt(yearMonth[1]) - 1;
        let dates = [];

        let firstDay = new Date(year, monthIndex, 1);
        let lastDay = new Date(year, monthIndex + 1, 0);

        for (let d = firstDay; d <= lastDay; d.setDate(d.getDate() + 1)) {
            if (d.getDay() === weekday) {
                let day = String(d.getDate()).padStart(2, "0");
                let formattedDate = `${year}-${yearMonth[1]}-${day}`;
                dates.push(formattedDate);
            }
        }

        return dates;
    }

    // Hàm lấy ngày làm việc sắp tới dựa vào thứ
    function getUpcomingDateByWeekday(weekday) {
        let today = new Date();
        let todayWeekday = today.getDay(); // 0: Chủ nhật, 1: Thứ 2, ..., 6: Thứ 7
        let daysToAdd = (weekday - todayWeekday + 7) % 7;
        if (daysToAdd === 0) daysToAdd = 7; // Nếu trùng thứ hiện tại, lấy tuần sau
        today.setDate(today.getDate() + daysToAdd);

        let year = today.getFullYear();
        let month = String(today.getMonth() + 1).padStart(2, "0");
        let day = String(today.getDate()).padStart(2, "0");

        return `${year}-${month}-${day}`;
    }
});
