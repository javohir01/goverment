<?php
namespace App\QueryFilters\V2;
use App\QueryFilters\Filter;
class Applicant extends Filter
{
    protected $column_name = 'applicant';
    protected $field_name = 'applicant';

    protected function applyFilter($builder)
    {
        return $builder->whereHas('survey', function ($query) {

            if(request($this->field_name) == 3){
                $query->whereNull($this->column_name)->orWhere($this->column_name, request($this->field_name));
            }
            $query->where($this->column_name, request($this->field_name));
        });
    }
}
