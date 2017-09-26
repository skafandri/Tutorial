<?php

namespace Parser;

class Parser
{
    private $cache = [];

    public function parse($data)
    {
        if (isset($this->cache[$data])) {
            return $this->cache[$data];
        }

        if (strpos($data, '[') === 0) {
            $parsed = json_decode($data);
            if (!isset($parsed['time'])) {
                $parsed['time'] = (new \DateTime())->format('Y:m:d H:i');
            }
            $this->cache[$data] = $parsed;
            return $parsed;
        }

        if (strpos($data, '{') === 0) {
            $parsed = json_decode($data);
            if (!isset($parsed->time)) {
                $parsed->time = (new \DateTime())->format('Y:m:d H:i');
            }
            $this->cache[$data] = $parsed;
            return $parsed;
        }

        if (strpos($data, ':') === 1) {
            $parsed = unserialize($data);
            if (is_array($parsed) && !isset($parsed['time'])) {
                $parsed['time'] = (new \DateTime())->format('Y:m:d H:i');
            }
            $this->cache[$data] = $parsed;
            return $parsed;
        }

        return $data;
    }
}
