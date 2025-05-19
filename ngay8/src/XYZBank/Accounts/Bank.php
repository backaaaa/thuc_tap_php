<?php
namespace XYZBank\Accounts;

class Bank
{
    private static int $totalAccounts = 0;

    public static function incrementAccountCount(): void
    {
        self::$totalAccounts++;
    }

    public static function getTotalAccounts(): int
    {
        return self::$totalAccounts;
    }

    public static function getBankName(): string
    {
        return 'Ngân hàng XYZ';
    }
}
