<?php
namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $now   = now();

        // Lấy user_id của admin
        $admin = DB::table('users')->where('user_email', 'admin@gmail.com')->first();

        if ($admin) {
            foreach (range(1, 117) as $index) {
                // created_at từ 1 năm trước đến hiện tại
                $created_at = $faker->dateTimeBetween('-1 year', 'now');

                // deadline: từ hiện tại -1 tháng đến +3 tháng
                $deadline = $faker->dateTimeBetween('-1 month', '+3 month');

                // updated_at nằm giữa created_at và hiện tại
                $updated_at = $faker->dateTimeBetween($created_at, $now);

                DB::table('tasks')->insert([
                    'user_id'          => $admin->user_id,
                    'task_title'       => substr($faker->sentence(6, true), 0, 50),
                    'task_description' => substr($faker->paragraph(3, true), 0, 255),
                    'task_deadline'    => $deadline,
                    'priority'         => $faker->randomElement(['low', 'medium', 'high']),
                    'is_completed'     => $faker->boolean(30),
                    'created_at'       => $created_at,
                    'updated_at'       => $updated_at,
                ]);
            }
        } else {
            $this->command->warn("⚠️ Không tìm thấy user có email = admin@gmail.com. Vui lòng seed user trước.");
        }
    }
}