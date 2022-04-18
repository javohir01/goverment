<?php
namespace App\QueryFilters\Media;
use App\QueryFilters\Filter;
class Type extends Filter
{
    protected $column_name = 'type';
    protected $field_name = 'type';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));
    }
}
