<?php
namespace App\QueryFilters;

class Status extends Filter
{
    protected $column_name = 'status';
    protected $field_name = 'status';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));       
    }
}