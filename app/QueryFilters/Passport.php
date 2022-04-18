<?php
namespace App\QueryFilters;

class Passport extends Filter
{
    protected $column_name = 'passport';
    protected $field_name = 'passport';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, 'LIKE', request($this->field_name) . '%');
    }

}
