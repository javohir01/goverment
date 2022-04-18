<?php
namespace App\QueryFilters\Application;
use App\QueryFilters\Filter;
class DenyReason extends Filter
{
    protected $column_name = 'deny_reason_id';
    protected $field_name = 'deny_reason_id';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));
    }
}
