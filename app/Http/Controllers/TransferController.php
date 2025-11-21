<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function create()
    {
        //Load accounts via Raw SQL
        $accounts = DB::select('SELECT id, number, balance FROM accounts ORDER BY number ASC');
        return view('transfer.create', compact('accounts'));

    }
    public function store(Request $request) //$request contains all the data the user sent from the form
    {
        //Validate input
        $data = $request->validate([
           'from_account_id' => ['required', 'integer', 'different:to_account_id'],
           'to_account_id' => ['required', 'integer'],
           'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        try {

            DB::transaction(function() use ($data) {
               //Lock rows for update to ensure isolation (MySQL syntax)
                //Find account that's sending money
                //Get its balance, LOCK IT
                //Find account that's receiving money
                //Get its balance
                //LOCK it too
                $from = DB::select('SELECT id, balance FROM accounts WHERE id = ? FOR UPDATE', [$data['from_account_id']]);
                $to = DB::select('SELECT id, balance FROM accounts WHERE id = ? FOR UPDATE', [$data['to_account_id']]);

                if(empty($from) || empty($to)) {
                    throw ValidationException::withMessages(['accounts' => 'Invalid account(s).']);
                }

                $fromBalance = (float)$from[0]->balance;
                $amount = (float) $data['amount'];

                if($fromBalance < $amount) {
                    // Record failed transaction
                    DB::insert(
                        'INSERT INTO transactions (from_account_id, to_account_id, amount, status, created_at, updated_at) VALUES (?,?,?,?, NOW(), NOW())',
                    [$data['from_account_id'], $data['to_account_id'], $amount, 'failed']
                    );
                    throw ValidationException::withMessages(['amount' => 'Insufficient balance']);

                }
                    // Debit from 'from' account
                    DB::update('UPDATE accounts SET balance = balance - ? WHERE id = ?',
                    [$amount, $data['from_account_id']]);

                    // Credit 'to' account
                    DB::update('UPDATE accounts SET balance = balance + ? WHERE id = ?',
                    [$amount, $data['to_account_id']]
                    );

                    // Record success transaction
                    DB::insert(
                      'INSERT into transactions (from_account_id, to_account_id, amount, status, created_at, updated_at) VALUES (?,?,?,?,NOW(), NOW())',
                      [$data['from_account_id'], $data['to_account_id'], $amount, 'success']
                    );
            });

            return redirect()->route('transfer.create')->with('status', 'Transfer completed successfully');
        } catch(ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch(\Throwable $e) {
            //Optional: log error
            return back()->with('error', 'Transfer failed. Please try again.')->withInput();
        }
    }
}
