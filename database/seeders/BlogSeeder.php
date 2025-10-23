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
                'slug' => 'building-a-rest-api-with-laravel',
                'category_id' => 1,
                'user_id' => 1,
                'photo' => 'blogs/laravel-rest-api.jpg',
                'description' => 'Learn how to build a powerful REST API using Laravel with best practices.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Mastering Tailwind CSS for Modern UI Design',
                'slug' => 'mastering-tailwind-css-for-modern-ui-design',
                'category_id' => 2,
                'user_id' => 1,
                'photo' => 'blogs/tailwind-css.jpg',
                'description' => 'Tailwind CSS is a utility-first CSS framework that helps you build custom UIs faster.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Getting Started with React and Laravel API',
                'slug' => 'getting-started-with-react-and-laravel-api',
                'category_id' => 3,
                'user_id' => 2,
                'photo' => 'blogs/react-laravel.jpg',
                'description' => 'Integrate React frontend with Laravel backend step by step.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Deploying Laravel Applications on AWS',
                'slug' => 'deploying-laravel-applications-on-aws',
                'category_id' => 4,
                'user_id' => 2,
                'photo' => 'blogs/laravel-aws.jpg',
                'description' => 'A comprehensive guide to deploying Laravel applications on Amazon Web Services.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Understanding Eloquent ORM in Laravel',
                'slug' => 'understanding-eloquent-orm-in-laravel',
                'category_id' => 1,
                'user_id' => 1,
                'photo' => 'blogs/eloquent-orm.jpg',
                'description' => 'Eloquent ORM is Laravel\'s built-in ORM that makes database interactions simple and elegant.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Building Real-time Applications with Laravel and Pusher',
                'slug' => 'building-real-time-applications-with-laravel-and-pusher',
                'category_id' => 5,
                'user_id' => 3,
                'photo' => 'blogs/laravel-pusher.jpg',
                'description' => 'Learn how to create real-time applications using Laravel and Pusher for instant updates.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
