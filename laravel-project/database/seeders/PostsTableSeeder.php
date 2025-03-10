<?php

namespace Database\Seeders;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'name' => '鈴木イチロウ',
            'date' => Carbon::parse('2025-03-01 10:00:00'),
            'description' => 'Description for 鈴木イチロウ',
        ]);

        Post::create([
            'name' => '山田タロウ',
            'date' => Carbon::parse('2025-03-02 09:00:00'),
            'description' => 'Description for 山田タロウ',
        ]);

        Post::create([
            'name' => '伊藤シンジ',
            'date' => Carbon::parse('2025-03-03 11:00:00'),
            'description' => 'Description for 伊藤シンジ',
        ]);
    }
}
