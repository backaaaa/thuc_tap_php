<?php
const COMMISSION_RATE = 0.2; 
const VAT_RATE = 0.1;    

$tenChienDich = "Spring Sale 2025";
$loaiSanPham = "Thời trang";
$giaSanPham = 99.99;
$soLuongDonHang = 150;
$trangThaiChienDich = true; 

$maDon = ["ID001", "ID002", "ID003", "ID004", "ID005"];
$giaTriDon = [99.99, 49.99, 149.99, 89.99, 109.99];

$doanhThu = 0;
for ($i = 0; $i < count($giaTriDon); $i++) {
    $doanhThu += $giaTriDon[$i];
}

$hoaHong = $doanhThu * COMMISSION_RATE;
$thueVAT = $doanhThu * VAT_RATE;
$loiNhuan = $doanhThu - $hoaHong - $thueVAT;

echo "<h2> PHÂN TÍCH CHIẾN DỊCH</h2>";
echo "<b>Tên chiến dịch:</b> $tenChienDich<br>";
echo "<b>Trạng thái:</b> " . ($trangThaiChienDich ? "Đã kết thúc" : "Đang chạy") . "<br>";
echo "<b>Loại sản phẩm:</b> $loaiSanPham<br><br>";

echo "<b> Tổng doanh thu:</b> " . number_format($doanhThu, 2) . " USD<br>";
echo "<b> Chi phí hoa hồng (20%):</b> " . number_format($hoaHong, 2) . " USD<br>";
echo "<b> Thuế VAT (10%):</b> " . number_format($thueVAT, 2) . " USD<br>";
echo "<b> Lợi nhuận:</b> " . number_format($loiNhuan, 2) . " USD<br><br>";

echo "<b> Hiệu quả chiến dịch:</b> ";
if ($loiNhuan > 0) {
    echo "Chiến dịch thành công<br>";
} elseif ($loiNhuan == 0) {
    echo "Chiến dịch hòa vốn<br>";
} else {
    echo "Chiến dịch thất bại<br>";
}

echo "<b> Gợi ý:</b> ";
switch ($loaiSanPham) {
    case "Thời trang":
        echo "Sản phẩm Thời trang có doanh thu ổn định.<br>";
        break;
    case "Điện tử":
        echo "Sản phẩm Điện tử có biến động lớn, cần theo dõi sát.<br>";
        break;
    case "Gia dụng":
        echo "Sản phẩm Gia dụng phù hợp với các chiến dịch dài hạn.<br>";
        break;
    default:
        echo "Không rõ loại sản phẩm.<br>";
        break;
}

echo "<br><b> Danh sách đơn hàng:</b><br>";
for ($i = 0; $i < count($maDon); $i++) {
    echo "Đơn " . $maDon[$i] . ": " . number_format($giaTriDon[$i], 2) . " USD<br>";
}

echo "<br><b> Thông báo:</b> Chiến dịch <b>$tenChienDich</b> đã ";
echo $trangThaiChienDich ? "kết thúc" : "chưa kết thúc";
echo " với lợi nhuận: <b>" . number_format($loiNhuan, 2) . " USD</b><br>";

?>
