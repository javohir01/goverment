<?php
namespace App\QueryFilters;

class CitizenPhone extends Filter
{
    protected $field_name = 'citizen_phone';
    protected $column_name = 'citizen_phone';
    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, 'LIKE', '%' . request($this->field_name) . '%');
    }
}