<?php

declare(strict_types = 1);

namespace App;

class PaymentService
{

    public function process():bool
    {
        echo "Paid" . PHP_EOL;

        return true;
    }
}
