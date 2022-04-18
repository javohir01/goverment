<?php
namespace App\QueryFilters;


class Pin extends Filter
{
    protected $column_name = 'pin';
    protected $field_name = 'pin';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, 'LIKE', request($this->field_name) . '%');
    }

}
