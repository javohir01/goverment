<?php
namespace App\QueryFilters\V2;

use App\QueryFilters\Filter;

class Status extends Filter
{
    protected $column_name = 'status';
    protected $field_name = 'status';

    protected function applyFilter($builder)
    {
        return $builder->whereHas('citizenAction', function ($query) {
            $query->where($this->column_name, request($this->field_name));
        });

    }
}
