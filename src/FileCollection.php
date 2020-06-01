<?php

namespace Live\Collection;

/**
 * File collection
 *
 * @package Live\Collection
 */

class FileCollection implements CollectionInterface
{
    private $filepath;
    private $file;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filepath = dirname(__FILE__) . "\arquivo.txt";
        $this->file = fopen($this->filepath, "w+");
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {
        if (!$this->has($index)) {
            return $defaultValue;
        }

        $data = $this->getData();

        $key = array_search($index, array_column($data, 0));

        if (time() > $data[$key][2]) {
            return $defaultValue;
        }

        return $data[$key][1];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, int $expiresIn = 60)
    {
        $expiresIn += time();

        if (is_array($value)) {
            $value = implode(";", $value);
        }

        $cache = $this->getData();

        $data = array($index, $value, $expiresIn, "\n");

        $key = array_search($index, array_column($cache, 0));

        if (!$key && $key !== 0) {
            $newFile = implode("|", $data);
            fwrite($this->file, $newFile);
            return;
        }

        $cache[$key] = $data;

        $this->clean();

        foreach ($cache as $i => $v) {
            $newFile = implode("|", $cache[$i]);
            fwrite($this->file, $newFile);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        $data = $this->getData();

        $key = array_search($index, array_column($data, 0));

        return ($key || $key === 0) ? true : false;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->getData());
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        file_put_contents($this->filepath, "");
    }

    private function getData()
    {
        $data = file($this->filepath);

        $map = function ($value) {
            return explode("|", $value);
        };

        $data = array_map($map, $data);

        return $data;
    }
}
