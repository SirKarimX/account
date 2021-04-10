<?php


namespace App;


class Commons
{
    public const BILL = 'bill';
    public const INVOICE = 'invoice';
    public const TRANSFER = 'transfer';
    public const ADJUST = 'adjust';

    public const TRANSACTION_TYPES = [
        Commons::BILL => 'Bill',
        Commons::INVOICE => 'Invoice',
        Commons::TRANSFER => 'Transfer',
        Commons::ADJUST => 'Adjust',
    ];

}
