<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class TransactionController
{

    public function __construct(private readonly TransactionService $transactionService)
    {
    }


    public function index(Request $request):string
    {
        echo $request->headers->get('X-Request-Id') . '<br />';
        return 'Transaction List    ';

    }

    public function show($tid, $fid=1):string
    {
        return 'Get transaction id: ' . $tid . 'and file id: ' . $fid;
    }

    public function create():string
    {
        return 'Form to create transaction';
    }

    public function store(Request $request):string
    {
        return 'Transaction Created';
    }

    public function documents():string
    {
        return 'Transaction Documents';
    }

    public static function middleware()
    {
        return [

        ];
    }
}
