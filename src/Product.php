<?php

namespace App;

class Product
{
    public $make;
    public $model;
    public $colour;
    public $capacity;
    public $network;
    public $grade;
    public $condition;

    public function __construct(array $data)
    {
        $requiredFields = ['brand_name', 'model_name'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \Exception("Required field '{$field}' is missing.");
            }
        }

        $this->make = $data['brand_name'];
        $this->model = $data['model_name'];
        $this->colour = $data['colour_name'] ?? null;
        $this->capacity = $data['gb_spec_name'] ?? null;
        $this->network = $data['network_name'] ?? null;
        $this->grade = $data['grade_name'] ?? null;
        $this->condition = $data['condition_name'] ?? null;
    }
}
