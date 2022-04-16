<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ReportAgencyApplicationThreeResource;
use App\Http\Resources\ReportAgencyApplicationThreeResourceCollection;
use App\Repositories\ReportRepository;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $response = [
        'success' => true,
        'result' => [],
        'error' => []
    ];
    private $repo;
    public function __construct()
    {
        $this->repo = new ReportRepository();
    }
    public function report(Request $request)
    {
        if (!empty($request->all())){
//            dd($request->all());
            $region_id = $request->all();
            $count = "citizens.district_id=  BETWEEN 16 AND 30";

            $report = DB::table('citizens')
//                ->where('citizens.region_id' ,$region_id)
                ->leftJoin('districts','citizens.district_id','=','district.id')
                ->select(
                    'districts.name_cyrl as region_name',
                    'districts.id as region_id',
                    DB::raw("sum(case when citizens.district_id=district.id then 1 else 0 end) as socials"),
                    DB::raw("sum(case when citizens.social_id=1 then 1 else 0 end) as social1"),
                    DB::raw("sum(case when citizens.social_id=2 then 1 else 0 end) as social2"),
                    DB::raw("sum(case when citizens.social_id=3 then 1 else 0 end) as social3"),
                    DB::raw("sum(case when citizens.social_id=4 then 1 else 0 end) as social4"),
                    DB::raw("sum(case when citizens.social_id=5 then 1 else 0 end) as social5"),
                    DB::raw("sum(case when citizens.social_id=6 then 1 else 0 end) as social6"),
                    DB::raw("sum(case when citizens.social_id=7 then 1 else 0 end) as social7"),
                    DB::raw("sum(case when citizens.social_id=8 then 1 else 0 end) as social8"),
                    DB::raw("sum(case when citizens.social_id=9 then 1 else 0 end) as social9"),
                    DB::raw("sum(case when citizens.social_id=10 then 1 else 0 end) as social10"),
                    DB::raw("sum(case when citizens.social_id=11 then 1 else 0 end) as social11"),
                    DB::raw("sum(case when citizens.social_id=12 then 1 else 0 end) as social12"),
                    DB::raw("sum(case when citizens.social_id=13 then 1 else 0 end) as social13"),
                    DB::raw("sum(case when citizens.social_id=14 then 1 else 0 end) as social14"),
                    DB::raw("sum(case when citizens.social_id=15 then 1 else 0 end) as social15"),
                    DB::raw("sum(case when citizens.social_id=16 then 1 else 0 end) as social16"),
                    DB::raw("sum(case when citizens.social_id=17 then 1 else 0 end) as social17"),
                    DB::raw("sum(case when citizens.social_id=18 then 1 else 0 end) as social18"),
                )
                ->groupBy('districts.id','districts.name_cyrl')
                ->orderBy('districts.id')
                ->get()->toArray();
        }
        else {
            $report = DB::table('citizens')
                ->leftJoin('regions', 'citizens.region_id', '=', 'regions.id')
                ->select(
                    'regions.name_cyrl as region_name',
                    'regions.id as region_id',
                    DB::raw("sum(case when citizens.region_id is not null then 1 else 0 end) as socials"),
                    DB::raw("sum(case when citizens.social_id=1 then 1 else 0 end) as social1"),
                    DB::raw("sum(case when citizens.social_id=2 then 1 else 0 end) as social2"),
                    DB::raw("sum(case when citizens.social_id=3 then 1 else 0 end) as social3"),
                    DB::raw("sum(case when citizens.social_id=4 then 1 else 0 end) as social4"),
                    DB::raw("sum(case when citizens.social_id=5 then 1 else 0 end) as social5"),
                    DB::raw("sum(case when citizens.social_id=6 then 1 else 0 end) as social6"),
                    DB::raw("sum(case when citizens.social_id=7 then 1 else 0 end) as social7"),
                    DB::raw("sum(case when citizens.social_id=8 then 1 else 0 end) as social8"),
                    DB::raw("sum(case when citizens.social_id=9 then 1 else 0 end) as social9"),
                    DB::raw("sum(case when citizens.social_id=10 then 1 else 0 end) as social10"),
                    DB::raw("sum(case when citizens.social_id=11 then 1 else 0 end) as social11"),
                    DB::raw("sum(case when citizens.social_id=12 then 1 else 0 end) as social12"),
                    DB::raw("sum(case when citizens.social_id=13 then 1 else 0 end) as social13"),
                    DB::raw("sum(case when citizens.social_id=14 then 1 else 0 end) as social14"),
                    DB::raw("sum(case when citizens.social_id=15 then 1 else 0 end) as social15"),
                    DB::raw("sum(case when citizens.social_id=16 then 1 else 0 end) as social16"),
                    DB::raw("sum(case when citizens.social_id=17 then 1 else 0 end) as social17"),
                    DB::raw("sum(case when citizens.social_id=18 then 1 else 0 end) as social18"),
                )
                ->groupBy('regions.id', 'regions.name_cyrl')
                ->orderBy('regions.id')
                ->get()->toArray();
        }
        return response()->successJson(['report' => $report]);
    }
    public function regions()
    {
        $report = DB::table('citizens')
            ->leftJoin('regions', 'citizens.region_id', '=', 'regions.id');

        $report = $report->select(
            'regions.name_cyrl as region_name',
            'regions.id as region_id',
            DB::raw("sum(case when citizens.social_id=1 then 1 else 0 end) as social1"),
            DB::raw("sum(case when citizens.social_id=2 then 1 else 0 end) as social2"),
            DB::raw("sum(case when citizens.social_id=3 then 1 else 0 end) as social3"),
            DB::raw("sum(case when citizens.social_id=4 then 1 else 0 end) as social4"),
            DB::raw("sum(case when citizens.social_id=5 then 1 else 0 end) as social5"),
            DB::raw("sum(case when citizens.social_id=6 then 1 else 0 end) as social6"),
            DB::raw("sum(case when citizens.social_id=7 then 1 else 0 end) as social7"),
            DB::raw("sum(case when citizens.social_id=8 then 1 else 0 end) as social8"),
            DB::raw("sum(case when citizens.social_id=9 then 1 else 0 end) as social9"),
            DB::raw("sum(case when citizens.social_id=10 then 1 else 0 end) as social10"),
            DB::raw("sum(case when citizens.social_id=11 then 1 else 0 end) as social11"),
            DB::raw("sum(case when citizens.social_id=12 then 1 else 0 end) as social12"),
            DB::raw("sum(case when citizens.social_id=13 then 1 else 0 end) as social13"),
            DB::raw("sum(case when citizens.social_id=14 then 1 else 0 end) as social14"),
            DB::raw("sum(case when citizens.social_id=15 then 1 else 0 end) as social15"),
            DB::raw("sum(case when citizens.social_id=16 then 1 else 0 end) as social16"),
            DB::raw("sum(case when citizens.social_id=17 then 1 else 0 end) as social17"),
            DB::raw("sum(case when citizens.social_id=18 then 1 else 0 end) as social18"),
        )
            ->whereNotNull(['regions.id'])
            ->groupBy('regions.id', 'regions.name_cyrl')
            ->orderBy('regions.id');

        $report = $report->get()->toArray();
//        $sum = $this->getSumRegionInsurance();
//        array_unshift($report, $sum);
//        $all_data=['report'=>$report, 'report_user'=>$report_user];
        return $report;
    }
    public function districts($id)
    {
        $region_id = $id;
        $report = DB::table('citizens')
            ->where('citizens.region_id' ,$region_id)
//            ->leftJoin('regions', 'citizens.region_id', '=', 'regions.id')
            ->leftJoin('districts','citizens.district_id','=','districts.id')
            ->select(
                'districts.name_cyrl as district_name',
                'districts.id as district_id',
                DB::raw("sum(case when citizens.social_id=1 then 1 else 0 end) as social1"),
                DB::raw("sum(case when citizens.social_id=2 then 1 else 0 end) as social2"),
                DB::raw("sum(case when citizens.social_id=3 then 1 else 0 end) as social3"),
                DB::raw("sum(case when citizens.social_id=4 then 1 else 0 end) as social4"),
                DB::raw("sum(case when citizens.social_id=5 then 1 else 0 end) as social5"),
                DB::raw("sum(case when citizens.social_id=6 then 1 else 0 end) as social6"),
                DB::raw("sum(case when citizens.social_id=7 then 1 else 0 end) as social7"),
                DB::raw("sum(case when citizens.social_id=8 then 1 else 0 end) as social8"),
                DB::raw("sum(case when citizens.social_id=9 then 1 else 0 end) as social9"),
                DB::raw("sum(case when citizens.social_id=10 then 1 else 0 end) as social10"),
                DB::raw("sum(case when citizens.social_id=11 then 1 else 0 end) as social11"),
                DB::raw("sum(case when citizens.social_id=12 then 1 else 0 end) as social12"),
                DB::raw("sum(case when citizens.social_id=13 then 1 else 0 end) as social13"),
                DB::raw("sum(case when citizens.social_id=14 then 1 else 0 end) as social14"),
                DB::raw("sum(case when citizens.social_id=15 then 1 else 0 end) as social15"),
                DB::raw("sum(case when citizens.social_id=16 then 1 else 0 end) as social16"),
                DB::raw("sum(case when citizens.social_id=17 then 1 else 0 end) as social17"),
                DB::raw("sum(case when citizens.social_id=18 then 1 else 0 end) as social18"),
            )
            ->groupBy('districts.id', 'districts.name_cyrl')
            ->orderBy('districts.id')
            ->get()->toArray();
        return response()->successJson(['report' => $report]);;
    }
    public function serviceCounts()
    {
        $report=$this->repo->getServiceCounts();
        return $report;
    }
    public function CitizensServices()
    {
        $report = $this->repo->getCitizensServices();
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }



    public function reportAgency(){
        $report = $this->repo->getReportAgency();
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    public function reportEmploymentDepartment(){
        $report = $this->repo->getEmploymentDepartment();
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    public function reportEmploymentDepartmentCitizens(Request $request){
        $report = $this->repo->getEmploymentDepartmentCitizens($request);
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    public function reportAgencyApplicationTwo(){
        $report = $this->repo->getReportAgencyApplicationTwo();     // xba hisobot 2-ilova
        if ($report) {
//            $this->response['result'] = $report;
            return $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    public function reportAgencyApplicationThree(){
        $report = $this->repo->getReportAgencyApplicationThree();   // xba hisobot 3-ilova
        if ($report) {
            $report = $report->orderBy('id', 'desc');
            if (\request()->get('getAll', false)) {
                $report = $report->get();
            } else {
                $report = $report->paginate(request()->get('limit', 10));
            }
            $report_employment = ReportAgencyApplicationThreeResource::collection($report);
            return $report_employment;
//            $this->response['result'] = $report_employment;
        } else {
            $this->response['success'] = false;
            return response()->json($this->response);
        }
    }

    public function reportAgencyApplicationFour(){
        $report = $this->repo->getReportAgencyApplicationFour();    // xba hisobot 4-ilova
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    public function reportAgencyApplicationFive(){
        $report = $this->repo->getReportAgencyApplicationFive();    // xba hisobot 5-ilova
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    public function reportAgencyApplicationSix(){
        $report = $this->repo->getReportAgencyApplicationSix();    // xba hisobot 5-ilova
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    public function reportAgencyApplicationSeven(){
        $report = $this->repo->getReportAgencyApplicationSeven();  // xba hisobot 7-ilova
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    public function CitizenServicesCitizens()
    {
        $report = $this->repo->getCitizenServicesCitizens();
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    public function AbkmCitizenServicesCitizens()
    {
        $report = $this->repo->getAbkmCitizenServicesCitizens();
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    public function needHelpServices()
    {
        $report=$this->repo->getNeedHelpServices();
        if($report){
            $this->response['result']=$report;
        }
        else{
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }
    public function needHelpCitizens()
    {
        $report=$this->repo->getNeedHelpCitizens();
        if($report){
            $this->response['result']=$report;
        }
        else{
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }

    public function educationReportOne()
    {
        $report = $this->repo->getEducationReportOne();
        if($report){
            $this->response['result']=$report;
        }
        else{
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }

    public function educationReportOneByCity($region_id)
    {
        $report = $this->repo->getEducationReportOneByCity($region_id);
        if($report){
            $this->response['result']=$report;
        }
        else{
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }

    public function educationReportTwo()
    {
        $report = $this->repo->getEducationReportTwo();
        if($report){
            $this->response['result']=$report;
        }
        else{
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }

    public function educationReportThree()
    {
        $report = $this->repo->getEducationReportThree();
        if($report){
            $this->response['result']=$report;
        }
        else{
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }

    public function reportABKM()
    {
        $report = $this->repo->getABKMReport();
        if($report){
            $this->response['result']=$report;
        }
        else{
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }

    public function reportABKMByCity()
    {
        $report = $this->repo->getABKMReportByCity();
        if($report){
            $this->response['result']=$report;
        }
        else{
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }

    public function reportSectorCitizenInfo()
    {
        $report = $this->repo->getReportSectorCitizenInfo();
        if($report){
            $this->response['result']=$report;
        }
        else{
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }

    public function sectorCitizenInfo()
    {
        $report = $this->repo->getSectorCitizenInfo();
        if($report){
            $this->response['result']=$report;
        }
        else{
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }

}
