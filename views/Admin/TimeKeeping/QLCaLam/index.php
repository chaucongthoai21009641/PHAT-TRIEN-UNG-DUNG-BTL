<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<!-- Tabs -->
<ul class="nav nav-tabs" id="notificationTabs">
    <li class="nav-item">
        <a class="nav-link active" id="tab-thoikhoabieu" data-bs-toggle="tab" href="#thoikhoabieu"
            data-script="{{ asset('js/thietlapcalam.js') }}">
            <img width="20" src="/prjhrmthuan/public/assets/uploads/thoikhoabieu.png" alt="Image"> Mẫu thời khóa biểu
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="tab-ngayle" data-bs-toggle="tab" href="#ngayle"
            data-script="{{ asset('js/ngayle.js') }}">
            <img width="20" src="/prjhrmthuan/public/assets/uploads/leave.png" alt="Image"> Ngày lễ
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="tab-lichlamviec" data-bs-toggle="tab" href="#lichlamviec"
            data-script="{{ asset('js/lichlamviec.js') }}">
            <img width="20" src="/prjhrmthuan/public/assets/uploads/work-schedule.png" alt="Image"> Lịch làm việc
        </a>
    </li>
</ul>

<!-- Nội dung của các tab -->
<div class="tab-content mt-3">
    <div id="thoikhoabieu"></div>
    <div id="ngayle"></div>
    <div id="lichlamviec"></div>
</div>


<script>
    $(document).ready(function() {
        // Hàm tải nội dung vào tab
        function loadContent(tabId, url) {
            $(tabId).html('<p>Đang tải...</p>'); // Hiển thị loading
            $.get(url, function(data) {
                $(tabId).html(data); // Load nội dung vào tab
            }).fail(function() {
                $(tabId).html('<p class="text-danger">Lỗi khi tải nội dung!</p>');
            });
        }

        // Mặc định tải nội dung cho tab "Hộp thoại" khi trang load
        loadContent("#thoikhoabieu", "/prjhrmthuan/views/Admin/TimeKeeping/QLCaLam/ThoiKhoaBieu/index.php");
        $(".nav-link").first().addClass("active"); // Đánh dấu tab "Hộp thoại" là active

        // Sự kiện khi nhấn vào tab
        $(".nav-link").click(function(e) {
            e.preventDefault(); // Ngừng sự kiện mặc định để không cuộn tới phần tử trong tab

            let tabId = $(this).attr("href"); // Lấy ID của tab (ví dụ #messages)
            let url = $(this).data("url"); // Lấy URL file PHP tương ứng với tab

            // Ẩn tất cả các tab content
            $(".tab-content > div").hide();

            // Hiển thị tab content mới
            if ($(tabId).is(":empty")) { // Kiểm tra nếu chưa load thì mới tải
                loadContent(tabId, url);
            } else {
                $(tabId).show(); // Nếu nội dung đã được load, chỉ cần hiển thị
            }

            // Xóa class "active" khỏi tất cả các tab và thêm "active" vào tab hiện tại
            $(".nav-link").removeClass("active");
            $(this).addClass("active");
        });

        // Mặc định ẩn các tab không được chọn
        $(".tab-content > div").hide();
        $("#messages").show(); // Hiển thị tab mặc định
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1/daterangepicker.min.js"></script>