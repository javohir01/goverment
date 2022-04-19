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
            DB::raw("sum(case when citizens.application_id is not null then 1 else 0 end) as sociala"),
            DB::raw("sum(case when citizens.application_id is null then 1 else 0 end) as socialt"),
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
        $reportCount['0'] = [
            'sociala'=>0,
            'socialt'=>0,
            'social1'=>0,
            'social2'=>0,
            'social3'=>0,
            'social4'=>0,
            'social5'=>0,
            'social6'=>0,
            'social7'=>0,
            'social8'=>0,
            'social9'=>0,
            'social10'=>0,
            'social11'=>0,
            'social12'=>0,
            'social13'=>0,
            'social14'=>0,
            'social15'=>0,
            'social16'=>0,
            'social17'=>0,
            'social18'=>0,
        ];
        foreach ($report as $r){
            $reportCount['0']['sociala']  += $r->sociala;
            $reportCount['0']['socialt']  += $r->socialt;
            $reportCount['0']['social1']  += $r->social1;
            $reportCount['0']['social2']  += $r->social2;
            $reportCount['0']['social3']  += $r->social3;
            $reportCount['0']['social4']  += $r->social4;
            $reportCount['0']['social5']  += $r->social5;;
            $reportCount['0']['social6']  += $r->social6;
            $reportCount['0']['social7']  += $r->social7;
            $reportCount['0']['social8']  += $r->social8;;
            $reportCount['0']['social9']  += $r->social9;
            $reportCount['0']['social10']  += $r->social10;
            $reportCount['0']['social11']  += $r->social11;
            $reportCount['0']['social12']  += $r->social12;
            $reportCount['0']['social13']  += $r->social13;
            $reportCount['0']['social14']  += $r->social14;
            $reportCount['0']['social15']  += $r->social15;
            $reportCount['0']['social16']  += $r->social16;
            $reportCount['0']['social17']  += $r->social17;
            $reportCount['0']['social18']  += $r->social18;
        }
//        dd($reportCount);

        return response()->successJson(['report' => $report, 'report_count' => $reportCount]);
    }
    public function districts($id)
    {
//        dd('disds');
        $region_id = $id;
        $report = DB::table('citizens')
            ->where('citizens.region_id' ,$region_id)
//            ->leftJoin('regions', 'citizens.region_id', '=', 'regions.id')
            ->leftJoin('districts','citizens.district_id','=','districts.id')
            ->select(
                'districts.name_cyrl as district_name',
                'districts.id as district_id',
                DB::raw("sum(case when citizens.application_id is not null then 1 else 0 end) as sociala"),
                DB::raw("sum(case when citizens.application_id is null then 1 else 0 end) as socialt"),
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
        $reportCount['0'] = [
            'sociala'=>0,
            'socialt'=>0,
            'social1'=>0,
            'social2'=>0,
            'social3'=>0,
            'social4'=>0,
            'social5'=>0,
            'social6'=>0,
            'social7'=>0,
            'social8'=>0,
            'social9'=>0,
            'social10'=>0,
            'social11'=>0,
            'social12'=>0,
            'social13'=>0,
            'social14'=>0,
            'social15'=>0,
            'social16'=>0,
            'social17'=>0,
            'social18'=>0,
        ];
        foreach ($report as $r){
            $reportCount['0']['sociala']  += $r->sociala;
            $reportCount['0']['socialt']  += $r->socialt;
            $reportCount['0']['social1']  += $r->social1;
            $reportCount['0']['social2']  += $r->social2;
            $reportCount['0']['social3']  += $r->social3;
            $reportCount['0']['social4']  += $r->social4;
            $reportCount['0']['social5']  += $r->social5;;
            $reportCount['0']['social6']  += $r->social6;
            $reportCount['0']['social7']  += $r->social7;
            $reportCount['0']['social8']  += $r->social8;;
            $reportCount['0']['social9']  += $r->social9;
            $reportCount['0']['social10']  += $r->social10;
            $reportCount['0']['social11']  += $r->social11;
            $reportCount['0']['social12']  += $r->social12;
            $reportCount['0']['social13']  += $r->social13;
            $reportCount['0']['social14']  += $r->social14;
            $reportCount['0']['social15']  += $r->social15;
            $reportCount['0']['social16']  += $r->social16;
            $reportCount['0']['social17']  += $r->social17;
            $reportCount['0']['social18']  += $r->social18;
        }
        return response()->successJson(['report' => $report, 'report_count' => $reportCount]);
    }

    public function applicationRegions(){
        $report = DB::table('applications')
            ->LeftJoin('regions', 'applications.region_id', '=', 'regions.id');

        $report = $report->select(
            'regions.name_cyrl as region_name',
            'regions.id as region_id',
            DB::raw("sum(case when applications.status is not null then 1 else 0 end) as all"),
            DB::raw("sum(case when applications.status=0 then 1 else 0 end) as new"),
            DB::raw("sum(case when applications.status=1 then 1 else 0 end) as confirmed"),
            DB::raw("sum(case when applications.status=2 then 1 else 0 end) as rejected"),
        )
            ->whereNotNull(['regions.id'])
            ->groupBy('regions.id', 'regions.name_cyrl')
            ->orderBy('regions.id');

        $report = $report->get()->toArray();
        $reportCount['0'] = [
            'all'=>0,
            'new'=>0,
            'confirmed'=>0,
            'rejected'=>0,
        ];
        foreach ($report as $r){
            $reportCount['0']['all']  += $r->all;
            $reportCount['0']['new']  += $r->new;
            $reportCount['0']['confirmed']  += $r->confirmed;
            $reportCount['0']['rejected']  += $r->rejected;
        }
        return response()->successJson(['report' => $report, 'report_count' => $reportCount]);
    }
    public function applicationDistricts($id){
        $region_id = $id;
        $report = DB::table('applications')
            ->where('applications.region_id' ,$region_id)
            ->leftJoin('districts','applications.district_id','=','districts.id');

        $report = $report->select(
            'districts.name_cyrl as district_name',
            'districts.id as district_id',
            DB::raw("sum(case when applications.status is not null then 1 else 0 end) as all"),
            DB::raw("sum(case when applications.status=0 then 1 else 0 end) as new"),
            DB::raw("sum(case when applications.status=1 then 1 else 0 end) as confirmed"),
            DB::raw("sum(case when applications.status=2 then 1 else 0 end) as rejected"),
        )
            ->whereNotNull(['districts.id'])
            ->groupBy('districts.id', 'districts.name_cyrl')
            ->orderBy('districts.id');

        $report = $report->get()->toArray();
        $reportCount['0'] = [
            'all'=>0,
            'new'=>0,
            'confirmed'=>0,
            'rejected'=>0,

        ];
        foreach ($report as $r){
            $reportCount['0']['all']  += $r->all;
            $reportCount['0']['new']  += $r->new;
            $reportCount['0']['confirmed']  += $r->confirmed;
            $reportCount['0']['rejected']  += $r->rejected;
        }

        return response()->successJson(['report' => $report, 'report_count' => $reportCount]);
    }

}
