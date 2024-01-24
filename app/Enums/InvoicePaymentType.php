<?php
namespace App\Enums;
class InvoicePaymentType extends AbstractEnum
{
    const TRANSFER = 'transfer';
    const CASH = 'cash';
    const CHEQUE = 'cheque';
    const WALLET = 'wallet';

}
