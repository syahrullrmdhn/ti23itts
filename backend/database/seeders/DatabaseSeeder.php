<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Episode;
use App\Models\Semester;
use App\Models\Timeline;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@ti23itts.my.id',
        ], [
            'name' => 'Admin TI23',
            'password' => Hash::make('password123'),
        ]);

        User::updateOrCreate([
            'email' => 'syahrul@ti23itts.my.id',
        ], [
            'name' => 'syahrul',
            'password' => Hash::make('syahrulitts2026'),
        ]);

        Timeline::create([
            'initial_count' => 45,
            'current_count' => 38,
        ]);

        $semesters = [
            [
                'semester' => 1,
                'period' => 'Sep 2023 - Jan 2024',
                'icon' => '🎯',
                'student_count' => 45,
                'lecturers' => [
                    ['name' => 'Pak Budi Santoso', 'isAnomaly' => false],
                    ['name' => 'Bu Siti Rahma', 'isAnomaly' => false],
                    ['name' => 'Pak Anomali Legend', 'isAnomaly' => true],
                ]
            ],
            [
                'semester' => 2,
                'period' => 'Feb 2024 - Jun 2024',
                'icon' => '🚀',
                'student_count' => 43,
                'lecturers' => [
                    ['name' => 'Pak Ahmad Wijaya', 'isAnomaly' => false],
                    ['name' => 'Bu Rina Dewi', 'isAnomaly' => false],
                ]
            ],
            [
                'semester' => 3,
                'period' => 'Sep 2024 - Jan 2025',
                'icon' => '⚡',
                'student_count' => 41,
                'lecturers' => [
                    ['name' => 'Pak Hendra', 'isAnomaly' => false],
                    ['name' => 'Bu Lina', 'isAnomaly' => false],
                ]
            ],
            [
                'semester' => 4,
                'period' => 'Feb 2025 - Jun 2025',
                'icon' => '🎨',
                'student_count' => 40,
                'lecturers' => [
                    ['name' => 'Pak Joko', 'isAnomaly' => false],
                    ['name' => 'Bu Maya', 'isAnomaly' => false],
                ]
            ],
            [
                'semester' => 5,
                'period' => 'Sep 2025 - Jan 2026',
                'icon' => '🔥',
                'student_count' => 39,
                'lecturers' => [
                    ['name' => 'Pak Rizal', 'isAnomaly' => false],
                    ['name' => 'Bu Dina', 'isAnomaly' => false],
                ]
            ],
            [
                'semester' => 6,
                'period' => 'Feb 2026 - Jun 2026',
                'icon' => '🎓',
                'student_count' => 38,
                'lecturers' => [
                    ['name' => 'Pak Eko', 'isAnomaly' => false],
                    ['name' => 'Bu Sari', 'isAnomaly' => false],
                ]
            ],
        ];

        foreach ($semesters as $semester) {
            Semester::create($semester);
        }

        $episodes = [
            [
                'category' => 'ZOOM FAILS',
                'title' => 'Monkey Business',
                'image' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Tragedi Willy TI di kebon dikejar monyet pas absen Zoom',
                'full_description' => 'Ini adalah momen legendaris di mana Willy lagi di kebun pas Zoom class, tiba-tiba dikejar monyet sambil teriak-teriak. Semua mahasiswa dan dosen langsung ngakak ngeliat kejadian ini. Video-nya bahkan sempat viral di group WhatsApp angkatan dan jadi meme internal.',
                'date' => 'Oktober 2023',
                'participants' => 'Willy & The Monkey'
            ],
            [
                'category' => 'ZOOM FAILS',
                'title' => 'Leaked Jidat',
                'image' => 'https://images.unsplash.com/photo-1520182205149-1e5e4e7329b4?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Insiden Dhila yang legend',
                'full_description' => 'Momen ketika Dhila lupa nyalain kamera dengan posisi yang... unik. Jidat cemerlang memenuhi layar Zoom, bikin semua orang nahan ketawa. Sejak saat itu, Dhila jadi icon angkatan dengan julukan "Si Jidat Terang".',
                'date' => 'November 2023',
                'participants' => 'Dhila'
            ],
            [
                'category' => 'ZOOM FAILS',
                'title' => 'The Naked Truth',
                'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Insiden Nico habis mandi pas Zoom',
                'full_description' => 'Nico lupa matiin kamera pas habis mandi. Untungnya cuma keliatan bahu ke atas, tapi cukup bikin heboh satu kelas. Dosen sampai harus pause kuliah sebentar karena semua chat box penuh emoji surprised.',
                'date' => 'Desember 2023',
                'participants' => 'Nico'
            ],
            [
                'category' => 'ZOOM MYSTERY',
                'title' => 'Symphony of Zoom',
                'image' => 'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Misteri siapa yang ngorok pas kelas online',
                'full_description' => 'Ada suara ngorok misterius yang sering muncul di tengah kuliah Zoom. Sampai sekarang belum ketahuan siapa pelakunya. Dosen pernah bilang "Tolong yang tidur matiin mic-nya", tapi suara ngorok tetap terdengar. Plot twist: mungkin dosennya sendiri?',
                'date' => 'Januari 2024',
                'participants' => 'Unknown Legend'
            ],
            [
                'category' => 'DRAMA',
                'title' => 'Surat Terbuka Moment',
                'image' => 'https://images.unsplash.com/photo-1577563908411-5077b6dc7624?auto=format&fit=crop&w=800&q=80',
                'short_description' => 'Arsip momen-momen spicy surat terbuka',
                'full_description' => 'Koleksi surat terbuka legendaris angkatan. Dari yang bikin baper, bikin heboh, sampai yang bikin tercengang. Ini adalah dokumentasi drama terbaik sepanjang masa TI 23. (Detail dirahasiakan untuk keamanan bersama)',
                'date' => 'Various Dates',
                'participants' => 'Multiple Players'
            ]
        ];

        foreach ($episodes as $episode) {
            Episode::create($episode);
        }

    }
}
