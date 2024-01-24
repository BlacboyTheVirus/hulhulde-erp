<?php
namespace App\Enums;
class ProcurementPaymentType extends AbstractEnum
{
    const TRANSFER = 'transfer';
    const CASH = 'cash';
    const CHEQUE = 'cheque';
    const ADVANCE = 'advance';

}
