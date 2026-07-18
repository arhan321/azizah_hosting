<?php

namespace App\Constants;

class OrderStatus
{
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const DIKERJAKAN = 'dikerjakan';
    const SELESAI = 'selesai';

    public static function all()
    {
        return [
            self::PENDING,
            self::APPROVED,
            self::DIKERJAKAN,
            self::SELESAI,
        ];
    }
}

class OrderType
{
    const CATALOG = 'catalog';
    const CUSTOM = 'custom';

    public static function all()
    {
        return [
            self::CATALOG,
            self::CUSTOM,
        ];
    }
}

class PaymentType
{
    const FULL = 'full';
    const DP = 'dp';

    public static function all()
    {
        return [
            self::FULL,
            self::DP,
        ];
    }
}

class PaymentStatus
{
    const UNPAID = 'unpaid';
    const DP_PAID = 'dp_paid';
    const FULLY_PAID = 'fully_paid';

    public static function all()
    {
        return [
            self::UNPAID,
            self::DP_PAID,
            self::FULLY_PAID,
        ];
    }
}

class UserRole
{
    const ADMIN = 'admin';
    const CUSTOMER = 'customer';

    public static function all()
    {
        return [
            self::ADMIN,
            self::CUSTOMER,
        ];
    }
}

class NotificationStatus
{
    const PENDING = 'pending';
    const SENT = 'sent';
    const FAILED = 'failed';

    public static function all()
    {
        return [
            self::PENDING,
            self::SENT,
            self::FAILED,
        ];
    }
}

class BankAccount
{
    const BANK_NAME = 'Bank BCA';
    const ACCOUNT_NUMBER = '1234567890';
    const ACCOUNT_HOLDER = 'AQLAM MURAL';

    public static function getInfo()
    {
        return [
            'bank_name' => self::BANK_NAME,
            'account_number' => self::ACCOUNT_NUMBER,
            'account_holder' => self::ACCOUNT_HOLDER,
        ];
    }
}
