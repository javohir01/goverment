<?php
namespace App\QueryFilters\V2;

use App\QueryFilters\Filter;

class Makhalla extends Filter
{
    protected $column_name = 'makhalla_id';
    protected $field_name = 'makhalla_id';

    protected function applyFilter($builder)
    {
        return $builder->whereHas('survey', function ($query) {
            $query->select('pin');
            $query->where($this->column_name, request($this->field_name));
        });
    }
}
