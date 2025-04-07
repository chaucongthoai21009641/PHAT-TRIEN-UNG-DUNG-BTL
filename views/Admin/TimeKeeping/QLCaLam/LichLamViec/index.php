<link rel="stylesheet" href="/prjhrmthuan/public/assets/css/lichlamviec.css">

<div class="container-fluid">
    <!-- Thanh công cụ -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" id="btnToday">Hôm nay</button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignShiftModal">
                Gán ca làm việc
            </button>
            <button class="btn btn-outline-secondary">Chọn nhiều</button>
        </div>
        <input class="form-control w-auto" type="month" id="monthPicker" value="<?php echo date('Y-m'); ?>" />
    </div>

    <!-- Bảng lịch làm việc -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered bg-white">
            <thead id="scheduleHeader">
                <!-- Header sẽ được thêm bằng JS -->
            </thead>
            <tbody id="scheduleBody">
                <!-- Nội dung sẽ được thêm bằng JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form Thêm/Chỉnh sửa Lịch Làm Việc -->
<div class="modal fade" id="scheduleAddShiftModal" tabindex="-1" aria-labelledby="scheduleAddShiftModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleAddShiftModalLabel">Thêm mới lịch làm việc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleAddShiftForm">
                    <div class="mb-3">
                        <label for="scheduleShiftName" class="form-label">Tên ca (*)</label>
                        <input type="text" class="form-control" id="scheduleShiftName" name="scheduleShiftName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời gian làm việc (*)</label>
                        <div class="d-flex">
                            <input type="time" class="form-control me-2" id="scheduleStartTime" required>
                            <span class="align-self-center">→</span>
                            <input type="time" class="form-control ms-2" id="scheduleEndTime" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời gian nghỉ (không bắt buộc)</label>
                        <div class="d-flex">
                            <input type="time" class="form-control me-2" id="scheduleBreakStartTime">
                            <span class="align-self-center">→</span>
                            <input type="time" class="form-control ms-2" id="scheduleBreakEndTime">
                        </div>
                        <small class="text-muted">Bỏ trống nếu không có thời gian nghỉ.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời gian Check-in sớm và muộn</label>
                        <div class="d-flex">
                            <input type="time" class="form-control" id="scheduleCheckInEarly">
                            <span class="align-self-center">→</span>
                            <input type="time" class="form-control" id="scheduleCheckOutLate">
                        </div>
                        <small class="text-muted">Bỏ trống nếu không có thời gian nghỉ.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hệ số lương (*)</label>
                        <input type="number" class="form-control" id="scheduleSalaryMultiplier" value="1.0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thưởng</label>
                        <input type="number" class="form-control" id="scheduleBonus" min="0" step="0.01">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" id="scheduleDeleteShift" class="btn btn-danger" style="display: none;">Xóa</button>
                        <button type="submit" id="scheduleAddShiftSubmit" class="btn btn-success">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Gán ca làm việc -->
<div class="modal fade" id="assignShiftModal" tabindex="-1" aria-labelledby="assignShiftModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="assignShiftForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignShiftModalLabel">Gán ca làm việc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="employee" class="form-label"><span class="text-danger">*</span> Nhân viên</label>
                        <select id="employee" class="form-select">
                            <option value="">Chọn nhân viên</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="shift" class="form-label"><span class="text-danger">*</span> Tên ca làm việc</label>
                        <select id="shift" class="form-select">
                            <option value="">Chọn ca làm việc</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary">Ca làm mặc định</button>
                    <button type="submit" class="btn btn-primary">Gán ca</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="/prjhrmthuan/public/assets/js/lichlamviec.js"></script>