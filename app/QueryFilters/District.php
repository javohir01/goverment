<?php
namespace App\QueryFilters;

class District extends Filter
{
    protected $column_name = 'district_id';
    protected $field_name = 'district_id';

    protected function applyFilter($builder)
    {
        $district_id = request('district_id');
        if(is_numeric($district_id))
            return $builder->where(function ($query) use ($district_id){
                return $query->where('district_id', $district_id);
            });
        else return $builder;
    }

}
