<?php

namespace App\Http\Controllers\API\tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;

class DatabaseController extends Controller
{
    public function manageDatabase(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'create') {
            return $this->createDatabaseAndTables();
        } elseif ($action === 'refresh') {
            return $this->refreshDatabase();
        }

        return response()->json(['message' => 'Invalid action'], 400);
    }

    private function createDatabaseAndTables()
    {
        try {
            // گرفتن نام دیتابیس از config
            $databaseName = Config::get('database.connections.mysql.database');

            // تغییر موقتی به دیتابیس اصلی MySQL برای ایجاد دیتابیس
            Config::set('database.connections.mysql.database', null);
            DB::purge('mysql');

            // بررسی و ایجاد دیتابیس در صورت عدم وجود
            DB::statement("CREATE DATABASE IF NOT EXISTS `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            DB::statement("SET GLOBAL default_storage_engine=INNODB");

            // بازگرداندن تنظیمات دیتابیس به مقدار اصلی
            Config::set('database.connections.mysql.database', $databaseName);
            DB::purge('mysql');

            // اجرای migrate برای ساخت جداول
            Artisan::call('migrate');

            return response()->json([
                'message' => 'Database and tables created successfully!',
                'output' => Artisan::output(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function refreshDatabase()
    {
        try {
            // گرفتن نام دیتابیس از config
            $databaseName = Config::get('database.connections.mysql.database');

            // تغییر موقتی به دیتابیس اصلی برای حذف دیتابیس فعلی
            Config::set('database.connections.mysql.database', null);
            DB::purge('mysql');

            // حذف دیتابیس
            DB::statement("DROP DATABASE IF EXISTS `$databaseName`");

            // ایجاد مجدد دیتابیس
            DB::statement("CREATE DATABASE `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            DB::statement("SET GLOBAL default_storage_engine=INNODB");

            // بازگرداندن تنظیمات دیتابیس به مقدار اصلی
            Config::set('database.connections.mysql.database', $databaseName);
            DB::purge('mysql');

            // اجرای migrate:fresh برای ساخت جداول
            Artisan::call('migrate');

            return response()->json([
                'message' => 'Database recreated and tables migrated successfully!',
                'output' => Artisan::output(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
