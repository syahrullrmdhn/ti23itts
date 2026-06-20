<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class MountainStorySeeder extends Seeder
{
    public function run(): void
    {
        Post::updateOrCreate(
            ['slug' => 'di-antara-kabut-dan-langit-biru'],
            [
                'title' => 'Di Antara Kabut dan Langit Biru',
                'excerpt' => 'Tujuh anak kampus, satu jalur pendakian, dan perjalanan yang membuktikan bahwa langkah terberat terasa lebih ringan ketika ditempuh bersama.',
                'content' => <<<'HTML'
<p>Ada yang bilang, gunung adalah tempat di mana kita menemukan diri sendiri. Tapi hari itu, di tengah hamparan padang yang terbuka dengan langit biru terlalu sempurna untuk diabaikan, yang kami temukan bukan hanya diri sendiri — tapi satu sama lain.</p>

<p><strong>Tujuh orang. Satu kampus. Satu jalur yang sama.</strong></p>

<p>John Muir pernah menulis bahwa <em>“dalam setiap perjalanan dengan alam, seseorang menerima jauh lebih banyak daripada yang dia cari.”</em> Dan memang begitu adanya. Kami tidak hanya mendapat pemandangan, tidak hanya mendapat stempel di catatan perjalanan. Kami mendapat sesuatu yang jauh lebih susah dijelaskan — rasa bahwa ada momen-momen dalam hidup yang tidak bisa dibeli, hanya bisa dijalani.</p>

<p>Ransel berat di punggung tidak pernah terasa seringan itu ketika ada tawa yang meledak di antara napas yang tersengal. Trekking pole yang menancap di tanah berbatu, jaket yang berlapis dua tapi tetap kedinginan, dan wajah-wajah yang lelah tapi anehnya — selalu siap untuk senyum.</p>

<p>Itulah yang tidak pernah bisa diabadikan kamera: kehangatan yang tumbuh di antara pendaki ketika langkah semakin berat.</p>

<p>Soe Hok Gie pernah bilang, <em>“aku hanya mencintai tiga hal: matahari, hujan, dan kamu — kamu yang mau berjalan bersamaku meski jalan itu tidak mudah.”</em> Mungkin itulah yang kami rasakan satu sama lain hari itu. Bukan cinta yang dramatis — tapi cinta yang sederhana, yang tumbuh diam-diam dari langkah-langkah yang ditempuh bersama.</p>

<p>Kami bukan pendaki profesional. Kami hanya sekumpulan anak kampus yang pada suatu titik sepakat bahwa ada hal yang lebih penting dari sekadar tugas dan deadline — yaitu <strong>membuktikan pada diri sendiri bahwa kita mampu.</strong></p>

<p>Dan hari itu, di bawah langit yang terbuka lebar, di antara tenda-tenda warna-warni yang bertebaran seperti bunga di padang, kami berdiri bersama. <strong>Lelah. Bahagia. Utuh.</strong></p>

<p>Fiersa Besari pernah mengingatkan bahwa <em>“gunung bukanlah tempat untuk pamer, tempat untuk berhitung ketinggian, apalagi tempat untuk menambah jumlah puncak yang sudah kita daki.”</em> Dan kami benar-benar tidak ke sini untuk itu. Kami ke sini karena ada kerinduan yang tidak bisa dijelaskan — pada angin yang dingin, pada tanah yang kasar, pada langit yang terasa lebih dekat dari biasanya. Dan lebih dari itu: pada orang-orang yang berjalan bersama kami.</p>

<p>Gunung mengajarkan hal yang tidak ada di silabus manapun: bahwa karakter seseorang terlihat paling jelas ketika mereka kelelahan.</p>

<p>Ada yang menjadi penyemangat tanpa diminta. Ada yang diam tapi selalu ada di saat dibutuhkan. Ada yang tawanya menular bahkan di tengah kabut tebal. Ada yang langkahnya paling lambat tapi tidak pernah menyerah.</p>

<p>Tere Liye pernah menulis, <em>“menyenangkan punya teman yang bisa kita ajak berpetualang melihat dunia bersama — dan akan selalu menjadi teman baik, meskipun setelah bekerja, berkeluarga, jadi terpisah jarak satu sama lain.”</em> Kalimat itu terasa hidup ketika kamu benar-benar punya orang-orang seperti itu di sisimu. Dan kami punya mereka — hari itu, di ketinggian yang sama, dengan napas yang sama beratnya.</p>

<p>Inilah yang kami bawa turun dari gunung — bukan hanya kenangan, tapi keyakinan bahwa persahabatan yang lahir dari perjuangan bersama adalah persahabatan yang berbeda kualitasnya.</p>

<p>Khalil Gibran menulis, <em>“jika hatimu adalah sebuah gunung berapi, bagaimana bisa kamu mengharapkan bunga untuk mekar?”</em> Maka kami memilih untuk tidak membawa amarah ke atas. Tidak membawa ego, tidak membawa jarak. Hanya langkah, napas, dan rasa syukur yang tumbuh semakin besar di setiap ketinggian.</p>

<p>Soe Hok Gie juga pernah berkata, <em>“kita sekarang hidup di zaman di mana orang lebih suka membicarakan petualangan daripada menjalaninya.”</em></p>

<p><strong>Tapi kami menjalaninya.</strong></p>

<p>Foto itu mungkin hanya akan menjadi file di galeri. Tapi di baliknya tersimpan hal yang tidak akan lekang — ingatan tentang bagaimana rasanya berdiri di tempat yang tinggi, bersama orang-orang yang tepat, di waktu yang tidak akan pernah bisa diulang.</p>

<p>Terima kasih sudah menjadi bagian dari perjalanan ini.</p>

<p>Terima kasih sudah memilih naik — bersama.</p>

<p><em>Sampai ketemu di puncak berikutnya.</em> 🏔️</p>
HTML,
                'cover_image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1600&q=85',
                'is_published' => true,
                'published_at' => now(),
            ]
        );
    }
}
