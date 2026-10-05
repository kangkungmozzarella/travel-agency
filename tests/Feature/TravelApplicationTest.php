<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TravelApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function package(array $attributes = []): TravelPackage
    {
        return TravelPackage::create($attributes + ['location' => 'Bali', 'slug' => 'bali', 'type' => '3 hari 2 malam', 'price' => 2500000, 'description' => '<p>Deskripsi perjalanan.</p>']);
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_public_pages_handle_empty_data_and_packages_without_photos(): void
    {
        $this->get('/')->assertOk()->assertInertia(fn (Assert $page) => $page->component('Home')->has('packages.data', 0));
        $package = $this->package();
        $this->get('/travel-packages')->assertOk()->assertInertia(fn (Assert $page) => $page->component('Packages/Index')->has('packages.data', 1)->where('packages.data.0.image_url', asset('images/placeholder.svg')));
        $this->get('/travel-packages/'.$package->slug)->assertOk()->assertInertia(fn (Assert $page) => $page->component('Packages/Show')->where('package.id', $package->id)->has('package.galleries', 0));
        $this->get('/blogs')->assertOk();
        $this->get('/contact')->assertOk();
        $this->get('/halaman-tidak-ada')->assertNotFound()->assertInertia(fn (Assert $page) => $page->component('Error')->where('status', 404));
    }

    public function test_catalogue_filters_and_sorts_in_the_database(): void
    {
        $this->package();
        $this->package(['location' => 'Lombok', 'slug' => 'lombok', 'price' => 1000000]);
        $this->get('/travel-packages?search=Lombok&sort=price_asc')->assertInertia(fn (Assert $page) => $page->has('packages.data', 1)->where('packages.data.0.location', 'Lombok'));
        $this->get('/travel-packages?sort=price_asc')->assertInertia(fn (Assert $page) => $page->where('packages.data.0.location', 'Lombok'));
    }

    public function test_booking_validates_input_and_never_accepts_status_from_visitors(): void
    {
        $package = $this->package();
        $this->from('/travel-packages/bali')->post('/booking', ['name' => 'Peserta', 'email' => 'invalid', 'number_phone' => 'abc', 'date' => now()->subDay()->toDateString(), 'travel_package_id' => 999])->assertSessionHasErrors(['email', 'number_phone', 'date', 'travel_package_id']);
        $this->assertDatabaseCount('bookings', 0);
        $this->post('/booking', ['name' => 'Peserta', 'email' => 'peserta@example.com', 'number_phone' => '081234567890', 'date' => now()->addWeek()->toDateString(), 'travel_package_id' => $package->id, 'status' => 'confirmed'])->assertRedirect()->assertSessionHas('message');
        $this->assertDatabaseHas('bookings', ['email' => 'peserta@example.com', 'status' => 'pending']);
    }

    public function test_admin_routes_require_login_and_admin_role(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($this->admin())->get('/admin/dashboard')->assertOk()->assertInertia(fn (Assert $page) => $page->component('Admin/Dashboard')->where('stats.bookings', 0));
    }

    public function test_admin_creates_duplicate_destination_slugs_and_only_stores_validated_fields(): void
    {
        $this->package();
        $this->actingAs($this->admin())->post('/admin/travel_packages', ['location' => 'Bali', 'type' => '5 hari', 'price' => 4000000, 'description' => 'Deskripsi', 'id' => 100, 'slug' => 'malicious'])->assertRedirect();
        $this->assertDatabaseHas('travel_packages', ['slug' => 'bali-2']);
        $this->assertDatabaseMissing('travel_packages', ['slug' => 'malicious']);
        $this->post('/admin/travel_packages', ['location' => 'Invalid', 'type' => '5 hari', 'price' => -1, 'description' => 'Deskripsi'])->assertSessionHasErrors('price');
    }

    public function test_package_deletion_preserves_booking_history_and_cleans_unused_photos(): void
    {
        Storage::fake('public');
        $package = $this->package();
        Storage::disk('public')->put('gallery/test.jpg', 'photo');
        $package->galleries()->create(['name' => 'Pantai', 'images' => 'gallery/test.jpg']);
        $booking = Booking::create(['name' => 'Peserta', 'email' => 'peserta@example.com', 'number_phone' => '081234567890', 'date' => now()->addWeek()->toDateString(), 'travel_package_id' => $package->id]);
        $this->actingAs($this->admin())->delete('/admin/travel_packages/'.$package->id)->assertSessionHasErrors('delete');
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
        Storage::disk('public')->assertExists('gallery/test.jpg');
        $booking->delete();
        $this->delete('/admin/travel_packages/'.$package->id)->assertSessionHas('message');
        $this->assertDatabaseMissing('travel_packages', ['id' => $package->id]);
        Storage::disk('public')->assertMissing('gallery/test.jpg');
    }

    public function test_gallery_parent_is_checked_and_extra_fields_are_ignored(): void
    {
        Storage::fake('public');
        $first = $this->package();
        $second = $this->package(['location' => 'Lombok', 'slug' => 'lombok']);
        $this->actingAs($this->admin())->post('/admin/travel_packages/'.$first->id.'/galleries', ['name' => 'Pantai', 'images' => UploadedFile::fake()->image('beach.jpg'), 'travel_package_id' => $second->id])->assertRedirect();
        $gallery = Gallery::firstOrFail();
        $this->assertEquals($first->id, $gallery->travel_package_id);
        $this->post('/admin/travel_packages/'.$first->id.'/galleries/'.$gallery->id, ['_method' => 'put', 'name' => 'Pantai diperbarui', 'images' => null])->assertSessionHasNoErrors();
        $this->assertEquals('Pantai diperbarui', $gallery->fresh()->name);
        $this->get('/admin/travel_packages/'.$second->id.'/galleries/'.$gallery->id.'/edit')->assertNotFound();
        $this->delete('/admin/travel_packages/'.$second->id.'/galleries/'.$gallery->id)->assertNotFound();
        $this->assertDatabaseHas('galleries', ['id' => $gallery->id]);
    }

    public function test_blog_upload_update_related_articles_and_category_deletion(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Panduan', 'slug' => 'panduan']);
        $data = ['title' => 'Cerita Bali', 'excerpt' => 'Ringkasan', 'description' => '<p>Cerita perjalanan</p>', 'category_id' => $category->id, 'reads' => 9000];
        $this->actingAs($this->admin())->post('/admin/blogs', $data + ['image' => UploadedFile::fake()->image('cover.jpg')])->assertRedirect();
        $blog = Blog::firstOrFail();
        $this->assertEquals(0, $blog->reads);
        $oldImage = $blog->image;
        $this->post('/admin/blogs/'.$blog->id, $data + ['_method' => 'put', 'image' => UploadedFile::fake()->image('new.jpg')])->assertRedirect();
        Storage::disk('public')->assertMissing($oldImage);
        $this->assertEquals(0, $blog->fresh()->reads);
        $this->delete('/admin/categories/'.$category->id)->assertSessionHasErrors('delete');
        $this->get('/blogs/'.$blog->slug)->assertInertia(fn (Assert $page) => $page->component('Blogs/Show')->where('blog.reads', 1));
        $this->assertDatabaseHas('blogs', ['id' => $blog->id, 'reads' => 1]);
    }

    public function test_admin_can_update_booking_status_and_invalid_status_is_rejected(): void
    {
        $package = $this->package();
        $booking = Booking::create(['name' => 'Peserta', 'email' => 'peserta@example.com', 'number_phone' => '081234567890', 'date' => now()->addWeek()->toDateString(), 'travel_package_id' => $package->id]);
        $this->actingAs($this->admin())->patch('/admin/bookings/'.$booking->id, ['status' => 'confirmed'])->assertSessionHas('message');
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'confirmed']);
        $this->patch('/admin/bookings/'.$booking->id, ['status' => 'invalid'])->assertSessionHasErrors('status');
    }

    public function test_auth_and_profile_use_inertia_and_hash_password_changes(): void
    {
        $this->get('/login')->assertInertia(fn (Assert $page) => $page->component('Auth/Login'));
        $this->get('/password/reset')->assertInertia(fn (Assert $page) => $page->component('Auth/ForgotPassword'));
        $admin = $this->admin();
        $this->actingAs($admin)->put('/admin/profile', ['name' => 'Admin Baru', 'email' => $admin->email, 'password' => 'newpassword123', 'password_confirmation' => 'newpassword123'])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('newpassword123', $admin->fresh()->password));
    }
}
