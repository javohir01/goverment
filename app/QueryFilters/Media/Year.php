<?php
namespace App\QueryFilters\Media;
use App\QueryFilters\Filter;
class Year extends Filter
{
    protected $column_name = 'year';
    protected $field_name = 'year';

    protected function applyFilter($builder)
    {
        return $builder->whereYear('created_at', request($this->field_name));
    }
}
