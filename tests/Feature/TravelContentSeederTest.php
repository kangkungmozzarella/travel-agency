<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Booking;
use App\Models\TravelPackage;
use Database\Seeders\TravelContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TravelContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_can_be_seeded_again_without_duplicates_or_overwriting_edits(): void
    {
        Storage::fake('public');
        $this->seed(TravelContentSeeder::class);
        $package = TravelPackage::where('slug', 'bali-ubud-sanur')->firstOrFail();
        $package->update(['description' => '<p>Konten yang diedit admin.</p>']);
        $this->seed(TravelContentSeeder::class);

        $this->assertSame(14, TravelPackage::count());
        $this->assertSame(22, Blog::count());
        $this->assertSame(0, Booking::count());
        $this->assertSame('<p>Konten yang diedit admin.</p>', $package->fresh()->description);
        $this->assertSame(2, $package->galleries()->count());
        Storage::disk('public')->assertExists($package->galleries()->first()->images);
        $this->assertFalse(TravelPackage::where('location', 'like', '%data uji%')->exists());
    }
}
