<?php
namespace App\QueryFilters;

class Category extends Filter
{
    protected $column_name = 'category_id';
    protected $field_name = 'category_id';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));       
    }
}