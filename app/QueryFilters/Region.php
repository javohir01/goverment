<?php
namespace App\QueryFilters;

class Region extends Filter
{
    protected $column_name = 'region_id';
    protected $field_name = 'region_id';

    protected function applyFilter($builder)
    {
        $region_id = request('region_id');
        if(is_numeric($region_id))
            return $builder->where(function ($query) use ($region_id){
                return $query->where('region_id', $region_id);
            });
        else return $builder;
    }

}
