<?php
namespace App\QueryFilters;

class Role extends Filter
{
    protected $field_name = 'role_id';
    protected $column_name = 'role_id';
    protected function applyFilter($builder)
    {
        $role_id = request('role_id');
        return $builder->wherehas('roles', function ($query) use ($role_id) {
            $query->where('id', $role_id);
        });
    }
}
