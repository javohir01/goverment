<?php


namespace App\Repositories;


use App\Models\District;

class DistrictRepository extends BaseRepository
{
    public function __construct(District $entity)
    {
        $this->entity = $entity;
    }

}
