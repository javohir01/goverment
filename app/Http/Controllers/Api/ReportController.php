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
    public function all()
    {
        $report = $this->repo->getRegionInsurance();

        if ($report) {
            $this->response['result'] = $report;
         } else {
            $this->response['success']=false;
        }
        return response()->json($this->response);
    }
    public function info_insurance_citizen($user_id)
    {
        $report = $this->repo->getInfoInsuranceCitizen($user_id);
        if ($report) {
            $this->response['result'] = $report;
        } else {
            $this->response['success']=false;
        }
        return response()->json($this->response);
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
