<?php


namespace App\Services;


use App\Repositories\RegionRepository;

class RegionService extends BaseService
{
    protected $filter_fields;

    public function __construct(RegionRepository $repo)
    {
        $this->repo = $repo;
        $this->filter_fields = ['name' => ['type' => 'string'], 'username' => ['type' => 'string'], 'status' => ['type' => 'number']];
        $this->relation = [];
        $this->attributes = ['*'];

        $this->sort_fields = [];
    }
}
