<?php
namespace App\QueryFilters;

class PhoneNumber extends Filter
{
    protected $field_name = 'phone_number';
    protected $column_name = 'phone_number';
    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, 'LIKE', '%' . request($this->field_name) . '%');
    }
}
