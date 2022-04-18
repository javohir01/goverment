<?php
namespace App\QueryFilters;

class RemoveType extends Filter
{
    protected $field_name = 'reason_id';

    protected function applyFilter($builder)
    {
        return $builder->wherehas('survey', function ($query) {
            $query->select('pin');
            $query->whereHas('citizenHistory', function ($q) {
                $q->where('reason_id', request($this->field_name));
            });
        });
    }
}
