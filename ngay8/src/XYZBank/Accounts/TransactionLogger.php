<?php
namespace XYZBank\Accounts;

trait TransactionLogger
{
    protected function logTransaction(string $type, float $amount, float $newBalance): void
    {
        $time = date('Y-m-d H:i:s');
        $amountFormatted = number_format($amount, 0, ',', '.');
        $balanceFormatted = number_format($newBalance, 0, ',', '.');
        echo "[$time] Giao dịch: $type $amountFormatted VNĐ | Số dư mới: $balanceFormatted VNĐ<br>";
    }
}
