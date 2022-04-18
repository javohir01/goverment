<?php
namespace App\QueryFilters;

class EventName extends Filter
{
    protected $field_name = 'name';
    protected $column_name = 'name';
    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, 'like', '%'.request($this->field_name).'%');
    }
}
