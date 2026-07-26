<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::insert([
            [
                'title' => 'Seminar Artificial Intelligence',
                'description' => 'Seminar pengenalan Artificial Intelligence dan penerapannya di dunia industri.',
                'location' => 'Auditorium Universitas',
                'date' => '2026-06-20',
                'time' => '09:00:00',
                'quota' => 150,
                'is_active' => 1,
                'banner' => 'events/6B9x6XHUV7mqpeZ9Lp4C2dX7dIwVgJMO7OUn9N3X.jpg',
            ],
            [
                'title' => 'Workshop UI/UX Design',
                'description' => 'Pelatihan desain antarmuka dan pengalaman pengguna menggunakan Figma.',
                'location' => 'Lab Multimedia',
                'date' => '2026-06-25',
                'time' => '08:00:00',
                'quota' => 100,
                'is_active' => 0,
                'banner' => 'events/to8Aw3DeNkESuCIoKJ8mRZY6RtVyxoWJ83vaiqo3.jpg',
            ],
            [
                'title' => 'Pelatihan Web Development',
                'description' => 'Pelatihan pengembangan website menggunakan React dan Laravel.',
                'location' => 'Lab Komputer 2',
                'date' => '2026-07-01',
                'time' => '08:30:00',
                'quota' => 120,
                'is_active' => 0,
                'banner' => 'events/NJ5nhtIVYXG7OyxxjNl63llxWv2TUMSGqdZsBCYq.jpg',
            ],
            [
                'title' => 'Seminar Cyber Security',
                'description' => 'Pembahasan keamanan siber, ethical hacking, dan keamanan data.',
                'location' => 'Aula Fakultas Teknik',
                'date' => '2026-07-05',
                'time' => '09:00:00',
                'quota' => 200,
                'is_active' => 0,
                'banner' => 'events/E0amrYhJT9sM7UqGKRkyXMjMr8SVHZ9vOrCXa0Cd.jpg',
            ],
            [
                'title' => 'Workshop Mobile Development',
                'description' => 'Pembuatan aplikasi Android menggunakan Flutter.',
                'location' => 'Lab Mobile Computing',
                'date' => '2026-07-10',
                'time' => '08:00:00',
                'quota' => 80,
                'is_active' => 0,
                'banner' => 'events/iX8pMeOZlkiS9UDCtxmEPbentSgEKGjDL5F76eKe.jpg',
            ],
            [
                'title' => 'Tech Talk Cloud Computing',
                'description' => 'Pengenalan teknologi cloud computing dan DevOps.',
                'location' => 'Ruang Seminar Gedung A',
                'date' => '2026-07-15',
                'time' => '10:00:00',
                'quota' => 180,
                'is_active' => 0,
                'banner' => 'events/ZAfo1ENZg4Z3fnKtrZ8EjgMEZDnlf97I0ugaQjh9.jpg',
            ],
            [
                'title' => 'Seminar Data Science',
                'description' => 'Pengolahan data dan machine learning menggunakan Python.',
                'location' => 'Auditorium Universitas',
                'date' => '2026-07-20',
                'time' => '09:00:00',
                'quota' => 160,
                'is_active' => 0,
                'banner' => 'events/4oPMgSooqfaB3xiZtBFJ1jUCe5XpjGocrCnaKHSx.jpg',
            ],
            [
                'title' => 'Workshop Internet of Things',
                'description' => 'Implementasi IoT untuk smart home dan smart campus.',
                'location' => 'Lab Embedded System',
                'date' => '2026-07-25',
                'time' => '08:00:00',
                'quota' => 90,
                'is_active' => 0,
                'banner' => 'events/lk3PdN85F2QTsqg8Td22hH7ybfXj7WyLra9vrEJo.jpg',
            ],
            [
                'title' => 'Kuliah Umum Transformasi Digital',
                'description' => 'Peran transformasi digital dalam dunia bisnis dan pendidikan.',
                'location' => 'Aula Utama Kampus',
                'date' => '2026-08-01',
                'time' => '09:30:00',
                'quota' => 250,
                'is_active' => 0,
                'banner' => 'events/CHwqkLXqthUMMB1gan9wcVTfY32XpKP1dZ7BWPOY.jpg',
            ],
            [
                'title' => 'Bootcamp Full Stack Developer',
                'description' => 'Pelatihan intensif menjadi Full Stack Web Developer.',
                'location' => 'Gedung Teknologi Informasi',
                'date' => '2026-08-10',
                'time' => '08:00:00',
                'quota' => 75,
                'is_active' => 0,
                'banner' => 'events/newsMP0aqFmB1soKcis5Fi5Sf4hZ9Y7vxCYyPCcR.jpg',
            ],
            [
                'title' => 'PAMERAN CAPSTONE PROJECT',
                'description' => 'YUK, HADIRI PAMERAN CAPSTONE PROJECT! Saksikan langsung karya-karya kreatif dan inovatif mahasiswa dalam menghadirkan berbagai solusi berbasis teknologi. Jangan lewatkan kesempatan untuk melihat, mencoba, dan memberikan apresiasi atas karya terbaik mahasiswa!',
                'location' => 'Selasar Fakultas Sains dan Teknologi Informasi',
                'date' => '2026-07-30',
                'time' => '08:00:00',
                'quota' => 0,
                'is_active' => 0,
                'banner' => 'events/3KlpN4ivQ4jakXAD4AvmU7Jvmc0ngzsRNPDkPOf6.jpg',
            ],
        ]);
    }
}
