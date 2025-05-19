<?php
namespace XYZBank\Accounts;

use IteratorAggregate;
use ArrayIterator;

class AccountCollection implements IteratorAggregate
{
    /**
     * @var BankAccount[]
     */
    private array $accounts = [];

    public function addAccount(BankAccount $account): void
    {
        $this->accounts[] = $account;
        Bank::incrementAccountCount();
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->accounts);
    }

    /**
     * Lọc các tài khoản có số dư ≥ 10.000.000 VNĐ
     * @return BankAccount[]
     */
    public function filterHighBalanceAccounts(): array
    {
        return array_filter($this->accounts, function (BankAccount $acc) {
            return $acc->getBalance() >= 10_000_000;
        });
    }
}
