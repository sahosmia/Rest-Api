<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Dummy data insert
        DB::table('blogs')->insert([
            [
                'title' => 'Building a REST API with Laravel',
                'category_id' => 1,
                'user_id' => 1,
                'photo' => 'blogs/laravel-rest-api.jpg',
                'description' => 'Learn how to build a powerful REST API using Laravel with best practices.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Mastering Tailwind CSS for Modern UI Design',
                'category_id' => 2,
                'user_id' => 1,
                'photo' => 'blogs/tailwind-css.jpg',
                'description' => 'Tailwind CSS is a utility-first CSS framework that helps you build custom UIs faster.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Getting Started with React and Laravel API',
                'category_id' => 3,
                'user_id' => 2,
                'photo' => 'blogs/react-laravel.jpg',
                'description' => 'Integrate React frontend with Laravel backend step by step.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
