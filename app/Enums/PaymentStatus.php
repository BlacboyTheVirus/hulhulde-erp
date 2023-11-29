<?php
namespace App\Enums;
class PaymentStatus extends AbstractEnum
{
    const UNPAID = 'unpaid';
    const PARTIAL = 'partial';
    const PAID = 'paid';

}
