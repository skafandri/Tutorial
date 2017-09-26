<?php

namespace Serializer;
/**
 * Class Serializer
 * @package Serializer
 *
 */
class Serializer
{
    private $counter = [];

    public function serialize($data, $type): ?string
    {
        $serialized = null;
        if (!isset($this->counter[$type])) {
            $this->counter[$type] = [];
        }

        switch ($type) {
            case 'json':
                $serialized = json_encode($data);
                break;
            case 'php':
                try {
                    $serialized = serialize($data);
                } catch (\Exception $exception) {
                }
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Invalid type %s', $type));
        }

        if (isset($this->counter[$type][$serialized])) {
            $this->counter[$type][$serialized]++;
        } else {
            $this->counter[$type][$serialized] = 1;
        }
        return $serialized;
    }

    public function getCounter()
    {
        return $this->counter;
    }
}
