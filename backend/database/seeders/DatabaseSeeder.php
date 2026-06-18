<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Episode;
use App\Models\Semester;
use App\Models\Timeline;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin TI23',
            'email' => 'admin@ti23itts.my.id',
            'password' => Hash::make('password123'),
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
                'icon' => '🐒',
                'gradient_color' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'short_description' => 'Tragedi Willy TI di kebon dikejar monyet pas absen Zoom',
                'full_description' => 'Ini adalah momen legendaris di mana Willy lagi di kebun pas Zoom class, tiba-tiba dikejar monyet sambil teriak-teriak. Semua mahasiswa dan dosen langsung ngakak ngeliat kejadian ini. Video-nya bahkan sempat viral di group WhatsApp angkatan dan jadi meme internal.',
                'date' => 'Oktober 2023',
                'participants' => 'Willy & The Monkey'
            ],
            [
                'category' => 'ZOOM FAILS',
                'title' => 'Leaked Jidat',
                'icon' => '💡',
                'gradient_color' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'short_description' => 'Insiden Dhila yang legend',
                'full_description' => 'Momen ketika Dhila lupa nyalain kamera dengan posisi yang... unik. Jidat cemerlang memenuhi layar Zoom, bikin semua orang nahan ketawa. Sejak saat itu, Dhila jadi icon angkatan dengan julukan "Si Jidat Terang".',
                'date' => 'November 2023',
                'participants' => 'Dhila'
            ],
            [
                'category' => 'ZOOM FAILS',
                'title' => 'The Naked Truth',
                'icon' => '🚿',
                'gradient_color' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'short_description' => 'Insiden Nico habis mandi pas Zoom',
                'full_description' => 'Nico lupa matiin kamera pas habis mandi. Untungnya cuma keliatan bahu ke atas, tapi cukup bikin heboh satu kelas. Dosen sampai harus pause kuliah sebentar karena semua chat box penuh emoji surprised.',
                'date' => 'Desember 2023',
                'participants' => 'Nico'
            ],
            [
                'category' => 'ZOOM MYSTERY',
                'title' => 'Symphony of Zoom',
                'icon' => '💤',
                'gradient_color' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                'short_description' => 'Misteri siapa yang ngorok pas kelas online',
                'full_description' => 'Ada suara ngorok misterius yang sering muncul di tengah kuliah Zoom. Sampai sekarang belum ketahuan siapa pelakunya. Dosen pernah bilang "Tolong yang tidur matiin mic-nya", tapi suara ngorok tetap terdengar. Plot twist: mungkin dosennya sendiri?',
                'date' => 'Januari 2024',
                'participants' => 'Unknown Legend'
            ],
            [
                'category' => 'DRAMA',
                'title' => 'Surat Terbuka Moment',
                'icon' => '✉️',
                'gradient_color' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
                'short_description' => 'Arsip momen-momen spicy surat terbuka',
                'full_description' => 'Koleksi surat terbuka legendaris angkatan. Dari yang bikin baper, bikin heboh, sampai yang bikin tercengang. Ini adalah dokumentasi drama terbaik sepanjang masa TI 23. (Detail dirahasiakan untuk keamanan bersama)',
                'date' => 'Various Dates',
                'participants' => 'Multiple Players'
            ]
        ];

        foreach ($episodes as $episode) {
            Episode::create($episode);
        }

        $students = [
            [
                'name' => 'Willy Kusuma',
                'role' => 'Korban Monyet 🐒',
                'status' => 'Aktif',
                'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop',
                'aib_photo' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=400&h=400&fit=crop',
                'fun_fact' => 'Pernah dikejar monyet pas zoom'
            ],
            [
                'name' => 'Dhila Permata',
                'role' => 'Si Jidat Terang 💡',
                'status' => 'Aktif',
                'photo' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=400&fit=crop',
                'aib_photo' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop',
                'fun_fact' => 'Jidat legendaris'
            ],
            [
                'name' => 'Nico Pratama',
                'role' => 'Mr. Fresh 🚿',
                'status' => 'Aktif',
                'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop',
                'aib_photo' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=400&fit=crop',
                'fun_fact' => 'Lupa matiin kamera habis mandi'
            ],
            [
                'name' => 'Andi Wijaya',
                'role' => 'Si Paling Rajin 📚',
                'status' => 'Aktif',
                'photo' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop',
                'aib_photo' => 'https://images.unsplash.com/photo-1463453091185-61582044d556?w=400&h=400&fit=crop',
                'fun_fact' => 'Selalu ngumpulin duluan'
            ],
            [
                'name' => 'Siti Rahmawati',
                'role' => 'Tukang Tidur 💤',
                'status' => 'Aktif',
                'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop',
                'aib_photo' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=400&h=400&fit=crop',
                'fun_fact' => 'Tidur di semua kelas'
            ],
            [
                'name' => 'Budi Santoso',
                'role' => 'The Debugger 🐛',
                'status' => 'Aktif',
                'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&h=400&fit=crop',
                'aib_photo' => 'https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?w=400&h=400&fit=crop',
                'fun_fact' => 'Bisa fix bug apapun'
            ],
            [
                'name' => 'Maya Anggraini',
                'role' => 'Queen of UI 👑',
                'status' => 'Aktif',
                'photo' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=400&h=400&fit=crop',
                'aib_photo' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=400&h=400&fit=crop',
                'fun_fact' => 'Desain selalu on point'
            ],
            [
                'name' => 'Reza Firmansyah',
                'role' => 'Tukang Copas 📋',
                'status' => 'Aktif',
                'photo' => 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=400&h=400&fit=crop',
                'aib_photo' => 'https://images.unsplash.com/photo-1489980557514-251d61e3eeb6?w=400&h=400&fit=crop',
                'fun_fact' => 'Stack Overflow adalah teman'
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
