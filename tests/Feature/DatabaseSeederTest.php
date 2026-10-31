<?php

namespace Tests\Feature;

use App\Models\JenisDokumen;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_the_required_document_types(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(4, JenisDokumen::query()->where('aktif', true)->where('wajib', true)->count());
    }
}
