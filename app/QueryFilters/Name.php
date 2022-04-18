<?php
namespace App\QueryFilters;

class Name extends Filter
{
    protected $column_name = 'name';
    protected $field_name = 'name';

    protected function applyFilter($builder)
    {
        $name = request('name');
        $name = mb_strtoupper($name);
        return $builder->where(function ($query) use ($name){
            return $query->whereRaw("concat(surname,' ',firstname, ' ', patronymic) like ?", "%{$name}%")
                         ->orWhereRaw("concat(surname,' ',patronymic, ' ', firstname) like ?", "%{$name}%")
                         ->orWhereRaw("concat(firstname,' ',surname, ' ', patronymic) like ?", "%{$name}%")
                         ->orWhereRaw("concat(firstname,' ',patronymic, ' ', surname) like ?", "%{$name}%")
                         ->orWhereRaw("concat(patronymic,' ',firstname, ' ', surname) like ?", "%{$name}%")
                         ->orWhereRaw("concat(patronymic,' ',surname, ' ', firstname) like ?", "%{$name}%");
        });
    }

}
