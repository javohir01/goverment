<?php
namespace App\QueryFilters;

use Closure;

abstract class Filter
{
    protected $field_name;
    protected $column_name;
    
    public function handle($query, Closure $next)
    {
        if (!request()->has($this->field_name) || request()->get($this->field_name) == '' ) {
            return $next($query);
        }
        $builder = $next($query);
        return $this->applyFilter($builder);
        
    }
    protected abstract function applyFilter($builder);
}