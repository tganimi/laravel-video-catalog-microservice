<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Genre;

class GenreTest extends TestCase
{
    use DatabaseMigrations;
    
    public function testList()
    {
        factory(Genre::class, 1)->create();
        $genres = Genre::all();
        $this->assertCount(1, $genres);
        $genresKey = array_keys($genres->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ], 
            $genresKey
        );
    }

    public function testCreate()
    {
        $genre = Genre::create([
            'name' => 'Test'
        ]);
        $genre->refresh();

        $this->assertEquals('Test', $genre->name);
        $this->assertNull($genre->description);
        $this->assertTrue($genre->is_active);

        $genre = Genre::create([
            'name' => 'Test',
            'description' => null
        ]);

        $this->assertNull($genre->description);

        $genre = Genre::create([
            'name' => 'Test',
            'description' => 'test description'
        ]);

        $this->assertEquals('test description', $genre->description);

        $genre = Genre::create([
            'name' => 'Test',
            'is_active' => false
        ]);

        $this->assertFalse($genre->is_active);

        $genre = Genre::create([
            'name' => 'Test',
            'is_active' => true
        ]);

        $this->assertTrue($genre->is_active);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create([
            'description' => 'test description',
            'is_active' => false
        ])->first();

        $data = [
            'name' => 'name updated',
            'description' => 'description updated',
            'is_active' => true
        ];

        $genre->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $genre[$key]);
        }
    }
}
