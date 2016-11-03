<?php

namespace tests\Mobileka\Liner;

use Mobileka\Liner\Liner;

/**
 * @covers \Mobileka\Liner\Liner
 */
class LinerTest extends BaseTestCase
{
    /**
     * @var string
     */
    protected $file;

    public function setUp()
    {
        $this->file = RESOURCES.'words';
    }

    /**
     * @test
     */
    public function is_instantiable()
    {
        new Liner(RESOURCES.'empty');
    }

    /**
     * @test
     */
    public function fetches_all_rows()
    {
        $file = new Liner($this->file);

        $result = $file->read();

        assertEquals(10, count($result));
    }

    /**
     * @test
     */
    public function sets_limit()
    {
        $file = new Liner($this->file);
        $limit = 3;

        $result = $file->read($limit);

        assertEquals($limit, count($result));
    }

    /**
     * @test
     */
    public function handles_negative_limit()
    {
        $file = new Liner($this->file);
        $limit = -10;

        $result = $file->read($limit);

        assertEquals(10, count($result));
    }

    /**
     * @test
     */
    public function handles_too_big_limit()
    {
        $file = new Liner($this->file);
        $limit = 100;

        $result = $file->read($limit);

        assertEquals(10, count($result));
    }

    /**
     * @test
     */
    public function sets_offset()
    {
        $file = new Liner($this->file);
        $offset = 3;
        $expect = 10 - $offset;

        $result = $file->read(0, $offset);

        assertEquals($expect, count($result));
    }

    /**
     * @test
     */
    public function sets_negative_offset()
    {
        $file = new Liner($this->file);
        $offset = -3;
        $expect = abs($offset);

        $result = $file->read(0, $offset);

        assertEquals($expect, count($result));
    }

    /**
     * @test
     */
    public function handles_too_big_offset()
    {
        $file = new Liner($this->file);
        $offset = 100;

        $result = $file->read(0, $offset);

        assertEquals(0, count($result));
    }

    /**
     * @test
     */
    public function handles_too_big_negative_offset()
    {
        $file = new Liner($this->file);
        $offset = -100;

        $result = $file->read(0, $offset);

        assertEquals(10, count($result));
    }

    /**
     * @test
     */
    public function limit_and_offset_work_together()
    {
        $file = new Liner($this->file);
        $offset = 3;
        $limit = 1;

        $result = $file->read($limit, $offset);

        assertEquals($limit, count($result));
    }

    /**
     * @test
     */
    public function modifies_read_data()
    {
        // Arrange
        $file = new Liner($this->file);
        $lines = $file->read();
        $expect = [];

        foreach ($lines as $line) {
            $expect[] = strrev($line);
        }

        // Act
        $result = $file->read(0, 0, function ($file, $line) {
            return strrev($line);
        });

        // Assert
        assertSame($expect, $result);
    }

    /**
     * @test
     */
    public function ignores_modified_value_when_its_null()
    {
        // Arrange
        $file = new Liner($this->file);

        // Act
        $result = $file->read(0, 0, function ($file, $line) {
            $line = trim($line);

            if ($line == 'a') {
                return null;
            }

            return $line;
        });

        // Assert
        assertSame(9, count($result));
    }

    /**
     * @test
     */
    public function counts_number_of_lines_when_ignores_modified_values()
    {
        // Arrange
        $file = new Liner($this->file);
        $file->read(0, 0, function ($file, $line) {
            $line = trim($line);

            if ($line == 'a') {
                return null;
            }

            return $line;
        });

        // Act
        $result = $file->getNumberOfLines();

        // Assert
        assertSame(9, $result);
    }

    /**
     * @test
     */
    public function delegates_methods_to_spl_file_object()
    {
        $file = new Liner($this->file);

        $result = $file->eof();

        assertFalse($result);
    }

    /**
     * @test
     * @expectedException \PHPUnit_Framework_Error
     */
    public function rises_errors()
    {
        $file = new Liner($this->file);
        $file->oops();
    }
}
