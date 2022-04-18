<?php
namespace App\QueryFilters;

class IsEmployer extends Filter
{
    protected $column_name = 'is_employer';
    protected $field_name = 'is_employer';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));
    }
}