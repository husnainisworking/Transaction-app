<!-- resources/views/transfer/create.blade.php -->

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Transfer Money</title>
    <style>
            body { font-family: sans-serif; margin: 2rem; }
            form { max-width: 420px; display: grid; gap: 12px; }
            select, input { padding: 8px; }
            .status { margin-bottom: 12px; color: green; }
            .error { margin-bottom: 12px; color:red;}
    </style>
    <link rel="stylesheet" href="{{asset('css/transfer.css')}}">
</head>

        <h1> Transfer Money </h1>
    @if(session('status')) <!-- session() is laravel way to store temporary data that lasts for one page load.-->
        <div class="status">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif
    @if($errors->any()) <!-- if validate() fails -->
        <div class="error">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('transfer.store') }}">
        @csrf

        <label>From account</label>
        <select name="from_account_id" required>
            <option value="">Select</option> <!-- shows select as the first option with no value , looks like [ Select ▼ ]-->
            @foreach($accounts as $acc) <!-- goes through each account in the database,
old() do what ? it remembers the user selected before, if the user previously selected it , keep
it selected.
old('from_account_id') == $acc->id  , checks if this account user selected before?
? 'selected' , if yes -> add 'selected'
: ' ' if NO -> add nothing.
-->
                <option value="{{ $acc->id }}" {{ old('from_account_id') == $acc->id ? 'selected' : ''}}>
                {{ $acc->number }} (Balance : {{number_format($acc->balance, 2)}})
                </option>
                @endforeach
        </select>

        <label>To account</label>
        <select name="to_account_id" required>
            <option value="">Select</option>
            @foreach($accounts as $acc)
                <option value="{{ $acc->id }}" {{ old('to_account_id') == $acc->id ? 'selected' : ''}}>
                {{ $acc->number }} (Balance: {{number_format($acc->balance, 2)}})
                </option>
            @endforeach
        </select>

        <label>Amount</label>
        <input type="number" name="amount" step="0.01" min="0.01" value="{{old('amount')}}" required />
        <!--
        - input type="number" creates a number input box
        - looks like:
┌─────────────┐
│ 100      ▲  │
│          ▼  │
└─────────────┘
        name="amount"
        When form is submitted, this data is sent as 'amount'

        step=0.01
        - allows decimals up to 2 places
        - can enter: 100.00, 50.50, 25.99 ✅
        - Can't enter: 100.001 ❌ (3 decimals)

        min=0.01
        - minimum value is 0.01
        - can enter: 0.01, 1.00, 100.00

        -->
        <button type="submit">Transfer</button>
    </form>
    <hr/>

    <h2>Recent Transactions</h2>
    @php
    //Load recent transactions with raw SQL (simple inline for demo)
    $txs = \Illuminate\Support\Facades\DB::select(
        'SELECT t.id, a1.number AS from_num, a2.number AS to_num, t.amount, t.status, t.created_at
        FROM transactions t
        JOIN accounts a1 ON a1.id = t.from_account_id
        JOIN accounts a2 ON a2.id = t.to_account_id
        ORDER BY t.id DESC
        LIMIT 10'
        );
    //Loads the 10 most recent transactions from your database
    // A JOIN is just saying:
    // Take rows from one sheet and match them with rows from another
    // sheet where the IDs line up.

    // Imagine you want to see who sent money to whom.
    /**
*   Your transactions table only has IDs (from_account_id, to_account_id).
 *  That's not very human-friendly.
 *
 *  transactions t -> This is the main table we're selecting from.
 *  it has info like: who sent money(from_account_id), who received money
 *  (to_account_id), how much, status, and date.
 *
 *  The t is just a nickname(alias) so we can write t.id instead of transactions.id
 *
 *  JOIN accounts a1
 *  this is the accounts table used for sender's account.
 *
 *  a1 is a nickname for this copy of the accounts table
 *  It matches a1.id with t.from_account_id.
 *  That way we can show the sender's account number(a1.number).
 *
 *  JOIN accounts a2
 *  This is the same accounts table again, but used for receiver's account.
 *  a2 is a nickname for this second copy.
 *  It matches a2.id with t.to_account_id.
 *  That way we can show the receiver's account number(a2.number)
 *
 *  So in total:
 *  We are selecting from 1 main table: transactions
 *  And we are joining the same accounts table twice:
 *   one for sender(a1), once for receiver(a2).
 *
 *  Easy Flow:
 *  1. Start with the transactions table (t).
 *  2. For each transaction, look up the sender's account in accounts (a1).
 *  3. Look up the receiver's account in accounts (a2).
 *  4. Show the combined result: transaction ID, sender account number, receiver account number,
 *  amount, status and date.
 */
    @endphp

    <table border="1" cellpadding="8">
        <!-- tr is short for table row-->
        <tr>
            <th>ID</th>
            <th>From</th>
            <th>To</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        @foreach($txs as $tx)
            <tr>
                <!-- td = table data -->
                <td>{{$tx->id}}</td>
                <td>{{$tx->from_num}}</td>
                <td>{{$tx->to_num}}</td>
                <td>{{ number_format($tx->amount, 2) }}</td>
                <td>{{ucfirst($tx->status)}}</td>
                <!--Make the first character uppercase -->
                <td>{{$tx->created_at}}</td>
            </tr>
            @endforeach
        </table>
</body>
</html>






















