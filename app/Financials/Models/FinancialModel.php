<?php

namespace App\Financials\Models;

use Carbon\Carbon;
use Exception;

class FinancialModel
{
    /**
     * Data array to hold values
     *
     * @var array
     */
    protected $data;

    /**
     * The allowed data keys and types
     * The is a key => value pair where the key is the data key and the value is the type
     *
     * Example:
     *  $key = ['title' => 'string'];
     *
     * @var array
     */
    protected $keys;

    /**
     * FinancialModel constructor
     *
     * @param array|null $data
     *
     * @throws Exception
     */
    public function __construct(array $data = null)
    {
        if ($data) {
            $this->loadData($data);
        }
    }

    /**
     * Load the data and validate the data items
     *
     * @param array $data
     *
     * @throws Exception
     */
    protected function loadData(array $data)
    {
        foreach ($data as $key => $value) {
            if (!isset($this->keys[$key])) {
                 throw new Exception("FinancialModel: The key $key does not exist");
            }
            $this->data[$key] = $this->castType($this->keys[$key], $value);
        }
    }

    /**
     * Cast the given value as the given type
     *
     * @param string $type
     * @param $value
     *
     * @return mixed
     * @throws Exception
     */
    protected function castType(string $type, $value)
    {
        if ($value === '' || $value === null) {
            return null;
        }

        if ($type === 'string') {
            return (string)$value;
        } elseif ($type === 'boolean') {
            return (bool)$value;
        } elseif ($type === 'integer') {
            return (int)$value;
        } elseif ($type === 'date') {
            return (new Carbon($value))->format('Y-m-d');
        } elseif ($type === 'currency') {
            return (float)$value;
        } elseif ($type === 'array') {
            if (!is_array($value)) {
                throw new Exception("FinancialModel: The type 'array' must be an actual array");
            }
            return $value;
        } elseif ($type === 'datetime') {
            return (new Carbon($value))->format('Y-m-d H:i:s');
        } elseif (class_exists($type)) {
            if (!$value instanceof $type) {
                throw new Exception("FinancialModel: The type '$type' must implement FinancialModel");
            }
            return $value;
        } else {
            throw new Exception("FinancialModel: The type '$type' is not valid");
        }
    }

    /**
     * Set the given value
     *
     * @param string $name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get(string $name)
    {
        $key = $this->validateKey($name);

        return $this->data[$key] ?? null;
    }

    /**
     * Set the given value
     *
     * @param string $name
     * @param $value
     *
     * @return void
     * @throws Exception
     */
    public function __set(string $name, $value): void
    {
        $key = $this->validateKey($name);

        $this->data[$key] = $this->castType($this->keys[$key], $value);
    }

    /**
     * Validate the given key
     *
     * @param string $key
     *
     * @return string
     * @throws Exception
     */
    protected function validateKey(string $key): string
    {
        if (!isset($this->keys[$key])) {
            throw new Exception("FinancialModel: The method '$key' does not exist");
        }

        return $key;
    }

    /**
     * Return an array of data values
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
