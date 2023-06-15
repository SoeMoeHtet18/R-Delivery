<?php

// Bootstrap Laravel
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Define the admin account
$adminAccount = '09123456789';

// Delete data from other tables
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Delete data from tables except the users table
$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = $table->{'Tables_in_'.env('DB_DATABASE')};
    if ($tableName !== 'users') {
        DB::table($tableName)->truncate();
    }
}

// Delete data from the users table except the admin account
DB::table('users')->where('phone_number', '!=', $adminAccount)->delete();

DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "Data deletion complete.\n";
