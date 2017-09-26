<?php

namespace Serializer;

use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid type invalid
     */
    public function test_invalid_type()
    {
        $serializer = new Serializer();
        $serializer->serialize('', 'invalid');
    }

    public function test_json_scalar()
    {
        $serializer = new Serializer();

        $this->assertEquals(1, $serializer->serialize(1, 'json'));
    }

    public function test_json_array()
    {
        $serializer = new Serializer();

        $this->assertEquals("[1,2]", $serializer->serialize([1, 2], 'json'));
    }

    public function test_json_object()
    {
        $serializer = new Serializer();

        $object = new \stdClass();
        $object->prop = 'value';

        $this->assertEquals('{"prop":"value"}', $serializer->serialize($object, 'json'));
    }

    public function test_json_anonymous_object()
    {
        $serializer = new Serializer();

        $object = new class
        {
            public $prop = 'value';
        };

        $this->assertEquals('{"prop":"value"}', $serializer->serialize($object, 'json'));
    }

    public function test_php_scalar()
    {
        $serializer = new Serializer();

        $this->assertEquals('i:1;', $serializer->serialize(1, 'php'));
    }

    public function test_php_array()
    {
        $serializer = new Serializer();

        $this->assertEquals("a:2:{i:0;i:1;i:1;i:2;}", $serializer->serialize([1, 2], 'php'));
    }

    public function test_php_object()
    {
        $serializer = new Serializer();

        $object = new \stdClass();
        $object->prop = 'value';

        $this->assertEquals('O:8:"stdClass":1:{s:4:"prop";s:5:"value";}', $serializer->serialize($object, 'php'));
    }

    public function test_php_anonymous_object()
    {
        $serializer = new Serializer();

        $object = new class
        {
            public $prop = 'value';
        };

        $this->assertEquals(null, $serializer->serialize($object, 'php'));
    }

    public function test_serialization_count()
    {
        $serilizer = new Serializer();
        $serilizer->serialize(1, 'json');
        $serilizer->serialize(1, 'json');
        $serilizer->serialize(1, 'php');
        $serilizer->serialize(2, 'php');

        $this->assertEquals(
            [
                'json' => [1 => 2],
                'php' => ['i:1;' => 1, 'i:2;' => 1]
            ],
            $serilizer->getCounter()
        );
    }

}
