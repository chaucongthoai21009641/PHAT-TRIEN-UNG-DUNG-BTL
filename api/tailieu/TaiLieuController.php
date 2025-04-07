<?php
require_once 'config/database.php'; // Kết nối Database
header('Content-Type: application/json');

class TaiLieuController
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Lấy danh sách tài liệu
    public function index()
    {
        $sql = "SELECT * FROM tailieu";
        $result = $this->conn->query($sql);

        if (!$result) {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi khi truy vấn dữ liệu"]);
            return;
        }

        $taiLieus = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($taiLieus);
    }

    // Thêm tài liệu mới
    public function store($data)
    {
        if (!isset($data['tieuDe'], $data['url'], $data['tgBatDau'], $data['tgKetThuc'])) {
            http_response_code(400);
            echo json_encode(["error" => "Thiếu dữ liệu đầu vào"]);
            return;
        }

        if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
            http_response_code(400);
            echo json_encode(["error" => "URL không hợp lệ"]);
            return;
        }

        if (strtotime($data['tgBatDau']) >= strtotime($data['tgKetThuc'])) {
            http_response_code(400);
            echo json_encode(["error" => "Thời gian kết thúc phải sau thời gian bắt đầu"]);
            return;
        }

        $sql = "INSERT INTO tailieu (tieuDe, url, tgBatDau, tgKetThuc) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $data['tieuDe'], $data['url'], $data['tgBatDau'], $data['tgKetThuc']);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Thêm tài liệu thành công", "maTL" => $this->conn->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi khi thêm tài liệu"]);
        }
    }

    // Lấy thông tin một tài liệu
    public function show($id)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID không hợp lệ"]);
            return;
        }

        $sql = "SELECT * FROM tailieu WHERE maTL = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $taiLieu = $result->fetch_assoc();

        if ($taiLieu) {
            echo json_encode($taiLieu);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Không tìm thấy tài liệu"]);
        }
    }

    // Cập nhật tài liệu
    public function update($id, $data)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID không hợp lệ"]);
            return;
        }

        $sql = "UPDATE tailieu SET 
                tieuDe = COALESCE(?, tieuDe), 
                url = COALESCE(?, url), 
                tgBatDau = COALESCE(?, tgBatDau), 
                tgKetThuc = COALESCE(?, tgKetThuc) 
                WHERE maTL = ?";
        $stmt = $this->conn->prepare($sql);

        $tieuDe = isset($data['tieuDe']) ? $data['tieuDe'] : null;
        $url = isset($data['url']) ? $data['url'] : null;
        $tgBatDau = isset($data['tgBatDau']) ? $data['tgBatDau'] : null;
        $tgKetThuc = isset($data['tgKetThuc']) ? $data['tgKetThuc'] : null;

        $stmt->bind_param("ssssi", $tieuDe, $url, $tgBatDau, $tgKetThuc, $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Cập nhật thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi khi cập nhật"]);
        }
    }

    // Xóa tài liệu
    public function destroy($id)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID không hợp lệ"]);
            return;
        }

        $sql = "DELETE FROM tailieu WHERE maTL = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Xóa tài liệu thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi khi xóa"]);
        }
    }
}

// Xử lý request từ URL
$controller = new TaiLieuController();
$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", trim($_SERVER['PATH_INFO'], "/"));

if ($request[0] === "tailieu") {
    switch ($method) {
        case "GET":
            isset($request[1]) ? $controller->show($request[1]) : $controller->index();
            break;
        case "POST":
            $data = json_decode(file_get_contents("php://input"), true);
            $controller->store($data);
            break;
        case "PUT":
            $data = json_decode(file_get_contents("php://input"), true);
            isset($request[1]) ? $controller->update($request[1], $data) : http_response_code(400);
            break;
        case "DELETE":
            isset($request[1]) ? $controller->destroy($request[1]) : http_response_code(400);
            break;
        default:
            http_response_code(405);
            echo json_encode(["error" => "Phương thức không hợp lệ"]);
    }
}
