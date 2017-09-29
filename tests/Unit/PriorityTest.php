<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestModel {
    public $priority;
    public $saved = false;
    
    public function __construct($priority)
    {
        $this->priority = $priority;
    }

    public function save()
    {
        $this->saved = true;
    }
}

class PriorityTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEmpty()
    {
        $priorityMin = -11;
        $priorityMax = 10;
        $before = [];
        $after = [];

        $this->assertEquals(0, getPriority($before, $after, $priorityMin, $priorityMax));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
     public function testWithOneElement()
     {
        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(0)];
        $after = [];

        $this->assertEquals(5, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($before[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [];
        $after = [new TestModel(0)];

        $this->assertEquals(-6, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($after[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(-10)];
        $after = [];

        $this->assertEquals(0, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($before[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [];
        $after = [new TestModel(-5)];

        $this->assertEquals(-8, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($after[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(9)];
        $after = [];

        $this->assertEquals(10, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($before[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [];
        $after = [new TestModel(-10)];

        $this->assertEquals(-11, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($after[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(-5)];
        $after = [];

        $this->assertEquals(3, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($before[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [];
        $after = [new TestModel(-11)];

        $this->assertEquals(-11, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertEquals(0, $after[0]->priority);
        $this->assertTrue($after[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(10)];
        $after = [];

        $this->assertEquals(10, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertEquals(0, $before[0]->priority);
        $this->assertTrue($before[0]->saved);
    }

    // /**
    //  * A basic test example.
    //  *
    //  * @return void
    //  */
    // public function testWithTwoElements()
    // {
    //     $priorityMin = -11;
    //     $priorityMax = 10;
    //     $before = [new TestModel(0)];
    //     $after = [];

    //     $this->assertEquals(6, getPriority($before, $after, $priorityMin, $priorityMax));

    //     $priorityMin = -11;
    //     $priorityMax = 10;
    //     $before = [];
    //     $after = [new TestModel(0)];

    //     $this->assertEquals(-6, getPriority($before, $after, $priorityMin, $priorityMax));

    //     $priorityMin = -11;
    //     $priorityMax = 10;
    //     $before = [new TestModel(-10)];
    //     $after = [];

    //     $this->assertEquals(1, getPriority($before, $after, $priorityMin, $priorityMax));

    //     $priorityMin = -11;
    //     $priorityMax = 10;
    //     $before = [];
    //     $after = [new TestModel(-5)];

    //     $this->assertEquals(-8, getPriority($before, $after, $priorityMin, $priorityMax));

    //     $priorityMin = -11;
    //     $priorityMax = 10;
    //     $before = [new TestModel(9)];
    //     $after = [];

    //     $this->assertEquals(10, getPriority($before, $after, $priorityMin, $priorityMax));

    //     $priorityMin = -11;
    //     $priorityMax = 10;
    //     $before = [];
    //     $after = [new TestModel(-10)];

    //     $this->assertEquals(-11, getPriority($before, $after, $priorityMin, $priorityMax));

    //     $priorityMin = -11;
    //     $priorityMax = 10;
    //     $before = [new TestModel(-5)];
    //     $after = [];

    //     $this->assertEquals(3, getPriority($before, $after, $priorityMin, $priorityMax));

    //     $priorityMin = -11;
    //     $priorityMax = 10;
    //     $before = [];
    //     $after = [new TestModel(-11)];

    //     $this->assertEquals(-11, getPriority($before, $after, $priorityMin, $priorityMax));
    //     $this->assertEquals(0, $after[0]->priority);
    //     $this->assertTrue($after[0]->saved);
    // }
}
