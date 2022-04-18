<?php
namespace App\QueryFilters;

class Username extends Filter
{
    protected $field_name = 'username';
    protected $column_name = 'username';
    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, 'LIKE', '%' . request($this->field_name) . '%');
    }
}
