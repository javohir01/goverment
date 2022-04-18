<?php
namespace App\QueryFilters\Media;
use App\QueryFilters\Filter;
class Title extends Filter
{
    protected $column_name = 'title';
    protected $field_name = 'title';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, 'ilike', '%'.request($this->field_name).'%');
    }
}
