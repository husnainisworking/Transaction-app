<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Raw SQL inserts
        DB::insert('INSERT INTO users (name, email, created_at, updated_at) VALUES (?,?,NOW(),NOW())',['Ali', 'ali@example.com']);
        DB::insert('INSERT INTO users (name, email, created_at, updated_at) VALUES (?,?, NOW(), NOW())', ['Sara','sara@example.com']);

        //Fetch IDs (Raw SQL)
        $ali = DB::select('SELECT id FROM users WHERE email = ?', ['ali@example.com'])[0]->id;
        $sara = DB::select('SELECT id FROM users WHERE email = ?', ['sara@example.com'])[0]->id;

        //Accounts
        DB::insert('INSERT INTO accounts (user_id, number, balance, created_at, updated_at) VALUES(?,?,?, NOW(), NOW())', [$ali, 'ACC-ALI-001', 1000.00]);
        DB::insert('INSERT INTO accounts (user_id, number, balance, created_at, updated_at) VALUES(?, ?,?,  NOW(), NOW())', [$sara, 'ACC-SARA-001', 500.00]);

    }
}
