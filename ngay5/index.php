<?php
include 'includes/header.php';
include 'includes/logger.php';
include 'includes/upload.php';
?>
<div class="form-container">
    <h1>Chào mừng đến với hệ thống ghi nhật ký hoạt động</h1>
    <button class="btn btn-info"><a href="view_log.php">Xem</a></button>
    
    <div class="card" style="width: 400px;">
        <div class="card-header">
            <h4>Lựa Chọn Tùy Chọn</h4>
        </div>
        <div class="card-body">
            <form action="index.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Chọn hành động:</label>
                    <div>
                        <input type="radio" class="btn-check" name="action" id="login" value="login" >
                        <label class="btn btn-outline-primary" for="login">Đăng nhập</label>
                    </div>
                    <div>
                        <input type="radio" class="btn-check" name="action" id="logout" value="logout" >
                        <label class="btn btn-outline-primary" for="logout">Đăng xuất</label>
                    </div>
                    <div>
                        <input type="radio" class="btn-check" name="action" id="submit_form" value="submit_form" >
                        <label class="btn btn-outline-primary" for="submit_form">Gửi biểu mẫu</label>
                    </div>
                    <div>
                        <input type="radio" class="btn-check" name="action" id="upload_file" value="upload_file" >
                        <label class="btn btn-outline-primary" for="upload_file">Tải file</label>
                    </div>
                        <br>

                    <div id="fileInputContainer" style="display:none;">
                        <label for="file">Chọn file (PDF, JPG, PNG):</label>
                        <input type="file" name="file" id="file" accept=".jpg,.png,.pdf">
                    </div>

                    
                    
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $file = $_FILES['file'];

    // Ghi log hoạt động
    logAc($action);

    // Upload file nếu có
    // Kiểm tra lỗi khi tải file
    if ($file['error'] == 0) {
        uploadFile($file);
    } else {
        echo "Lỗi tải file: " . $file['error'];
    }
}
?>
