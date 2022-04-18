<?php


namespace App\QueryFilters;


class Lists extends Filter
{
    protected $column_name = 'is_list';
    protected $field_name = 'is_list';

    protected function applyFilter($builder)
    {
        if(request($this->field_name == 'true')) {
            return $builder->list();
        }
    }
}
