<?php


namespace App\Repositories;


use App\Models\Region;

class RegionRepository extends BaseRepository
{
    public function __construct(Region $entity)
    {
        $this->entity = $entity;
    }

}
