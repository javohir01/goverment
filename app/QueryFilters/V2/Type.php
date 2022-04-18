<?php
namespace App\QueryFilters\V2;

use App\QueryFilters\Filter;

class Type extends Filter
{
    protected $column_name = 'type';
    protected $field_name = 'type';

    protected function applyFilter($builder)
    {
        if(request($this->field_name) == 'all') {
            return $builder;
        } elseif (request($this->field_name) == 'woman') {
            return $builder->whereHas('citizenAction', function ($query) {
                $query->select('pin');
                $query->where('category_id', 4);
            });
        } else {
            return $builder->whereHas('citizenAction', function ($query) {
                $query->select('pin');
                $query->whereIn('category_id', [1, 2]);
            });
        }
    }
}
