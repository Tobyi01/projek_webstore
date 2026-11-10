<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Platform Kasir &amp; Manajemen Toko Next-Gen', false)
            ->assertSee('Apa yang bisa dilakukan dengan NesiaStore?')
            ->assertSee('Proses transaksi penjualan')
            ->assertSee('Kelola produk dan stok')
            ->assertSee('Pantau riwayat dan aktivitas');
    }
}
