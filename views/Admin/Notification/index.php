<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1/daterangepicker.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<!-- Tabs -->
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="#messages" data-url="/prjhrmthuan/views/Admin/Notification/HopThoai/index.php">📩 Hộp thoại</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#notifications" data-url="/prjhrmthuan/views/Admin/Notification/ThongBao/index.php">🔔 Thông báo</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#documents" data-url="/prjhrmthuan/views/Admin/Notification/TaiLieu/index.php">📂 Tài liệu</a>
    </li>
</ul>

<!-- Nội dung của các tab -->
<div class="tab-content mt-3">
    <div id="messages"></div>
    <div id="notifications"></div>
    <div id="documents"></div>
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
        let firstTab = $(".nav-tabs .nav-link").first();
        let firstTabId = firstTab.attr("href");
        let firstTabUrl = firstTab.data("url");

        firstTab.addClass("active"); // Đánh dấu tab đầu tiên là active
        loadContent(firstTabId, firstTabUrl); // Load nội dung tab đầu tiên
        $(firstTabId).show(); // Hiển thị tab đầu tiên

        // Sự kiện khi nhấn vào tab
        $(".nav-tabs .nav-link").click(function(e) {
            e.preventDefault(); // Ngăn sự kiện mặc định

            let tabId = $(this).attr("href"); // Lấy ID của tab (VD: #messages)
            let url = $(this).data("url"); // Lấy URL file PHP tương ứng

            // Ẩn tất cả các tab content
            $(".tab-content > div").hide();

            // Nếu tab chưa có nội dung thì mới load lại
            if ($(tabId).is(":empty")) {
                loadContent(tabId, url);
            }

            $(tabId).show(); // Hiển thị tab mới

            // Đổi active class
            $(".nav-tabs .nav-link").removeClass("active");
            $(this).addClass("active");
        });
    });
</script>


<!-- <script src="/prjhrmthuan/public/assets/js/hopthoai.js"></script> -->
<!-- <script src="/prjhrmthuan/public/assets/js/thongbao.js"></script>
<script src="/prjhrmthuan/public/assets/js/tailieu.js"></script> -->

<!-- Daterangepicker CSS -->

<!-- Moment.js & Daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1/daterangepicker.min.js"></script>