<?php
namespace App\QueryFilters\Application;

use App\QueryFilters\Filter;

class ReasonInNotebook extends Filter
{
    protected $column_name = 'reason_in_notebook';
    protected $field_name = 'reason_in_notebook';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));
    }
}
