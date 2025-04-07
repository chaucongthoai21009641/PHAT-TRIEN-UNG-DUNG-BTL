<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <title><?php echo isset($title) ? $title : 'Trang Admin'; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/assets/css/app.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body class="bg-gray-100">
    <div class="container-fluid d-flex p-0 m-0">
        <div class="d-flex">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="fs-4 fw-bold mb-4"><img width="70" src="../public/assets/uploads/logo_PT.png" alt="Image">HRM System</div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2 rounded-2">
                        <a href="index.php?EmployeeManagement"
                            class="nav-link load-page d-flex align-items-center text-white"
                            data-url="/quanlynhanvien">
                            <!-- <img width="20" src="/prjhrmthuan/public/assets/uploads/employee.png" alt="Image"> -->
                            <img width="20" src="../public/assets/uploads/employee.png" alt="Image">
                            Nhân viên
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="index.php?Announcement"
                            class="nav-link load-page d-flex align-items-center text-white"
                            data-url="/quanlythongbao">
                            <img width="20" src="../public/assets/uploads/bell.png" alt="Image">
                            Thông báo
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link justify-content-between d-flex align-items-center" data-bs-toggle="collapse"
                            href="#recruitmentMenu" role="button" aria-expanded="false" aria-controls="recruitmentMenu">
                            <img width="20" src="../public/assets/uploads/team.png" alt="Image">
                            Quản lý chấm công
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.5 8.25464L12 15.7546L4.5 8.25464" stroke="white" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <div class="collapse" id="recruitmentMenu">
                            <ul class="nav flex-column submenu">
                                <li class="nav-item mb-2">
                                    <a href="index.php?ScheduleManagement"
                                        class="nav-link load-page d-flex align-items-center text-white"
                                        data-url="/lichlamviec">
                                        <img width="20" src="../public/assets/uploads/calendar.png" alt="Image">
                                        Thiết lập ca làm
                                    </a>
                                </li>
                                <li class="nav-item mb-2">
                                    <a href="/bangchamcong"
                                        class="nav-link load-page d-flex align-items-center text-white"
                                        data-url="/bangchamcong">
                                        <img width="20" src="../public/assets/uploads/hourglass.png" alt="Image">
                                        Bảng chấm công
                                    </a>
                                </li>
                                <li class="nav-item mb-2">
                                    <a href="/donphep"
                                        class="nav-link load-page d-flex align-items-center text-white"
                                        data-url="/donphep">
                                        <img width="20" src="../public/assets/uploads/leave.png" alt="Image">
                                        Đơn phép
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="/tienluong" 
                            class="nav-link load-page d-flex align-items-center text-white" 
                            data-url="/tienluong">
                            <img width="20" src="../public/assets/uploads/salary.png" alt="Image">
                            Tiền lương
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="index.php?EmailManagement"
                            class="nav-link load-page d-flex align-items-center text-white"
                            data-url="/email">
                            <img width="20" src="../public/assets/uploads/email.png" alt="Image">
                            Gửi Email
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Nội dung chính -->
        <main class="flex-grow-1 p-4 overflow-hidden">
            <?php
            if (isset($_REQUEST['EmployeeManagement']))
                include('./Admin/Employee/index.php');
            elseif (isset($_REQUEST['Announcement']))
                include('./Admin/Notification/index.Php');
            elseif (isset($_REQUEST['ScheduleManagement']))
                include('./Admin/TimeKeeping/QLCaLam/index.php');
            elseif (isset($_REQUEST['TimeKeeperManagement']))
                include('./Admin/TimeKeeping/QLChamCong/index.php');
            elseif (isset($_REQUEST['EmailManagement']))
                include('./Admin/Email/index.php');
            else
                echo "<h3>Đây là trang dành cho ADMIN</h3>";
            ?>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    ?>
</body>

</html>
