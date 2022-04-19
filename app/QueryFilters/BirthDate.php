<?php
namespace App\QueryFilters;
use App\QueryFilters\Filter;
use Carbon\Carbon;

class BirthDate extends Filter
{
    protected $column_name = 'birth_date';
    protected $field_name = 'birth_date';

    protected function applyFilter($builder)
    {
        $birth_date = Carbon::parse(request($this->field_name))->format('Y-m-d');
        return $builder->whereDate($this->column_name, $birth_date);
    }
}
