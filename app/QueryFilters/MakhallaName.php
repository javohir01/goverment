<?php
namespace App\QueryFilters;

class MakhallaName extends Filter
{
    protected $column_name = 'name';
    protected $field_name = 'name';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, 'LIKE', '%' . request($this->field_name) . '%');
    }

}