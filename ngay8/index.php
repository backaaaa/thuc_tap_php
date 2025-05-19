<?php
require_once 'src/XYZBank/Accounts/BankAccount.php';
require_once 'src/XYZBank/Accounts/InterestBearing.php';
require_once 'src/XYZBank/Accounts/TransactionLogger.php';
require_once 'src/XYZBank/Accounts/SavingsAccount.php';
require_once 'src/XYZBank/Accounts/CheckingAccount.php';
require_once 'src/XYZBank/Accounts/Bank.php';
require_once 'src/XYZBank/Accounts/AccountCollection.php';

use XYZBank\Accounts\SavingsAccount;
use XYZBank\Accounts\CheckingAccount;
use XYZBank\Accounts\AccountCollection;
use XYZBank\Accounts\Bank;

echo "Khởi tạo tài khoản <br>";

$savings = new SavingsAccount('10201122', 'Nguyễn Thị A', 20_000_000);

$checking1 = new CheckingAccount('20301123', 'Lê Văn B', 8_000_000);
$checking2 = new CheckingAccount('20401124', 'Trần Minh C', 12_000_000);

$accounts = new AccountCollection();
$accounts->addAccount($savings);
$accounts->addAccount($checking1);
$accounts->addAccount($checking2);

$checking1->deposit(5_000_000);
$checking2->withdraw(2_000_000);

$interest = $savings->calculateAnnualInterest();
echo "Lãi suất hàng năm cho {$savings->getOwnerName()}: " . number_format($interest, 0, ',', '.') . " VNĐ<br>";

foreach ($accounts as $acc) {
    echo sprintf(
        "Tài khoản: %s | %s | Loại: %s | Số dư: %s VNĐ<br>",
        $acc->getAccountNumber(),
        $acc->getOwnerName(),
        $acc->getAccountType(),
        number_format($acc->getBalance(), 0, ',', '.')
    );
}

// In tổng số tài khoản
echo "Tổng số tài khoản đã tạo: " . Bank::getTotalAccounts() . "<br>";

// In tên ngân hàng
echo "Tên ngân hàng: " . Bank::getBankName() . "<br>";
