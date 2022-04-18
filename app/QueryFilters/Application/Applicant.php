<?php
namespace App\QueryFilters\Application;
use App\QueryFilters\Filter;
class Applicant extends Filter
{
    protected $column_name = 'applicant';
    protected $field_name = 'applicant';

    protected function applyFilter($builder)
    {
        return $builder->where($this->column_name, request($this->field_name));       
    }
}