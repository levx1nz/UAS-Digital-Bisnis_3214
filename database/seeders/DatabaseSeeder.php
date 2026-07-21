<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'superadmin@amikom.ac.id'], [
            'name' => 'Super Admin', 'password' => bcrypt('password'),
            'role' => 'superadmin', 'account_status' => 'approved',
        ]);

        User::firstOrCreate(['email' => 'admin@amikom.ac.id'], [
            'name' => 'Admin Amikom', 'password' => bcrypt('password'),
            'role' => 'admin', 'account_status' => 'approved',
        ]);

        $hmif = User::firstOrCreate(['email' => 'hmif@amikom.ac.id'], [
            'name' => 'Ketua HMIF', 'organizer_name' => 'HIMA Informatika',
            'password' => bcrypt('password'), 'role' => 'organizer', 'account_status' => 'approved',
        ]);

        User::firstOrCreate(['email' => 'bemkm@amikom.ac.id'], [
            'name' => 'Ketua BEM', 'organizer_name' => 'BEM KM Amikom',
            'password' => bcrypt('password'), 'role' => 'organizer', 'account_status' => 'pending',
        ]);

        User::firstOrCreate(['email' => 'user@amikom.ac.id'], [
            'name' => 'Mahasiswa Amikom', 'password' => bcrypt('password'),
            'role' => 'user', 'account_status' => 'approved',
        ]);

        $category  = Category::firstOrCreate(['slug' => 'seminar-it'],  ['name' => 'Seminar IT']);
        $category2 = Category::firstOrCreate(['slug' => 'entertaiment'], ['name' => 'Entertaiment']);
        $category3 = Category::firstOrCreate(['slug' => 'esport'],       ['name' => 'Esport']);
        $category4 = Category::firstOrCreate(['slug' => 'konser'],       ['name' => 'Konser']);
        $category5 = Category::firstOrCreate(['slug' => 'game-dev'],     ['name' => 'Game Dev']);

        $events = [
            ['category_id' => $category2->id, 'title' => 'Jazz Night 2025', 'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.', 'date' => '2026-05-10 19:00:00', 'location' => 'Amikom Baru', 'price' => 50000, 'stock' => 100, 'poster_path' => 'posters/event-1.png'],
            ['category_id' => $category->id, 'title' => 'Hackaton - Unleash Your Inner Developer', 'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif!', 'date' => '2026-05-05 10:00:00', 'location' => 'Inkubator Amikom', 'price' => 50000, 'stock' => 100, 'poster_path' => 'posters/event-2.png'],
            ['category_id' => $category->id, 'title' => 'AI & FUTURE TECH SUMMIT 2026', 'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan.', 'date' => '2026-05-01 13:00:00', 'location' => 'Cinema Unit 6', 'price' => 50000, 'stock' => 100, 'poster_path' => 'posters/event-3.png'],
            ['category_id' => $category3->id, 'title' => 'Valorant Nusantara Championship 2026', 'description' => 'Pertarungan sengit tim-tim Valorant terbaik se-Indonesia.', 'date' => '2026-06-15 10:00:00', 'location' => 'Universitas Amikom, Yogyakarta', 'price' => 150000, 'stock' => 5000, 'poster_path' => 'posters/event-1.png'],
            ['category_id' => $category3->id, 'title' => 'MLBB M8 World Championship Watch Party', 'description' => 'Nonton bareng tim perwakilan Indonesia di ajang kejuaraan dunia Mobile Legends.', 'date' => '2026-12-10 15:00:00', 'location' => 'Universitas Amikom, Yogyakarta', 'price' => 35000, 'stock' => 500, 'poster_path' => 'event-2.png'],
            ['category_id' => $category4->id, 'title' => 'Sheila On 7 - Tunggu Aku di Yogyakarta', 'description' => 'Konser reuni spesial band legendaris Indonesia.', 'date' => '2026-07-20 19:00:00', 'location' => 'Universitas Amikom, Yogyakarta', 'price' => 350000, 'stock' => 10000, 'poster_path' => 'event-1.png'],
            ['category_id' => $category4->id, 'title' => 'Tulus - Intimate Tour 2026', 'description' => 'Malam syahdu bersama Tulus dengan aransemen akustik yang intim.', 'date' => '2026-09-05 20:00:00', 'location' => 'Universitas Amikom, Yogyakarta', 'price' => 750000, 'stock' => 1000, 'poster_path' => 'event-2.png'],
            ['category_id' => $category5->id, 'title' => 'Indie Game Developer Gathering 2026', 'description' => 'Wadah bertemunya para developer game indie untuk networking & showcase.', 'date' => '2026-08-10 09:00:00', 'location' => 'Universitas Amikom, Yogyakarta', 'price' => 75000, 'stock' => 300, 'poster_path' => 'event-3.png'],
            ['category_id' => $category5->id, 'title' => 'Global Game Jam - Jakarta Chapter', 'description' => 'Tantang dirimu membuat game dalam 48 jam!', 'date' => '2026-01-25 17:00:00', 'location' => 'Universitas Amikom, Yogyakarta', 'price' => 50000, 'stock' => 150, 'poster_path' => 'event-3.png'],
        ];

        foreach ($events as $data) {
            Event::firstOrCreate(['title' => $data['title']], array_merge($data, [
                'organizer_id' => $hmif->id,
                'is_published' => true,
            ]));
        }
    }
}