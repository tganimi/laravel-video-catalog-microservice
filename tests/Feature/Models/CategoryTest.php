<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Category::class, 1)->create();
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $categoryKey = array_keys($categories->first()->getAttributes());
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
            $categoryKey
        );
    }

    public function testCreate()
    {
        $category = Category::create([
            'name' => 'Test'
        ]);
        $category->refresh();

        $this->assertEquals(36, strlen($category->id));
        $this->assertEquals('Test', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category = Category::create([
            'name' => 'Test',
            'description' => null
        ]);

        $this->assertNull($category->description);

        $category = Category::create([
            'name' => 'Test',
            'description' => 'test description'
        ]);

        $this->assertEquals('test description', $category->description);

        $category = Category::create([
            'name' => 'Test',
            'is_active' => false
        ]);

        $this->assertFalse($category->is_active);

        $category = Category::create([
            'name' => 'Test',
            'is_active' => true
        ]);

        $this->assertTrue($category->is_active);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'description' => 'test description',
            'is_active' => false
        ])->first();

        $data = [
            'name' => 'name updated',
            'description' => 'description updated',
            'is_active' => true
        ];

        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category[$key]);
        }
    }

    public function testDelete()
    {
        $category = factory(Category::class)->create();
        $category->delete();
        $this->assertNull(Category::find($category->id));

        $category->restore();
        $this->assertNotNull(Category::find($category->id));
    }
}
