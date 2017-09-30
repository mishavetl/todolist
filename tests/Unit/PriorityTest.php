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

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testWithTwoElements()
    {
        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(0), new TestModel(9)];
        $after = [];

        $this->assertEquals(10, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($before[0]->saved);
        $this->assertFalse($before[1]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(-3)];
        $after = [new TestModel(0)];

        $this->assertEquals(-1, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($before[0]->saved);
        $this->assertFalse($after[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(-5)];
        $after = [new TestModel(0)];

        $this->assertEquals(-2, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($before[0]->saved);
        $this->assertFalse($after[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(-10)];
        $after = [new TestModel(10)];

        $this->assertEquals(0, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($before[0]->saved);
        $this->assertFalse($after[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(-11)];
        $after = [new TestModel(-5)];

        $this->assertEquals(-8, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertFalse($before[0]->saved);
        $this->assertFalse($after[0]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [];
        $after = [new TestModel(-11), new TestModel(-10)];

        $this->assertEquals(-11, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertEquals(-6, $after[0]->priority);
        $this->assertEquals(0, $after[1]->priority);
        $this->assertTrue($after[0]->saved);
        $this->assertTrue($after[1]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(9), new TestModel(10)];
        $after = [];

        $this->assertEquals(10, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertEquals(-1, $before[0]->priority);
        $this->assertEquals(5, $before[1]->priority);
        $this->assertTrue($before[0]->saved);
        $this->assertTrue($before[1]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [new TestModel(9)];
        $after = [new TestModel(10)];

        $this->assertEquals(9, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertEquals(-1, $before[0]->priority);
        $this->assertTrue($before[0]->saved);
        $this->assertFalse($after[0]->saved);
    }


    /**
    * A basic test example.
    *
    * @return void
    */
    public function testMixed()
    {
        $priorityMin = -11;
        $priorityMax = 10;
        $before = [];
        $after = [
            new TestModel(-11), new TestModel(-10),
            new TestModel(-9), new TestModel(-8),
            new TestModel(-7), new TestModel(-6),
            new TestModel(-5), new TestModel(-4),
            new TestModel(-3), new TestModel(-2),
            new TestModel(-1), new TestModel(0),
        ];

        $this->assertEquals(-11, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertEquals(-10, $after[0]->priority);
        $this->assertEquals(-8, $after[1]->priority);
        $this->assertEquals(-7, $after[2]->priority);
        $this->assertEquals(-6, $after[3]->priority);
        $this->assertEquals(-5, $after[4]->priority);
        $this->assertEquals(-4, $after[5]->priority);
        $this->assertEquals(-3, $after[6]->priority);
        $this->assertEquals(-2, $after[7]->priority);
        $this->assertEquals(-1, $after[8]->priority);
        $this->assertEquals(0, $after[9]->priority);
        $this->assertEquals(2, $after[10]->priority);
        $this->assertEquals(5, $after[11]->priority);
        $this->assertTrue($after[0]->saved);
        $this->assertTrue($after[1]->saved);
        $this->assertTrue($after[2]->saved);
        $this->assertTrue($after[3]->saved);
        $this->assertTrue($after[4]->saved);
        $this->assertTrue($after[5]->saved);
        $this->assertTrue($after[6]->saved);
        $this->assertTrue($after[7]->saved);
        $this->assertTrue($after[8]->saved);
        $this->assertTrue($after[9]->saved);
        $this->assertTrue($after[10]->saved);

        $priorityMin = -11;
        $priorityMax = 10;
        $before = [];
        $after = [
            new TestModel(-11), new TestModel(-9),
            new TestModel(-8), new TestModel(-7),
            new TestModel(-6), new TestModel(-5),
            new TestModel(-4), new TestModel(-3),
            new TestModel(-2), new TestModel(-1),
            new TestModel(0), new TestModel(1),
        ];

        $this->assertEquals(-11, getPriority($before, $after, $priorityMin, $priorityMax));
        $this->assertEquals(-10, $after[0]->priority);
        $this->assertEquals(-9, $after[1]->priority);
        $this->assertEquals(-8, $after[2]->priority);
        $this->assertEquals(-7, $after[3]->priority);
        $this->assertEquals(-6, $after[4]->priority);
        $this->assertEquals(-5, $after[5]->priority);
        $this->assertEquals(-4, $after[6]->priority);
        $this->assertEquals(-3, $after[7]->priority);
        $this->assertEquals(-2, $after[8]->priority);
        $this->assertEquals(-1, $after[9]->priority);
        $this->assertEquals(0, $after[10]->priority);
        $this->assertEquals(1, $after[11]->priority);
        $this->assertTrue($after[0]->saved);
        $this->assertFalse($after[1]->saved);
        $this->assertFalse($after[2]->saved);
        $this->assertFalse($after[3]->saved);
        $this->assertFalse($after[4]->saved);
        $this->assertFalse($after[5]->saved);
        $this->assertFalse($after[6]->saved);
        $this->assertFalse($after[7]->saved);
        $this->assertFalse($after[8]->saved);
        $this->assertFalse($after[9]->saved);
        $this->assertFalse($after[10]->saved);
    }
}
