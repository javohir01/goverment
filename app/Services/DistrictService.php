<?php


namespace App\Services;


use App\Repositories\DistrictRepository;

class DistrictService extends BaseService
{
    protected $filter_fields;

    public function __construct(DistrictRepository $repo)
    {
        $this->repo = $repo;
        $this->filter_fields = ['name' => ['type' => 'string'], 'username' => ['type' => 'string'], 'status' => ['type' => 'number']];
        $this->relation = [];
    }
}
