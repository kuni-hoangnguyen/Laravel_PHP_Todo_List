<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InitDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature   = 'db:init';
    protected $description = 'Khởi tạo database, chạy migrate và seed dữ liệu mẫu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dbName    = config('database.connections.mysql.database');
        $charset   = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

        try {
            // 1. Tạm bỏ database để kết nối MySQL root
            config(['database.connections.mysql.database' => null]);
            DB::purge('mysql'); // xóa kết nối cũ
            DB::reconnect('mysql');

            // 2. Tạo database nếu chưa có
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET $charset COLLATE $collation;");
            $this->info("✅ Database `$dbName` đã được tạo hoặc đã tồn tại.");

            // 3. Set lại database vừa tạo
            config(['database.connections.mysql.database' => $dbName]);
            DB::purge('mysql');     // xóa cache kết nối
            DB::reconnect('mysql'); // kết nối lại vào DB mới tạo

            // 4. Chạy migrate + seed
            $this->info("🔄 Đang chạy migrate + seed...");
            Artisan::call('migrate:fresh', ['--seed' => true]);
            $this->info(Artisan::output());

            $this->info("🎉 Database `$dbName` đã sẵn sàng với dữ liệu mẫu!");
        } catch (\Exception $e) {
            $this->error("❌ Lỗi: " . $e->getMessage());
        }
    }

}
