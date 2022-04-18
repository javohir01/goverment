<?php
namespace App\QueryFilters;

class Address extends Filter
{
    protected $column_name = "address";
    protected $field_name = "address";

    protected function applyFilter($builder)
    {
        $name = request('address');
        $name = mb_strtoupper($name);
        return $builder->whereRaw("address like ?", "{$name}%");
    }

}
