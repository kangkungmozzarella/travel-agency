<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\TravelPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TravelContentSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'slug' => 'bali-ubud-sanur', 'location' => 'Bali · Ubud & Sanur',
                'type' => '3 hari 2 malam', 'price' => 2500000, 'image' => 'sawah.jpg', 'gallery' => ['travel-4.jpg'],
                'intro' => 'Dari lanskap sawah di Ubud sampai pagi yang tenang di Sanur. Rute ini memberi ruang untuk berjalan pelan, mencoba kuliner lokal, dan menikmati dua sisi Bali dalam satu perjalanan.',
                'days' => ['Tiba di Bali, menuju Ubud, dan menikmati sore dengan berjalan di sekitar pusat Ubud.', 'Menjelajahi kawasan Tegallalang, berhenti untuk makan siang, lalu melanjutkan perjalanan ke Sanur.', 'Menikmati pagi di tepi pantai Sanur dan bersiap untuk perjalanan pulang.'],
                'preparation' => 'Bawa pakaian ringan, alas kaki yang nyaman, dan botol minum. Saat memasuki tempat ibadah, ikuti tata tertib dan ketentuan pakaian setempat.',
                'title' => 'Ubud atau Sanur? Dua ritme untuk satu perjalanan ke Bali',
                'excerpt' => 'Sawah, jalan kecil, dan pantai pagi: menyusun perjalanan ke Bali dengan waktu untuk berhenti.',
                'article' => '<h2>Mulai dengan ritme Ubud</h2><p>Ubud menawarkan suasana pedalaman Bali, dengan kegiatan seni dan lanskap sawah di kawasan sekitarnya. Tegallalang bisa menjadi salah satu pilihan untuk menikmati bentang terasering. Sisakan waktu di antara kunjungan, supaya perjalanan tidak habis di kendaraan.</p><h2>Lanjutkan ke tepi laut</h2><p>Sanur memberi suasana berbeda dari Ubud. Berjalan pagi di tepi pantai bisa menjadi penutup perjalanan yang sederhana. Pilih tempat menginap sesuai kegiatan yang paling ingin kamu lakukan, lalu kelompokkan kunjungan yang berdekatan.</p><h2>Jangan padatkan setiap hari</h2><p>Tiga hari cukup untuk memilih beberapa kegiatan utama, bukan mengejar seluruh Bali. Sisakan waktu untuk makan, istirahat, dan perubahan rencana. Diskusikan titik jemput serta waktu penerbangan sebelum menyepakati itinerary.</p>',
                'source' => 'https://www.indonesia.travel/id/en/destination/bali-nusa-tenggara/bali',
            ],
            [
                'slug' => 'lombok-pantai-selatan', 'location' => 'Lombok · Pantai Selatan',
                'type' => '3 hari 2 malam', 'price' => 1800000, 'image' => 'travel-1.jpg', 'gallery' => ['hero.jpg'],
                'intro' => 'Perjalanan pesisir dengan waktu untuk menikmati pantai, melihat lanskap dari Bukit Merese, dan mengenal kawasan Kuta Mandalika. Cocok untuk rencana liburan yang ingin tetap longgar.',
                'days' => ['Tiba di Lombok, menuju kawasan Kuta Mandalika, dan menikmati sore di sekitar penginapan.', 'Mengunjungi Tanjung Aan dan melanjutkan ke Bukit Merese sesuai kondisi cuaca.', 'Sarapan santai, waktu bebas, dan perjalanan menuju titik kepulangan.'],
                'preparation' => 'Siapkan pelindung matahari dan alas kaki untuk berjalan di bukit. Kegiatan laut perlu menyesuaikan ombak, cuaca, dan arahan petugas di lokasi.',
                'title' => 'Menyusun hari yang santai di pesisir selatan Lombok',
                'excerpt' => 'Memadukan Tanjung Aan, Bukit Merese, dan waktu bebas tanpa mengejar terlalu banyak tempat.',
                'article' => '<h2>Pilih satu kawasan sebagai basis</h2><p>Kawasan Kuta Mandalika dapat menjadi titik awal untuk menjelajahi pesisir selatan Lombok. Menginap dekat kegiatan utama membantu mengurangi waktu berpindah dan memberi ruang untuk istirahat.</p><h2>Pantai dan bukit dalam satu hari</h2><p>Tanjung Aan dan Bukit Merese dapat menjadi dua kegiatan utama dalam satu hari. Nikmati pantai terlebih dahulu, lalu sesuaikan kegiatan berjalan di bukit dengan cuaca dan kemampuan masing-masing peserta.</p><h2>Siapkan rencana yang fleksibel</h2><p>Jangan menjadikan setiap kegiatan sebagai kewajiban. Kondisi ombak dan cuaca bisa mengubah rencana di pantai. Bawa air minum, tinggalkan lokasi dengan bersih, dan ikuti arahan warga maupun pengelola.</p>',
                'source' => 'https://www.indonesia.travel/us/en/destination/bali-nusa-tenggara/west-nusa-tenggara/tanjung-aan',
            ],
            [
                'slug' => 'labuan-bajo-pulau-padar', 'location' => 'Labuan Bajo · Pulau Padar',
                'type' => '3 hari 2 malam', 'price' => 3500000, 'image' => 'travel-2.jpg', 'gallery' => ['palawan.jpg', 'hero.jpg'],
                'intro' => 'Mulai dari Labuan Bajo dan lanjutkan ke lanskap kepulauan di kawasan Taman Nasional Komodo. Perjalanan darat dan laut dirancang dengan jeda yang cukup untuk menikmati pemandangan.',
                'days' => ['Tiba di Labuan Bajo, check-in, dan menikmati suasana kota pesisir.', 'Perjalanan laut menuju Pulau Padar dengan rute dan kegiatan yang disepakati bersama operator kapal.', 'Waktu bebas di Labuan Bajo sebelum transfer menuju titik kepulangan.'],
                'preparation' => 'Rute kapal, akses kawasan, dan aktivitas bergantung pada kondisi cuaca serta ketentuan pengelola. Pilih operator yang menyediakan perlengkapan keselamatan dan ikuti pendamping di kawasan konservasi.',
                'title' => 'Sebelum berlayar dari Labuan Bajo: hal yang perlu dibicarakan',
                'excerpt' => 'Rute, waktu di kapal, dan persiapan sederhana agar perjalanan ke kepulauan lebih tertata.',
                'article' => '<h2>Kenali titik awal perjalanan</h2><p>Labuan Bajo berada di Flores bagian barat dan menjadi pintu perjalanan menuju kawasan Taman Nasional Komodo. Pulau Padar merupakan salah satu tujuan di kawasan tersebut, dengan bentang perbukitan dan teluk yang menjadi daya tarik utamanya.</p><h2>Tanyakan detail perjalanan laut</h2><p>Sebelum menyepakati rute, bicarakan durasi di kapal, titik keberangkatan, kapasitas peserta, dan fasilitas yang tersedia. Jadwal di laut harus tetap mengikuti cuaca serta arahan operator.</p><h2>Beri ruang untuk penyesuaian</h2><p>Persiapkan alas kaki yang sesuai untuk berjalan, pelindung matahari, dan barang pribadi dalam tas yang mudah dibawa. Hindari mengemas terlalu banyak kegiatan dalam satu hari. Di kawasan konservasi, ikuti aturan pengelola dan jangan mengganggu satwa.</p>',
                'source' => 'https://www.indonesia.travel/id/id/destination/bali-nusa-tenggara/east-nusa-tenggara/labuan-bajo',
            ],
            [
                'slug' => 'bromo-menyambut-pagi', 'location' => 'Bromo · Menyambut Pagi',
                'type' => '2 hari 1 malam', 'price' => 1500000, 'image' => 'mount.jpg', 'gallery' => ['volcano-with-mist-sunset.jpg'],
                'intro' => 'Jeda singkat di pegunungan Jawa Timur, dengan perjalanan pagi menuju titik pandang dan waktu untuk menikmati bentang kaldera. Rencana ringkas untuk kamu yang ingin mengganti suasana.',
                'days' => ['Tiba di kawasan penginapan sekitar Bromo, istirahat, dan membicarakan jadwal keberangkatan pagi dengan pendamping.', 'Berangkat menuju titik pandang sesuai akses yang tersedia, menikmati panorama Bromo, lalu kembali dan melanjutkan perjalanan pulang.'],
                'preparation' => 'Bawa jaket, alas kaki tertutup, dan perlengkapan pribadi untuk udara dingin. Panorama pagi bergantung pada cuaca. Akses dan kegiatan mengikuti informasi pengelola kawasan.',
                'title' => 'Bromo dalam dua hari: sisakan waktu untuk tidur',
                'excerpt' => 'Menyusun perjalanan pagi ke Bromo dimulai dari persiapan dan istirahat pada malam sebelumnya.',
                'article' => '<h2>Datang sebelum hari terlalu larut</h2><p>Rencana melihat panorama pagi di Bromo biasanya membutuhkan keberangkatan dini hari. Karena itu, hari pertama sebaiknya dipakai untuk tiba di penginapan, makan, dan beristirahat, bukan memadatkan kegiatan tambahan.</p><h2>Fokus pada pengalaman, bukan foto tertentu</h2><p>Penanjakan dikenal sebagai salah satu titik pandang di kawasan Bromo. Namun hasil pengamatan pagi selalu dipengaruhi kondisi cuaca dan akses pada hari kunjungan. Diskusikan pilihan titik pandang dengan pendamping dan siapkan rencana alternatif.</p><h2>Perlengkapan yang membantu</h2><p>Jaket dan alas kaki tertutup memudahkan kegiatan di pegunungan. Siapkan barang pribadi sejak malam sebelumnya agar tidak terburu-buru. Setelah kegiatan pagi, sisakan waktu untuk sarapan dan kembali dengan tenang.</p>',
                'source' => 'https://www.indonesia.travel/id/id/travel-ideas/adventure/gunung-bromo',
            ],
            [
                'slug' => 'nusa-penida-tebing-barat', 'location' => 'Nusa Penida · Tebing Barat',
                'type' => '2 hari 1 malam', 'price' => 1650000, 'image' => 'bali.jpg', 'gallery' => ['kontakp.jpg'],
                'intro' => 'Menyeberang dari Bali menuju pulau dengan tebing kapur dan teluk berpasir putih. Rute singkat ini cocok untuk kamu yang ingin melihat lanskap pesisir yang dramatis tanpa cuti panjang.',
                'days' => ['Menyeberang dari Sanur, menuju penginapan, lalu mengunjungi titik pandang di sisi barat pulau saat sore.', 'Pagi di tepi pantai, waktu bebas, dan penyeberangan kembali ke Bali.'],
                'preparation' => 'Jalur menuju pantai di bawah tebing cukup curam. Gunakan alas kaki yang mencengkeram, bawa air minum, dan jangan memaksakan diri turun jika kondisi jalur licin.',
                'title' => 'Nusa Penida tanpa terburu-buru: pilih sisi pulau, bukan semua',
                'excerpt' => 'Jarak antartitik di Nusa Penida cukup jauh. Fokus pada satu sisi pulau membuat hari terasa lebih lapang.',
                'article' => '<h2>Jarak lebih jauh dari yang terlihat</h2><p>Di peta, titik-titik wisata Nusa Penida tampak berdekatan. Kenyataannya, jalan yang berkelok membuat perpindahan memakan waktu. Memilih satu sisi pulau per hari membantu kamu menikmati tempat yang dikunjungi.</p><h2>Datang sore, bukan siang</h2><p>Cahaya sore membuat tebing dan air laut terlihat lebih lembut. Suhu juga lebih bersahabat untuk berjalan. Gunakan waktu siang untuk makan dan beristirahat di penginapan.</p><h2>Hormati batas diri</h2><p>Beberapa pantai hanya bisa dicapai lewat jalur menurun yang curam. Menikmati pemandangan dari atas sudah cukup indah. Ikuti papan peringatan dan arahan warga setempat.</p>',
            ],
            [
                'slug' => 'bromo-ijen-jalur-timur', 'location' => 'Bromo & Ijen · Jalur Timur',
                'type' => '4 hari 3 malam', 'price' => 3200000, 'image' => 'volcano-with-mist-sunset.jpg', 'gallery' => ['mount.jpg'],
                'intro' => 'Dua kawasan pegunungan Jawa Timur dalam satu perjalanan darat. Pagi di kaldera Bromo, lalu lanjut ke kawasan Ijen dengan jeda istirahat yang cukup di antara keduanya.',
                'days' => ['Tiba di Malang atau Surabaya, perjalanan menuju kawasan Bromo, dan istirahat lebih awal.', 'Kegiatan pagi di kawasan Bromo, sarapan, lalu perjalanan darat menuju Bondowoso.', 'Waktu istirahat di penginapan dan persiapan untuk pendakian Ijen.', 'Pendakian pagi ke kawasan Ijen sesuai izin pengelola, kemudian perjalanan menuju titik kepulangan.'],
                'preparation' => 'Bawa jaket hangat, sarung tangan, senter, dan alas kaki untuk mendaki. Pendakian Ijen mengikuti jam buka dan ketentuan pengelola, termasuk penggunaan masker bila diperlukan.',
                'title' => 'Bromo dan Ijen dalam satu rute: atur napas di antara keduanya',
                'excerpt' => 'Dua kegiatan dini hari berturut-turut terasa berat. Hari jeda di tengah membuat perjalanan tetap menyenangkan.',
                'article' => '<h2>Dua pagi yang panjang</h2><p>Bromo dan Ijen sama-sama dinikmati sejak dini hari. Jika dijalankan berurutan tanpa jeda, tubuh mudah lelah. Hari istirahat di antara keduanya memberi waktu untuk tidur dan memulihkan tenaga.</p><h2>Perjalanan darat bagian dari cerita</h2><p>Rute dari Bromo menuju Bondowoso melewati kota kecil dan perkebunan. Nikmati perjalanan ini sebagai bagian dari liburan, bukan sekadar perpindahan.</p><h2>Persiapan untuk udara dingin</h2><p>Suhu di kedua kawasan bisa turun drastis menjelang pagi. Lapisan pakaian lebih praktis daripada satu jaket tebal, karena kamu bisa melepasnya saat matahari mulai naik.</p>',
            ],
            [
                'slug' => 'raja-ampat-kepulauan-karst', 'location' => 'Raja Ampat · Kepulauan Karst',
                'type' => '5 hari 4 malam', 'price' => 8900000, 'image' => 'palawan.jpg', 'gallery' => ['hero.jpg', 'borabora.jpg'],
                'intro' => 'Perjalanan laut di antara pulau-pulau karst dan perairan jernih di Papua Barat Daya. Rute panjang ini dirancang untuk menikmati laut dengan tempo yang tenang.',
                'days' => ['Tiba di Sorong, menyeberang ke Waisai, dan beristirahat di penginapan.', 'Menjelajahi perairan sekitar dengan perahu dan berhenti di pantai yang disepakati.', 'Perjalanan menuju titik pandang kepulauan karst sesuai kondisi laut.', 'Waktu bebas untuk snorkeling atau bersantai di penginapan.', 'Menyeberang kembali ke Sorong untuk perjalanan pulang.'],
                'preparation' => 'Siapkan biaya izin masuk kawasan, perlengkapan snorkeling pribadi bila ada, dan obat-obatan pribadi. Jadwal laut sangat bergantung pada cuaca.',
                'title' => 'Raja Ampat untuk pertama kali: lebih lama, lebih tenang',
                'excerpt' => 'Jarak tempuh yang panjang membuat Raja Ampat lebih nikmat dijalani dengan hari yang tidak terlalu padat.',
                'article' => '<h2>Perjalanan menuju ke sana butuh waktu</h2><p>Penerbangan ke Sorong lalu penyeberangan ke Waisai bisa memakan setengah hari. Jangan menjadwalkan kegiatan laut pada hari kedatangan.</p><h2>Laut menentukan jadwal</h2><p>Gelombang dan cuaca memengaruhi rute perahu. Bicarakan pilihan alternatif dengan operator, sehingga hari tetap berisi meski rencana awal berubah.</p><h2>Jaga apa yang kamu nikmati</h2><p>Gunakan tabir surya yang ramah terumbu karang, jangan menginjak karang saat snorkeling, dan bawa kembali sampahmu ke daratan.</p>',
            ],
            [
                'slug' => 'karimunjawa-pulau-kecil', 'location' => 'Karimunjawa · Pulau Kecil',
                'type' => '3 hari 2 malam', 'price' => 2100000, 'image' => 'hero.jpg', 'gallery' => ['palawan.jpg'],
                'intro' => 'Gugusan pulau kecil di utara Jawa Tengah dengan perairan dangkal yang jernih. Pilihan liburan laut yang dekat untuk kamu yang berangkat dari Jawa.',
                'days' => ['Menyeberang dari Jepara, tiba di Karimunjawa, dan menikmati sore di dermaga.', 'Hopping pulau dengan perahu dan snorkeling di titik yang disepakati.', 'Pagi santai sebelum kapal kembali ke Jepara.'],
                'preparation' => 'Jadwal kapal dapat berubah mengikuti cuaca. Siapkan uang tunai karena tidak semua tempat menerima pembayaran nontunai.',
                'title' => 'Karimunjawa: liburan laut yang dekat dari Jawa',
                'excerpt' => 'Kapal dari Jepara, perahu kecil antarpulau, dan sore yang panjang di dermaga.',
                'article' => '<h2>Cek jadwal kapal lebih dulu</h2><p>Perjalanan ke Karimunjawa bergantung pada kapal dari Jepara. Pastikan jadwal keberangkatan dan kepulangan sebelum memesan penginapan.</p><h2>Satu hari untuk laut</h2><p>Hopping pulau biasanya berlangsung seharian. Bawa pakaian ganti, pelindung matahari, dan kantong kedap air untuk barang pribadi.</p><h2>Nikmati malam yang sederhana</h2><p>Malam di Karimunjawa tenang. Makan ikan bakar di alun-alun bisa menjadi penutup hari yang menyenangkan.</p>',
            ],
            [
                'slug' => 'uluwatu-senja-tebing', 'location' => 'Uluwatu · Senja di Tebing',
                'type' => '2 hari 1 malam', 'price' => 1400000, 'image' => 'kontakp.jpg', 'gallery' => ['travel-4.jpg'],
                'intro' => 'Ujung selatan Bali dengan tebing tinggi, pantai tersembunyi, dan langit sore yang luas. Rute pendek untuk menutup perjalanan di Bali.',
                'days' => ['Tiba di kawasan Uluwatu, mengunjungi pantai di bawah tebing, lalu menikmati senja dari titik pandang.', 'Pagi di pantai, waktu bebas, dan perjalanan menuju bandara.'],
                'preparation' => 'Banyak pantai di Uluwatu dicapai lewat tangga. Bawa alas kaki yang nyaman, dan jaga barang bawaan dari kera di area pura.',
                'title' => 'Mengejar senja di Uluwatu tanpa terjebak macet',
                'excerpt' => 'Datang lebih awal, pilih satu titik pandang, lalu biarkan sore berjalan pelan.',
                'article' => '<h2>Berangkat sebelum sore</h2><p>Jalan menuju Uluwatu sering padat menjelang senja. Tiba lebih awal memberi waktu untuk makan dan mencari tempat duduk yang nyaman.</p><h2>Satu titik pandang sudah cukup</h2><p>Berpindah-pindah demi foto berbeda justru membuat senja terlewat. Pilih satu tempat dan nikmati perubahan warna langit.</p><h2>Jaga barang bawaan</h2><p>Di beberapa area, kera dikenal suka mengambil kacamata atau ponsel. Simpan barang kecil di dalam tas.</p>',
            ],
            [
                'slug' => 'derawan-maratua', 'location' => 'Derawan · Maratua',
                'type' => '4 hari 3 malam', 'price' => 6400000, 'image' => 'borabora.jpg', 'gallery' => ['palawan.jpg'],
                'intro' => 'Penginapan di atas air, laguna biru, dan kesempatan bertemu penyu di Kalimantan Timur. Perjalanan laut yang tenang untuk berlibur lebih lama.',
                'days' => ['Tiba di Berau, perjalanan darat dan laut menuju Pulau Derawan.', 'Menyeberang ke Maratua dan menikmati sore di penginapan atas air.', 'Mengunjungi laguna dan danau di pulau sekitar sesuai kondisi laut.', 'Kembali ke Berau untuk perjalanan pulang.'],
                'preparation' => 'Perjalanan menuju kepulauan cukup panjang. Bawa obat antimabuk bila perlu, dan jaga jarak saat bertemu penyu di laut.',
                'title' => 'Derawan dan Maratua: tidur di atas air',
                'excerpt' => 'Perjalanan panjang yang terbayar dengan laguna jernih dan pagi yang sunyi.',
                'article' => '<h2>Hitung waktu tempuh</h2><p>Dari Berau, perjalanan menuju kepulauan menggabungkan jalur darat dan laut. Sisihkan satu hari penuh untuk berangkat dan satu hari untuk kembali.</p><h2>Penginapan sebagai tujuan</h2><p>Menginap di atas air memberi pengalaman tersendiri. Pagi bisa dinikmati hanya dengan duduk di teras dan melihat laut.</p><h2>Bertemu satwa dengan hormat</h2><p>Penyu sering terlihat di perairan sekitar. Jangan menyentuh atau mengejar mereka, dan ikuti arahan pemandu.</p>',
            ],
            [
                'slug' => 'belitung-batu-granit', 'location' => 'Belitung · Batu Granit',
                'type' => '3 hari 2 malam', 'price' => 2300000, 'image' => 'travel-2.jpg', 'gallery' => ['travel-1.jpg'],
                'intro' => 'Pantai berbatu granit besar, air laut yang tenang, dan pulau-pulau kecil yang bisa dijangkau dengan perahu. Liburan pantai yang ramah untuk keluarga.',
                'days' => ['Tiba di Tanjung Pandan, menikmati kuliner lokal, dan sore di pantai terdekat.', 'Hopping pulau di sekitar Tanjung Kelayang sesuai kondisi laut.', 'Mengunjungi pantai di pesisir barat sebelum menuju bandara.'],
                'preparation' => 'Perairan relatif tenang, tetapi tetap gunakan pelampung saat di perahu. Bawa topi dan pelindung matahari.',
                'title' => 'Belitung bersama keluarga: pantai tenang dan perjalanan pendek',
                'excerpt' => 'Jarak antarpantai yang dekat membuat Belitung nyaman untuk liburan bersama anak-anak.',
                'article' => '<h2>Jarak yang bersahabat</h2><p>Banyak pantai di Belitung bisa dicapai dalam waktu singkat dari Tanjung Pandan. Anak-anak tidak perlu terlalu lama di kendaraan.</p><h2>Laut yang relatif tenang</h2><p>Perairan dangkal di beberapa pantai cocok untuk bermain air. Tetap awasi anak dan perhatikan kondisi ombak.</p><h2>Cicipi makanan lokal</h2><p>Mi Belitung dan hidangan laut bisa menjadi bagian menyenangkan dari perjalanan. Tanyakan rekomendasi warung kepada pemandu.</p>',
            ],
            [
                'slug' => 'dieng-dataran-tinggi', 'location' => 'Dieng · Dataran Tinggi',
                'type' => '2 hari 1 malam', 'price' => 1250000, 'image' => 'mount.jpg', 'gallery' => ['volcano-with-mist-sunset.jpg'],
                'intro' => 'Udara sejuk, telaga berwarna, dan candi di dataran tinggi Jawa Tengah. Perjalanan akhir pekan yang cocok untuk mengganti suasana kota.',
                'days' => ['Tiba di Wonosobo, naik ke Dieng, dan mengunjungi kompleks candi serta telaga.', 'Menikmati pagi dari bukit pandang, sarapan, lalu turun kembali ke Wonosobo.'],
                'preparation' => 'Suhu di Dieng bisa sangat dingin pada malam dan pagi hari. Bawa jaket, kaus kaki tebal, dan penutup kepala.',
                'title' => 'Akhir pekan di Dieng: dingin yang menyenangkan',
                'excerpt' => 'Telaga, candi, dan pagi berkabut dalam perjalanan dua hari dari Wonosobo.',
                'article' => '<h2>Siapkan pakaian hangat</h2><p>Dieng berada di ketinggian, sehingga udara dingin terasa sejak sore. Lapisan pakaian membantu kamu tetap nyaman.</p><h2>Telaga dan candi dalam satu hari</h2><p>Lokasi wisata di Dieng cukup berdekatan. Kunjungi kompleks candi dan telaga pada hari pertama, lalu simpan pagi untuk bukit pandang.</p><h2>Cicipi yang hangat</h2><p>Mi ongklok dan minuman hangat terasa pas di udara dingin. Tanyakan pilihan kuliner lokal di sekitar penginapan.</p>',
            ],
            [
                'slug' => 'sumba-pantai-sabana', 'location' => 'Sumba · Pantai & Sabana',
                'type' => '4 hari 3 malam', 'price' => 5200000, 'image' => 'travel-4.jpg', 'gallery' => ['kontakp.jpg'],
                'intro' => 'Sabana luas, kampung adat, dan pantai dengan senja panjang di Nusa Tenggara Timur. Perjalanan untuk kamu yang ingin melihat lanskap yang berbeda.',
                'days' => ['Tiba di Tambolaka dan menikmati senja di pantai terdekat.', 'Mengunjungi kampung adat dengan pendamping lokal.', 'Menjelajahi bukit sabana dan air terjun sesuai akses jalan.', 'Pagi santai, lalu menuju bandara.'],
                'preparation' => 'Saat mengunjungi kampung adat, minta izin sebelum memotret dan ikuti arahan pendamping. Jarak antarlokasi cukup jauh, jadi siapkan waktu di perjalanan.',
                'title' => 'Sumba: jarak jauh, langit luas',
                'excerpt' => 'Menyusun perjalanan ke Sumba dengan waktu yang cukup untuk perjalanan darat dan senja.',
                'article' => '<h2>Pulau yang lebih besar dari dugaan</h2><p>Perjalanan antarlokasi di Sumba bisa memakan beberapa jam. Kelompokkan kunjungan berdasarkan wilayah agar tidak bolak-balik.</p><h2>Kunjungi kampung adat dengan hormat</h2><p>Kampung adat adalah tempat tinggal warga. Datang bersama pendamping, minta izin sebelum memotret, dan ikuti aturan setempat.</p><h2>Simpan sore untuk pantai</h2><p>Senja di pesisir Sumba terasa panjang. Selesaikan kegiatan darat sebelum sore, lalu nikmati matahari terbenam.</p>',
            ],
            [
                'slug' => 'jatiluwih-terasering', 'location' => 'Jatiluwih · Terasering',
                'type' => '2 hari 1 malam', 'price' => 1100000, 'image' => 'sawah.jpg', 'gallery' => ['bali.jpg'],
                'intro' => 'Berjalan di antara terasering sawah yang luas di Tabanan, Bali. Rute pendek yang tenang, jauh dari keramaian kawasan pantai.',
                'days' => ['Tiba di Tabanan, berjalan sore di jalur sawah Jatiluwih, dan menginap di penginapan sekitar.', 'Pagi di antara sawah, sarapan, lalu kembali ke Denpasar.'],
                'preparation' => 'Gunakan alas kaki yang nyaman dan topi. Tetap di jalur yang tersedia agar tidak merusak pematang sawah milik warga.',
                'title' => 'Jatiluwih: Bali yang lebih pelan',
                'excerpt' => 'Berjalan kaki di antara sawah dan menginap jauh dari keramaian pantai.',
                'article' => '<h2>Datang pagi atau sore</h2><p>Cahaya pagi dan sore membuat terasering terlihat lebih indah, dan suhu lebih nyaman untuk berjalan.</p><h2>Pilih jalur sesuai waktu</h2><p>Tersedia beberapa jalur berjalan kaki dengan panjang berbeda. Tanyakan kepada pengelola jalur mana yang sesuai dengan waktumu.</p><h2>Sawah adalah lahan kerja</h2><p>Terasering di Jatiluwih masih digarap petani. Tetap di jalur, jangan menginjak tanaman, dan sapa warga yang sedang bekerja.</p>',
            ],
        ];

        $articles = [
            ['category' => 'Tips perjalanan', 'image' => 'blog-2.jpg', 'days' => 3, 'title' => 'Satu tas untuk tiga hari: cara berkemas lebih ringan', 'excerpt' => 'Daftar sederhana agar ranselmu tidak penuh barang yang tidak terpakai.', 'article' => '<h2>Mulai dari rencana kegiatan</h2><p>Tuliskan kegiatan setiap hari, lalu pilih pakaian yang bisa dipakai untuk lebih dari satu kegiatan.</p><h2>Pilih warna yang mudah dipadukan</h2><p>Pakaian dengan warna netral mudah dipadukan, sehingga jumlah barang yang dibawa berkurang.</p><h2>Sisakan ruang</h2><p>Tas yang tidak terlalu penuh memudahkan saat membawa oleh-oleh atau barang tambahan di perjalanan pulang.</p>'],
            ['category' => 'Tips perjalanan', 'image' => 'blog-1.jpg', 'days' => 6, 'title' => 'Menyusun anggaran liburan tanpa pusing', 'excerpt' => 'Pisahkan biaya tetap dan biaya harian agar pengeluaran lebih mudah dipantau.', 'article' => '<h2>Biaya tetap lebih dulu</h2><p>Transportasi utama dan penginapan biasanya menjadi pengeluaran terbesar. Catat keduanya sebelum menghitung biaya lain.</p><h2>Tetapkan anggaran harian</h2><p>Makan, transportasi lokal, dan tiket masuk bisa dihitung per hari. Cara ini membantu kamu tahu kapan harus berhemat.</p><h2>Siapkan dana cadangan</h2><p>Sisihkan sebagian kecil anggaran untuk kebutuhan tak terduga, seperti perubahan jadwal atau obat-obatan.</p>'],
            ['category' => 'Tips perjalanan', 'image' => 'blog-3.jpg', 'days' => 9, 'title' => 'Memotret perjalanan tanpa lupa menikmatinya', 'excerpt' => 'Kamera membantu mengingat, tapi jangan sampai menggantikan pengalaman itu sendiri.', 'article' => '<h2>Tentukan momen untuk memotret</h2><p>Ambil beberapa foto saat tiba, lalu simpan kamera dan nikmati suasana.</p><h2>Cukup satu atau dua lensa</h2><p>Perlengkapan yang ringan membuat kamu lebih leluasa bergerak.</p><h2>Minta izin saat memotret orang</h2><p>Saat ingin memotret warga, sapa dan minta izin terlebih dahulu.</p>'],
            ['category' => 'Tips perjalanan', 'image' => 'blog-3_.jpg', 'days' => 12, 'title' => 'Barang kecil yang sering terlupa saat berangkat', 'excerpt' => 'Charger, obat pribadi, dan dokumen: periksa sekali lagi sebelum menutup pintu.', 'article' => '<h2>Elektronik dan pengisi daya</h2><p>Kumpulkan semua kabel dalam satu kantong agar mudah diperiksa.</p><h2>Obat dan perlengkapan pribadi</h2><p>Bawa obat yang rutin kamu konsumsi dalam jumlah cukup, ditambah sedikit cadangan.</p><h2>Dokumen perjalanan</h2><p>Simpan salinan digital tiket dan kartu identitas di ponsel sebagai cadangan.</p>'],
            ['category' => 'Tips perjalanan', 'image' => 'travel-1.jpg', 'days' => 15, 'title' => 'Memilih waktu berangkat agar liburan tidak terlalu ramai', 'excerpt' => 'Hindari puncak musim liburan untuk mendapatkan suasana yang lebih tenang.', 'article' => '<h2>Perhatikan kalender libur</h2><p>Libur sekolah dan hari raya biasanya membuat destinasi populer lebih padat.</p><h2>Datang di hari kerja</h2><p>Jika memungkinkan, mulai perjalanan pada hari kerja. Harga penginapan sering lebih bersahabat.</p><h2>Pertimbangkan musim</h2><p>Musim hujan dan kemarau memengaruhi kegiatan, terutama untuk perjalanan laut dan pendakian.</p>'],
            ['category' => 'Cerita perjalanan', 'image' => 'kontakp.jpg', 'days' => 18, 'title' => 'Sore yang terlalu cepat berlalu di tepi tebing', 'excerpt' => 'Catatan pendek tentang menunggu matahari terbenam tanpa membawa kamera.', 'article' => '<h2>Datang tanpa rencana foto</h2><p>Sore itu kami memutuskan meninggalkan kamera di penginapan. Hanya duduk di tepi tebing dan melihat laut.</p><h2>Warna yang berganti</h2><p>Langit berubah dari jingga ke ungu dalam hitungan menit. Tidak ada yang berbicara.</p><h2>Yang dibawa pulang</h2><p>Tidak ada foto dari sore itu, tetapi ingatannya tetap jelas.</p>'],
            ['category' => 'Cerita perjalanan', 'image' => 'travel-3.jpg', 'days' => 21, 'title' => 'Tersesat di kota pesisir dan menemukan warung terbaik', 'excerpt' => 'Kadang belokan yang salah membawa kita ke makan malam paling berkesan.', 'article' => '<h2>Peta yang keliru</h2><p>Jalan yang kami pilih ternyata buntu di sebuah gang kecil dekat pelabuhan.</p><h2>Warung tanpa nama</h2><p>Di ujung gang, ada warung kecil dengan ikan bakar dan sambal yang masih kami bicarakan sampai sekarang.</p><h2>Pelajaran kecil</h2><p>Sisakan waktu kosong dalam rencana. Hal terbaik sering datang tanpa dijadwalkan.</p>'],
            ['category' => 'Cerita perjalanan', 'image' => 'travel-4.jpg', 'days' => 24, 'title' => 'Liburan pertama bersama orang tua', 'excerpt' => 'Belajar memperlambat langkah dan menyesuaikan rencana dengan ritme keluarga.', 'article' => '<h2>Rencana yang berubah</h2><p>Daftar kegiatan yang kami susun ternyata terlalu padat. Di hari kedua, kami memangkasnya menjadi setengah.</p><h2>Waktu yang lebih berarti</h2><p>Duduk lama di tepi pantai sambil bercerita ternyata menjadi bagian favorit perjalanan.</p><h2>Untuk perjalanan berikutnya</h2><p>Kami belajar bahwa liburan bersama keluarga bukan tentang jumlah tempat, tapi waktu yang dihabiskan bersama.</p>'],
        ];

        $guides = Category::firstOrCreate(['slug' => 'panduan-perjalanan'], ['name' => 'Panduan perjalanan']);
        foreach ($destinations as $index => $item) {
            $itinerary = '';
            foreach ($item['days'] as $day => $activity) {
                $itinerary .= '<h3>Hari '.($day + 1).'</h3><p>'.$activity.'</p>';
            }
            $description = '<p>'.$item['intro'].'</p><h2>Rencana perjalanan</h2>'.$itinerary.'<h2>Persiapan</h2><p>'.$item['preparation'].'</p><h2>Harga dan konfirmasi</h2><p>Harga yang ditampilkan adalah estimasi contoh untuk menyusun rencana perjalanan, bukan penawaran final. Titik jemput, transportasi, penginapan, tiket, makan, dan kegiatan yang termasuk perlu disepakati dengan tim sebelum konfirmasi. Jadwal dapat menyesuaikan cuaca dan akses destinasi.</p><p>Foto merupakan ilustrasi suasana perjalanan dari koleksi website.</p>';
            $date = now()->subDays($index * 2);
            $package = TravelPackage::firstOrCreate(['slug' => $item['slug']], ['location' => $item['location'], 'type' => $item['type'], 'price' => $item['price'], 'description' => $description, 'created_at' => $date, 'updated_at' => $date]);
            $imagePath = $this->store($item['image'], 'destinations/'.$item['slug'].'.jpg');
            if ($package->wasRecentlyCreated) {
                $package->galleries()->create(['name' => 'Ilustrasi suasana perjalanan', 'images' => $imagePath]);
                foreach ($item['gallery'] as $number => $file) {
                    $package->galleries()->create(['name' => 'Ilustrasi suasana perjalanan', 'images' => $this->store($file, 'destinations/'.$item['slug'].'-'.($number + 2).'.jpg')]);
                }
            }
            $source = isset($item['source']) ? '<p>Informasi destinasi: <a href="'.$item['source'].'">Indonesia Travel</a>. Foto merupakan ilustrasi suasana perjalanan.</p>' : '<p>Foto merupakan ilustrasi suasana perjalanan.</p>';
            Blog::firstOrCreate(['slug' => 'panduan-'.$item['slug']], ['title' => $item['title'], 'excerpt' => $item['excerpt'], 'description' => $item['article'].$source, 'image' => $imagePath, 'category_id' => $guides->id, 'created_at' => $date, 'updated_at' => $date]);
        }

        foreach ($articles as $item) {
            $category = Category::firstOrCreate(['slug' => str($item['category'])->slug()], ['name' => $item['category']]);
            $slug = str($item['title'])->slug();
            $date = now()->subDays($item['days']);
            Blog::firstOrCreate(['slug' => $slug], ['title' => $item['title'], 'excerpt' => $item['excerpt'], 'description' => $item['article'].'<p>Foto merupakan ilustrasi suasana perjalanan.</p>', 'image' => $this->store($item['image'], 'blog/'.$slug.'.jpg'), 'category_id' => $category->id, 'created_at' => $date, 'updated_at' => $date]);
        }
    }

    private function store(string $file, string $path): string
    {
        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents(public_path('frontend/assets/img/'.$file)));
        }

        return $path;
    }
}
