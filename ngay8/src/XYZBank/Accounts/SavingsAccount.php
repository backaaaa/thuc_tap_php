<?php
namespace XYZBank\Accounts;

class SavingsAccount extends BankAccount implements InterestBearing
{
    use TransactionLogger;

    private const INTEREST_RATE = 0.05;
    private const MIN_BALANCE = 1_000_000;

    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Số tiền gửi phải lớn hơn 0");
        }
        $this->balance += $amount;
        $this->logTransaction('Gửi tiền', $amount, $this->balance);
    }

    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Số tiền rút phải lớn hơn 0");
        }
        if (($this->balance - $amount) < self::MIN_BALANCE) {
            throw new \Exception("Không thể rút vì số dư sau giao dịch phải ≥ " . number_format(self::MIN_BALANCE, 0, ',', '.') . " VNĐ");
        }
        $this->balance -= $amount;
        $this->logTransaction('Rút tiền', $amount, $this->balance);
    }

    public function calculateAnnualInterest(): float
    {
        return $this->balance * self::INTEREST_RATE;
    }

    public function getAccountType(): string
    {
        return 'Tiết kiệm';
    }
}
