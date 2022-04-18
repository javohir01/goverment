<?php
namespace App\QueryFilters;

class EventType extends Filter
{
    protected $field_name = 'event_type_id';
    protected $column_name = 'event_type_id';
    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));
    }
}
