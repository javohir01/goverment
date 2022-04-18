<?php
namespace App\QueryFilters;

class Makhalla extends Filter
{
    protected $column_name = 'makhalla_id';
    protected $field_name = 'makhalla_id';

    protected function applyFilter($builder)
    {
        $makhalla_id = request('makhalla_id');
        return $builder->where(function ($query) use ($makhalla_id){
            return $query->where('makhalla_id', $makhalla_id);
        });
    }

}