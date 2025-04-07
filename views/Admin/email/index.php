<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gửi Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h4 class="mb-4">📬 Gửi Email</h4>

    <form action="./Admin/email/send_email.php" method="POST" enctype="multipart/form-data"
        class="border rounded p-4 bg-white shadow-sm" style="max-width: 1200px;">
            <div class="mb-3">
                <label for="to" class="form-label">Email người nhận</label>
                <input type="email" name="to" class="form-control" id="to" required>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Chủ đề</label>
                <input type="text" name="subject" class="form-control" id="subject" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Nội dung</label>
                <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="attachment" class="form-label">Tệp đính kèm (tuỳ chọn)</label>
                <input type="file" name="attachment" class="form-control" id="attachment">
            </div>
            <div class="d-flex justify-content-center"> 
                <button type="submit" class="btn btn-primary w-50">Gửi</button>
            </div>
    </form>
</body>
</html>
