<?php


namespace App\QueryFilters;


class SocialStatus extends Filter
{
    protected $field_name = 'social_id';
    protected $column_name = 'social_id';
    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));
    }
}
