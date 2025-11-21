<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProcessTransactionController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $transactionId)
    {
        return 'Processed  ' . $transactionId;

    }
}
