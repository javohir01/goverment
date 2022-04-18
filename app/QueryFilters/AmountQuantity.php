<?php
namespace App\QueryFilters;

class AmountQuantity extends Filter
{
    protected $column_name = 'amount';
    protected $field_name = 'amount';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, 'LIKE', '%' . request($this->field_name) . '%');
    }

}