<?php
namespace App\QueryFilters;

class FullName extends Filter
{
    protected $field_name = 'full_name';
    protected $column_name = 'full_name';
    protected function applyFilter($builder)
    {
        $full_name = request('full_name');
        return $builder->where(function ($query) use ($full_name){
            return $query->where('full_name', 'LIKE', $full_name . '%');
        });
    }
}
