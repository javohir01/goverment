<?php
namespace App\QueryFilters;

class Tin extends Filter
{
    protected $column_name = 'tin';
    protected $field_name = 'tin';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));       
    }
}