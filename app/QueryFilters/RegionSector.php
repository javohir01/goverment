<?php
namespace App\QueryFilters;

class RegionSector extends Filter
{
    protected $field_name = 'region_sector';
    protected $column_name = 'region_sector';
    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));
    }
}