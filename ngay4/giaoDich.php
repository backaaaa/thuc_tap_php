<?php
session_start();

//xem session đã có thông tin giao dịch và số dư chưa
if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
    $_SESSION['balance'] = 0; 
}

function processTransaction() {
    global $total_thu, $total_chi;

    if (!preg_match("/^[0-9]+$/", $_POST['amount']) || $_POST['amount'] <= 0) {
        echo "<p style='color: red;'>Số tiền không hợp lệ! Phải là số dương.</p>";
        return;
    }
    if (!preg_match("/^([0-2][0-9]|3[0-1])\/([0][1-9]|1[0-2])\/\d{4}$/", $_POST['date'])) {
        echo "<p style='color: red;'>Ngày thực hiện không đúng định dạng (dd/mm/yyyy)!</p>";
        return;
    }
    if (isset($_POST['notes']) && (preg_match("/nợ xấu|vay nóng/i", $_POST['notes']))) {
        echo "<p style='color: orange;'>Cảnh báo: Ghi chú chứa từ khóa nhạy cảm!</p>";
        return;
    }

    //dữ liệu từ form
    $transaction_name = $_POST['transaction_name'];
    $amount = $_POST['amount'];
    $transaction_type = $_POST['transaction_type'];
    $notes = $_POST['notes'];
    $date = $_POST['date'];

    //xử lý giao dịch thu/chi
    if ($transaction_type == 'thu') {
        $_SESSION['balance'] += $amount; 
    } elseif ($transaction_type == 'chi') {
        $_SESSION['balance'] -= $amount;
    }

    // lưu giao dịch vào session
    $transaction = [
        'transaction_name' => $transaction_name,
        'amount' => $amount,
        'transaction_type' => $transaction_type,
        'notes' => $notes,
        'date' => $date
    ];

    //them giao dịch mới vào danh sách giao dịch
    $_SESSION['transactions'][] = $transaction;

    if ($transaction_type == 'thu') {
        $total_thu += $amount;
    } else {
        $total_chi += $amount;
    }
}

//khi người dùng gửi form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    processTransaction();
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="transaction_name">Tên Giao Dịch:</label>
    <input type="text" id="transaction_name" name="transaction_name" ><br>

    <label for="amount">Số Tiền:</label>
    <input type="number" id="amount" name="amount" ><br>

    <label>Loại Giao Dịch:</label>
    <input type="radio" id="thu" name="transaction_type" value="thu" > Thu
    <input type="radio" id="chi" name="transaction_type" value="chi" > Chi<br>

    <label for="notes">Ghi Chú (tuỳ chọn):</label>
    <textarea id="notes" name="notes"></textarea><br>

    <label for="date">Ngày Thực Hiện (dd/mm/yyyy):</label>
    <input type="text" id="date" name="date" ><br>

    <input type="submit" value="Gửi">
</form>

<hr>

<?php
if (!empty($_SESSION['transactions'])) {
    echo "<h3>Danh sách Giao Dịch:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Tên Giao Dịch</th><th>Số Tiền</th><th>Loại</th><th>Ghi Chú</th><th>Ngày Thực Hiện</th></tr>";

    foreach ($_SESSION['transactions'] as $transaction) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($transaction['transaction_name']) . "</td>";
        echo "<td>" . number_format($transaction['amount']) . " VND</td>";
        echo "<td>" . ($transaction['transaction_type'] == 'thu' ? 'Thu' : 'Chi') . "</td>";
        echo "<td>" . htmlspecialchars($transaction['notes']) . "</td>";
        echo "<td>" . htmlspecialchars($transaction['date']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>
<?php
$total_thu = 0;
$total_chi = 0;
foreach ($_SESSION['transactions'] as $t) {
    if ($t['transaction_type'] == 'thu') $total_thu += $t['amount'];
    if ($t['transaction_type'] == 'chi') $total_chi += $t['amount'];
}
?>

<h3>Thống kê:</h3>
<p>Tổng Thu: <?php echo number_format($total_thu); ?> VND</p>
<p>Tổng Chi: <?php echo number_format($total_chi); ?> VND</p>
<p>Số Dư: <?php echo number_format($total_thu - $total_chi); ?> VND</p>
