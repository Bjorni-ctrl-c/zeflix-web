<?php

namespace Database\Seeders;

use App\Models\Film;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoFilmsSeeder extends Seeder
{
    public function run(): void
    {
        $genres = ['Aksi', 'Drama', 'Sci-Fi', 'Komedi', 'Horor', 'Dokumenter', 'Animasi'];
        foreach ($genres as $g) {
            Genre::firstOrCreate(['name' => $g]);
        }

        $films = [
            [
                'title' => 'Cyberpunk: Protokol 01',
                'genre' => 'Sci-Fi',
                'duration_minutes' => 24,
                'match_rate' => 98,
                'age_rating' => '16+',
                'quality' => '4K UHD',
                'is_featured' => true,
                'access' => 'premium',
                'views_count' => 1240,
                'synopsis' => 'Di megacity Neo-Nusantara tahun 2088, seorang hacker bawah tanah menemukan enkripsi tersembunyi yang mengendalikan ingatan seluruh penduduk kota. Satu keputusan memicu perburuan cyber mematikan.',
                'poster_url' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=800&auto=format&fit=crop&q=80',
                'video_provider' => 'youtube',
                'video_ref' => 'https://www.youtube.com/watch?v=R2U62Gk2y5g',
            ],
            [
                'title' => 'Bayang di Balik Senja',
                'genre' => 'Drama',
                'duration_minutes' => 18,
                'match_rate' => 96,
                'age_rating' => '13+',
                'quality' => '4K',
                'is_featured' => false,
                'access' => 'free',
                'views_count' => 980,
                'synopsis' => 'Pertemuan tak sengaja antara seorang musisi jalanan tua dan fotografer muda di dermaga membuka lembaran kenangan yang terkubur selama tiga dekade di bawah langit jingga pesisir.',
                'poster_url' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=800&auto=format&fit=crop&q=80',
                'video_provider' => 'youtube',
                'video_ref' => 'https://www.youtube.com/watch?v=eRsGyueVLvQ',
            ],
            [
                'title' => 'Kota Tak Bertuan',
                'genre' => 'Aksi',
                'duration_minutes' => 28,
                'match_rate' => 99,
                'age_rating' => '18+',
                'quality' => '4K UHD',
                'is_featured' => false,
                'access' => 'premium',
                'views_count' => 2150,
                'synopsis' => 'Tim taktis khusus terjebak di zona karantina industri terpencil saat operasi penyelamatan sandera berubah menjadi perang gerilya melawan sindikat tentara bayaran.',
                'poster_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=800&auto=format&fit=crop&q=80',
                'video_provider' => 'youtube',
                'video_ref' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ',
            ],
            [
                'title' => 'Tawa di Ujung Jalan',
                'genre' => 'Komedi',
                'duration_minutes' => 15,
                'match_rate' => 92,
                'age_rating' => 'SU',
                'quality' => 'HD',
                'is_featured' => false,
                'access' => 'free',
                'views_count' => 640,
                'synopsis' => 'Dua pengemudi ojek online kocak tak sengaja menukar paket pengantaran berisi boneka antik bernilai ratusan juta rupiah milik seorang kolektor eksentrik berwatak galak.',
                'poster_url' => 'https://images.unsplash.com/photo-1514306191717-452ec28c7814?w=800&auto=format&fit=crop&q=80',
                'video_provider' => 'youtube',
                'video_ref' => 'https://www.youtube.com/watch?v=YE7VzlLtp-4',
            ],
            [
                'title' => 'Detik Hening',
                'genre' => 'Horor',
                'duration_minutes' => 22,
                'match_rate' => 95,
                'age_rating' => '18+',
                'quality' => '4K UHD',
                'is_featured' => false,
                'access' => 'premium',
                'views_count' => 1430,
                'synopsis' => 'Di sebuah vila kolonial sunyi di perbukitan berkabut, sebuah radio tua menyiarkan rekaman siaran malam dari tahun 1942 yang hanya bisa didengar oleh penghuni kamar lantai dua.',
                'poster_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=800&auto=format&fit=crop&q=80',
                'video_provider' => 'youtube',
                'video_ref' => 'https://www.youtube.com/watch?v=calgR67X8uQ',
            ],
            [
                'title' => 'Satelit Terakhir',
                'genre' => 'Sci-Fi',
                'duration_minutes' => 32,
                'match_rate' => 97,
                'age_rating' => '13+',
                'quality' => '4K',
                'is_featured' => false,
                'access' => 'premium',
                'views_count' => 1890,
                'synopsis' => 'Kisah seorang astronom di stasiun observatorium pegunungan salju yang menangkap sinyal radio misterius dari satelit buatan manusia pertama yang dikira telah terbakar seabad lalu.',
                'poster_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&auto=format&fit=crop&q=80',
                'video_provider' => 'youtube',
                'video_ref' => 'https://www.youtube.com/watch?v=calgR67X8uQ',
            ],
            [
                'title' => 'Jejak Langkah Kecil',
                'genre' => 'Animasi',
                'duration_minutes' => 12,
                'match_rate' => 94,
                'age_rating' => 'SU',
                'quality' => '4K',
                'is_featured' => false,
                'access' => 'free',
                'views_count' => 770,
                'synopsis' => 'Petualangan visual yang menakjubkan dari seekor anak rubah kecil yang mengembara di hutan ajaib bercahaya untuk mengembalikan serpihan bintang yang jatuh.',
                'poster_url' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=800&auto=format&fit=crop&q=80',
                'video_provider' => 'youtube',
                'video_ref' => 'https://www.youtube.com/watch?v=mN0zPOgsrqM',
            ],
            [
                'title' => 'Melodi Pulau Seberang',
                'genre' => 'Dokumenter',
                'duration_minutes' => 26,
                'match_rate' => 91,
                'age_rating' => 'SU',
                'quality' => 'HD',
                'is_featured' => false,
                'access' => 'premium',
                'views_count' => 520,
                'synopsis' => 'Dokumenter puitis yang mengeksplorasi kehidupan nelayan dan pengrajin petikan sasando tradisional di kepulauan Rote dalam menjaga kearifan bahari leluhur.',
                'poster_url' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80',
                'video_provider' => 'youtube',
                'video_ref' => 'https://www.youtube.com/watch?v=9No-FiEInLA',
            ],
        ];

        foreach ($films as $item) {
            $slug = Str::slug($item['title']);
            Film::updateOrCreate(
                ['slug' => $slug],
                array_merge($item, [
                    'status' => 'published',
                    'published_at' => now()->subDays(rand(1, 30)),
                ])
            );
        }
    }
}
