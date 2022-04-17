<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Region\IndexRequest as RegionRequest;
use App\Http\Requests\District\IndexRequest as DistrictRequest;
use App\Models\DenyReason;
use App\Models\District;
use App\Models\Region;
use App\Models\SocialStatus;
use App\Services\DistrictService;
use App\Services\RegionService;
use Illuminate\Http\Request;

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
//        $params = $request->validated();
//        return [
//            'current_page' => $request->page ?? 1,
//            'per_page' => $request->limit,
//            'data' => response()->successJson($this->regionService->get($params))
//                ->get(),
////            'total' => $query->count() < $request->limit ? $citizens->count() : -1
//        ];
//        dd($this->regionService->get($params));
        $regions = Region::all();
        return response()->successJson(['regions' => $regions]);
//        return response()->successJson($this->regionService->get($params));
    }

//    public function districts(DistrictRequest $request)
//    {
//        $params = $request->validated();
////        dd($request);
//        return response()->successJson($this->districtService->get($params));
//    }
    public function districts(Request $request)
    {
        $districts = District::query();
        if (!empty($request->all()['region_id'])) {
            $districts->where('region_id', $request->all()['region_id']);
        }
        $districts = $districts->get();
        return response()->successJson(['districts' => $districts]);
    }

    public function socialStatuses(Request $request)
    {
        $social_statuses = SocialStatus::all();
        return response()->successJson(['social_statuses' => $social_statuses]);
    }

    public function denyReasons(Request $request)
    {
        $deny_reasons = DenyReason::all();
        return response()->successJson(['deny_reasons' => $deny_reasons]);
    }
}
