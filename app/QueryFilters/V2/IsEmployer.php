<?php
namespace App\QueryFilters\V2;

use App\QueryFilters\Filter;

class IsEmployer extends Filter
{
    protected $column_name = 'is_employer';
    protected $field_name = 'is_employer';

    protected function applyFilter($builder)
    {
        return $builder->whereHas('citizenAction', function ($query) {
            $query->select('pin');
            $query->where($this->field_name, request($this->column_name));
        });
    }
}
