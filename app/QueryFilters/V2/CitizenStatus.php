<?php


namespace App\QueryFilters\V2;


use App\QueryFilters\Filter;

class CitizenStatus extends Filter
{
    protected $field_name = 'citizen_status_id';
    protected $column_name = 'citizen_status_ids';
    protected function applyFilter($builder)
    {
        return $builder->whereHas('citizenAction', function ($query) {
            $query->select('pin');
            $query->whereRaw("'".request($this->field_name)."'". " = any (string_to_array(citizen_status_ids, ','))");
        });
    }
}
