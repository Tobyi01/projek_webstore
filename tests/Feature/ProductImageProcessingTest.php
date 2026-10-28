<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Tests\TestCase;

class ProductImageProcessingTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_and_update_process_product_images_as_webp(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $productData = [
            'name' => 'Produk Gambar',
            'price' => 10000,
            'stock' => 3,
        ];

        $this->actingAs($admin)
            ->post(route('products.store'), $productData + [
                'image' => UploadedFile::fake()->image('product.png', 1200, 900),
            ])
            ->assertRedirect(route('products.index'));

        $product = Product::where('name', 'Produk Gambar')->firstOrFail();
        $oldImage = $product->image;
        $this->assertStringEndsWith('.webp', $oldImage);
        Storage::disk('public')->assertExists($oldImage);

        $processedImage = (new ImageManager(Driver::class))
            ->read(Storage::disk('public')->get($oldImage));
        $this->assertSame(800, $processedImage->width());
        $this->assertSame(800, $processedImage->height());

        $this->actingAs($admin)
            ->put(route('products.update', $product), $productData + [
                'image' => UploadedFile::fake()->image('replacement.jpg', 900, 1200),
            ])
            ->assertRedirect(route('products.index'));

        $product->refresh();
        $this->assertNotSame($oldImage, $product->image);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($product->image);
    }
}
