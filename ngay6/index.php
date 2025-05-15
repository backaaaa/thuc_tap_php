<?php
session_start();


$products_list = [
    ['title' => 'Clean Code', 'price' => 150000],
    ['title' => 'Design Patterns', 'price' => 200000],
    ['title' => 'Refactoring', 'price' => 180000],
    ['title' => 'The Pragmatic Programmer', 'price' => 170000],
    ['title' => 'Code Complete', 'price' => 160000],
];

function log_error($msg) {
    file_put_contents('log.txt', date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND);
}

class FileWriteException extends Exception {}

$error = '';
$success = false;
$order_info = null;

$cookie_email = isset($_COOKIE['customer_email']) ? $_COOKIE['customer_email'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $selected_title = isset($_POST['book_title']) ? trim($_POST['book_title']) : '';
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($quantity === false || $quantity === null) {
            throw new Exception("Số lượng không hợp lệ.");
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if (!$email) throw new Exception("Email không hợp lệ.");

        $phone = filter_input(INPUT_POST, 'phone', FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => '/^\d{9,15}$/']
        ]);
        if (!$phone) throw new Exception("Số điện thoại không hợp lệ (chỉ gồm 9-15 chữ số).");

        $address_raw = isset($_POST['address']) ? trim($_POST['address']) : '';
        $address = trim($address_raw);
        if (empty($address)) throw new Exception("Địa chỉ không được để trống.");

        $book = null;
        foreach ($products_list as $p) {
            if ($p['title'] === $selected_title) {
                $book = $p;
                break;
            }
        }
        if (!$book) throw new Exception("Sản phẩm không hợp lệ.");

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['title'] === $book['title']) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = [
                'title' => $book['title'],
                'price' => $book['price'],
                'quantity' => $quantity
            ];
        }

        setcookie('customer_email', $email, time() + 7 * 24 * 60 * 60);

        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
        $order_data = [
            'customer_email' => $email,
            'products' => $_SESSION['cart'],
            'total_amount' => $total_amount,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if (false === file_put_contents('cart_data.json', json_encode($order_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            throw new FileWriteException("Lỗi ghi dữ liệu đơn hàng.");
        }

        $success = true;
        $order_info = [
            'products' => $_SESSION['cart'],
            'total_amount' => $total_amount,
            'customer_email' => $email,
            'phone' => $phone,
            'address' => $address,
            'created_at' => $order_data['created_at']
        ];

    } catch (FileWriteException $fe) {
        $error = "Lỗi lưu đơn hàng, vui lòng thử lại sau.";
        log_error($fe->getMessage());
    } catch (Exception $ex) {
        $error = $ex->getMessage();
        log_error($ex->getMessage());
    }
}

if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    if (file_exists('cart_data.json')) unlink('cart_data.json');
    $success = false;
    $error = "Giỏ hàng đã được xóa.";
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Ứng dụng giỏ hàng đơn giản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Chọn sách và đặt hàng</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>

    <?php if ($success && $order_info): ?>
        <div class="alert alert-success">Đặt hàng thành công!</div>
        <h4>Thông tin đơn hàng</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên sách</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_info['products'] as $item): ?>
                    <tr>
                        <td><?=htmlspecialchars($item['title'])?></td>
                        <td><?=number_format($item['price'])?> đ</td>
                        <td><?=$item['quantity']?></td>
                        <td><?=number_format($item['price'] * $item['quantity'])?> đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Tổng tiền</th>
                    <th><?=number_format($order_info['total_amount'])?> đ</th>
                </tr>
            </tfoot>
        </table>

        <h5>Thông tin khách hàng</h5>
        <ul>
            <li>Email: <?=htmlspecialchars($order_info['customer_email'])?></li>
            <li>Số điện thoại: <?=htmlspecialchars($order_info['phone'])?></li>
            <li>Địa chỉ giao hàng: <?=htmlspecialchars($order_info['address'])?></li>
            <li>Thời gian đặt hàng: <?=$order_info['created_at']?></li>
        </ul>

        <form method="post" onsubmit="return confirm('Bạn có chắc muốn xóa giỏ hàng không?');">
            <button type="submit" name="clear_cart" class="btn btn-danger">Xóa giỏ hàng</button>
        </form>
        <hr>
    <?php endif; ?>

    <form method="post" class="row g-3 needs-validation" novalidate>
        <div class="col-md-6">
            <label for="book_title" class="form-label">Chọn sách</label>
            <select class="form-select" id="book_title" name="book_title" required>
                <option value="">-- Chọn sách --</option>
                <?php foreach ($products_list as $p): ?>
                    <option value="<?=htmlspecialchars($p['title'])?>"><?=htmlspecialchars($p['title'])?> - <?=number_format($p['price'])?> đ</option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Vui lòng chọn sách.</div>
        </div>
        <div class="col-md-2">
            <label for="quantity" class="form-label">Số lượng</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required />
            <div class="invalid-feedback">Số lượng phải lớn hơn 0.</div>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email nhận đơn hàng</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?=htmlspecialchars($cookie_email)?>" required />
            <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
        </div>
        <div class="col-md-6">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" pattern="\d{9,15}" required />
            <div class="invalid-feedback">Số điện thoại phải từ 9 đến 15 chữ số.</div>
        </div>
        <div class="col-md-12">
            <label for="address" class="form-label">Địa chỉ giao hàng</label>
            <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
            <div class="invalid-feedback">Vui lòng nhập địa chỉ.</div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng / Xác nhận đặt hàng</button>
        </div>
    </form>

    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0 && !$success): ?>
        <hr>
        <h4>Giỏ hàng hiện tại</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tên sách</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sum = 0;
                foreach ($_SESSION['cart'] as $item): 
                    $item_total = $item['price'] * $item['quantity'];
                    $sum += $item_total;
                ?>
                <tr>
                    <td><?=htmlspecialchars($item['title'])?></td>
                    <td><?=number_format($item['price'])?> đ</td>
                    <td><?=$item['quantity']?></td>
                    <td><?=number_format($item_total)?> đ</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Tổng tiền</th>
                    <th><?=number_format($sum)?> đ</th>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
  'use strict';
  $('.needs-validation').on('submit', function(e) {
    if (!this.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
    }
    $(this).addClass('was-validated');
  });
});
</script>

</body>
</html>
