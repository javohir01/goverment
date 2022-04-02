<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Region\IndexRequest as RegionRequest;
use App\Http\Requests\District\IndexRequest as DistrictRequest;
use App\Services\DistrictService;
use App\Services\RegionService;

class ResourceController extends Controller
{
    private $regionService;
    private $districtService;

    public function __construct(RegionService $regionService, DistrictService $districtService)
    {
        $this->regionService = $regionService;
        $this->districtService = $districtService;
    }

    public function regions(RegionRequest $request)
    {
        $params = $request->validated();
//        return [
//            'current_page' => $request->page ?? 1,
//            'per_page' => $request->limit,
//            'data' => response()->successJson($this->regionService->get($params))
//                ->get(),
////            'total' => $query->count() < $request->limit ? $citizens->count() : -1
//        ];
////        dd($this->regionService->get($params));
        return response()->successJson($this->regionService->get($params));
    }

    public function districts(DistrictRequest $request)
    {
        $params = $request->validated();
//        dd($request);
        return response()->successJson($this->districtService->get($params));
    }
}
