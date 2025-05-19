<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $date = $_POST['date'];
    $logFile = 'logs/log_' . $date . '.txt';

    if(file_exists($logFile)){
        $fileContent = file_get_contents($logFile);
        echo nl2br($fileContent);
    }else{
        echo "Không có nhật kí trong ngày này";
    }

}
?>

<form action="view_log.php" method="post">
    <label for="date">Chọn ngày xem log:</label>
    <input type="date" name="date" id="date">
    <input type="submit" value="Xem nhật ký">
</form>
<a href="index.php">Quay lại</a>
