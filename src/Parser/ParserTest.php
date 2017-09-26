<?php

namespace Parser;

use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function test_parse_numeric()
    {
        $parser = new Parser();

        $this->assertEquals(
            1,
            $parser->parse('1')
        );
    }

    public function test_parse_string()
    {
        $parser = new Parser();

        $this->assertEquals(
            'string',
            $parser->parse('string')
        );
    }

    public function test_parse_json_array()
    {
        $parser = new Parser();

        $this->assertEquals(
            [1, 'time' => (new \DateTime())->format('Y:m:d H:i')],
            $parser->parse('[1]')
        );
    }


    public function test_parse_json_object()
    {
        $parser = new Parser();

        $object = new \stdClass();
        $object->property = 'value';
        $object->time = (new \DateTime())->format('Y:m:d H:i');

        $this->assertEquals(
            $object,
            $parser->parse('{"property":"value"}')
        );
    }

    public function test_parse_json_object_with_time()
    {
        $parser = new Parser();

        $object = new \stdClass();
        $object->property = 'value';
        $object->time = "2000:10:10";

        $this->assertEquals(
            $object,
            $parser->parse('{"property":"value","time":"2000:10:10"}')
        );
    }

    public function test_parse_serialized_scalar()
    {
        $parser = new Parser();

        $this->assertEquals(1, $parser->parse('i:1;'));
    }

    public function test_parse_serialized_array()
    {
        $parser = new Parser();

        $this->assertEquals(
            [1, 2, 'time' => (new \DateTime())->format('Y:m:d H:i')],
            $parser->parse('a:2:{i:0;i:1;i:1;i:2;}')
        );
    }

    public function test_parse_serialized_array_with_time()
    {
        $parser = new Parser();

        $this->assertEquals(
            [1, 2, 'time' => '2000:10:10'],
            $parser->parse('a:3:{i:0;i:1;i:1;i:2;s:4:"time";s:10:"2000:10:10";}')
        );
    }

    public function test_parse_serialized_object()
    {
        $parser = new Parser();

        $object = new \stdClass();
        $object->property = 'value';

        $this->assertEquals(
            $object,
            $parser->parse('O:8:"stdClass":1:{s:8:"property";s:5:"value";}')
        );
    }

    public function test_parse_serialized_object_with_time()
    {
        $parser = new Parser();

        $object = new \stdClass();
        $object->property = 'value';
        $object->time = "2000:10:10";

        $this->assertEquals(
            $object,
            $parser->parse('O:8:"stdClass":2:{s:8:"property";s:5:"value";s:4:"time";s:10:"2000:10:10";}')
        );
    }

    public function test_cached_json_is_at_least_10_times_faster()
    {
        $data = [];

        for ($i = 0; $i < 1000; $i++) {
            $data[$i] = rand(0, $i);
        }

        $data = json_encode($data);

        $parser = new Parser();


        $startTime = microtime(true);
        $parser->parse($data);
        $firstDuration = microtime(true) - $startTime;
        $parser->parse($data);
        $secondDuration = microtime(true) - $firstDuration - $startTime;

        $this->assertTrue($secondDuration < $firstDuration / 10);
    }

    public function test_cached_serialized_is_at_least_10_times_faster()
    {
        $data = [];

        for ($i = 0; $i < 1000; $i++) {
            $data[$i] = rand(0, $i);
        }

        $data = serialize($data);

        $parser = new Parser();


        $startTime = microtime(true);
        $parser->parse($data);
        $firstDuration = microtime(true) - $startTime;
        $parser->parse($data);
        $secondDuration = microtime(true) - $firstDuration - $startTime;

        $this->assertTrue($secondDuration < $firstDuration / 10);
    }
}
