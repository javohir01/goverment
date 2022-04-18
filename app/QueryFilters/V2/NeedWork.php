<?php
namespace App\QueryFilters\V2;

use App\QueryFilters\Filter;

class NeedWork extends Filter
{
    protected $column_name = 'need_work';
    protected $field_name = 'need_work';

    protected function applyFilter($builder)
    {
        if(request($this->field_name) == 3) {
            return $builder->whereHas('citizenAction', function ($query) {
                $query->where('status', 3);
            });
        } else if(request($this->field_name) == 4) {
            return $builder->whereHas('survey', function ($query) {
                $query->select('pin');
                $query->whereNotNull('sip_problem_id');
            });
        } else {
            return $builder->whereHas('survey', function ($query) {
                $query->select('pin');
                $query->where($this->column_name, request($this->field_name));
            });
        }
    }
}
