<?php

namespace Tests\Fixtures;

use App\Models\Blog;
use App\Models\Booking;
use App\Models\Category;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BrowserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('testing') || config('database.default') !== 'sqlite') {
            throw new \RuntimeException('Browser fixtures are only allowed in the isolated SQLite testing environment.');
        }
        User::forceCreate(['name' => 'Admin Uji', 'email' => 'browser@example.test', 'password' => Hash::make('BrowserTest123!'), 'is_admin' => true]);
        $category = Category::create(['name' => 'Panduan (data uji)', 'slug' => 'panduan-uji']);
        foreach ([['Bali', 'bali.jpg', 2500000], ['Lombok', 'travel-1.jpg', 1800000], ['Labuan Bajo', 'travel-2.jpg', 3500000], ['Bromo', 'mount.jpg', 1500000]] as $index => [$location, $image, $price]) {
            $package = TravelPackage::create(['location' => $location.' (data uji)', 'slug' => strtolower(str_replace(' ', '-', $location)).'-uji', 'type' => '3 hari 2 malam', 'price' => $price, 'description' => '<p>Contoh deskripsi untuk pengujian tampilan dan formulir perjalanan.</p><h2>Rencana perjalanan</h2><ul><li>Contoh jadwal kegiatan.</li><li>Detail dikonfirmasi bersama tim.</li></ul>']);
            foreach ([$image, 'hero.jpg'] as $photoIndex => $photo) {
                $path = 'browser-fixtures/'.$index.'-'.$photoIndex.'.jpg';
                Storage::disk('public')->put($path, file_get_contents(public_path('frontend/assets/img/'.$photo)));
                $package->galleries()->create(['name' => 'Foto contoh '.($photoIndex + 1), 'images' => $path]);
            }
            Blog::create(['title' => 'Catatan '.$location.' (data uji)', 'slug' => 'catatan-'.$package->slug, 'excerpt' => 'Artikel contoh untuk menguji tampilan jurnal perjalanan.', 'description' => '<p>Konten ini adalah data uji, bukan informasi paket yang dijual.</p>', 'image' => 'browser-fixtures/'.$index.'-0.jpg', 'category_id' => $category->id]);
            Booking::create(['name' => 'Peserta Uji '.($index + 1), 'email' => 'peserta'.$index.'@example.test', 'number_phone' => '081234567890', 'date' => now()->addDays($index + 7)->toDateString(), 'travel_package_id' => $package->id, 'status' => $index % 2 ? 'confirmed' : 'pending']);
        }
    }
}
