<?php

namespace App\Repositories;

use App\Citizen;
use App\Http\Resources\ReportAgencyApplicationTwoResource;
use App\ProfessionalDirection;
use App\SectorCitizen;
use App\Service;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\CitizenService;
use App\ServiceStatus;
use App\Region;

class ReportRepository
{
    //Ҳудуд кесимида суғурта полиси берилган фуқаролар сони статистикаси
    public function getRegion(Request $request)
    {
        if (!empty($request->all()['region_id'])){
            $region_id = $request->all()['region_id'];
            $report = DB::table('citizens')
                ->where('citizens.region_id','=' ,$region_id)
                ->leftJoin('districts','citizens.district_id','=','district.id')
                ->select(
                    'districts.name_cyrl as region_name',
                    'districts.id as region_id',
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

    public function getSumRegionInsurance()
    {
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $service_id = 2;//Sugurta xizmati id raqami 2 bulganligi uchun olindi
        $service_status_id = 5; //Bu xizmat tuliq yopilganligi uchun 5 qiymat olindi
        $total = DB::table('citizens')
            ->join('citizen_services', 'citizen_services.citizen_id', '=', 'citizens.id')
            ->where('citizen_services.service_id', $service_id);
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $total = $total
                ->where('citizen_services.data->sold_date', '>=', $start)
                ->where('citizen_services.data->sold_date', '<=', $end);
        }
        $total = $total->count('citizen_services.*');
        $performed_count = DB::table('citizens')
            ->join('citizen_services', 'citizen_services.citizen_id', '=', 'citizens.id')
            ->where('citizen_services.service_id', $service_id)
            ->where('citizen_services.service_status_id', $service_status_id);
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $performed_count = $performed_count
                ->where('citizen_services.data->sold_date', '>=', $start)
                ->where('citizen_services.data->sold_date', '<=', $end);
        }
        $performed_count = $performed_count->count('citizen_services.*');
        return [
            'user_id' => 'all',
            'region' => 'Жами',
            'total' => $total,
            'counts' => $performed_count,
        ];
    }

    //Ҳудуд кесимида суғурта полиси берилган фуқаролар маълумотномаси статистикаси
    public function getInfoInsuranceCitizen($user_id)
    {
        $service_id = 2;//Sugurta xizmati id raqami 2 bulganligi uchun olindi
        $service_status_id = 5; //Bu xizmat tuliq yopilganligi uchun 5 qiymat olindi
        $condition = [];
        $condition1 = [];
        $condition2 = [];
        $passport = request()->get('passport', null);
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $insurance_type = request()->get('insurance_type', null);;
        if (is_numeric($user_id)) {
            $condition['citizen_services.user_id'] = $user_id;
        }
        if ($passport) {
            $condition1['citizens.passport'] = $passport;
        }
        if ($insurance_type) {
            $condition2['citizen_services.data->insurance_type'] = $insurance_type;
        }
        $report_citizen = DB::table('citizen_services')
            ->leftJoin('citizens', 'citizen_services.citizen_id', '=', 'citizens.id')
            ->join('regions', 'regions.id', '=', 'citizens.region_id')
            ->join('cities', 'cities.id', '=', 'citizens.city_id')
            ->select('citizens.s_name as s_name', 'citizens.f_name as f_name', 'citizens.m_name as m_name',
                'citizens.birth_date as birth_date', 'citizens.passport as passport', 'regions.name_uz as region', 'cities.name_uz as city',
                'citizen_services.data->company as company',
                'citizen_services.data->sold_date as sold_date',
                'citizen_services.data->serial_number as serial_number',
                'citizen_services.data->insurance_type as insurance_type',
                'citizen_services.updated_at as updated_at'
            )
            ->where('citizen_services.service_id', $service_id)
            ->where('citizen_services.service_status_id', $service_status_id)
            ->where($condition)
            ->where($condition1)
            ->where($condition2);
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_citizen = $report_citizen
                ->where('citizen_services.data->sold_date', '>=', $start)
                ->where('citizen_services.data->sold_date', '<=', $end);
        }
        if (\request()->get('getAll', false)) {
            $limit = $report_citizen->count() ? $report_citizen->count() : 1;
            $report_citizen = $report_citizen->paginate($limit);
        } else {
            $report_citizen = $report_citizen->paginate(request()->get('limit', 20));
        }
        return $report_citizen;
    }

    // XBA uchun xizmatlar kesimida foydalanilgan xizmatlar soni
    public function getServiceCounts()
    {
        $status = request()->get('status', 'agency');
        $services = Service::active();
        $services = $services
            ->whereHas('role_services', function (Builder $query) use ($status) {
                $query->whereHas('role', function (Builder $query) use ($status) {
                    $query->where('name', $status);
                });
            })
            ->withCount([
                'citizen_services AS counts' => function ($query) {
                    if (auth()->user()->hasRole('agency') or auth()->user()->hasRole('operator') or auth()->user()->hasRole('representation') or auth()->user()->hasRole('sub_operator')) {
                        $query->select(DB::raw("count(citizen_services.*)"))
                            ->where('citizen_services.user_id', auth()->id());
                    } else {
                        $query->select(DB::raw("count(citizen_services.*)"));
                    }
                }
            ])
            ->withCount([
                'citizen_services AS counts_performed' => function ($query) {
                    if (auth()->user()->hasRole('agency') or auth()->user()->hasRole('operator') or auth()->user()->hasRole('representation') or auth()->user()->hasRole('sub_operator')) {
                        $query->select(DB::raw("count(citizen_services.*)"))
                            ->whereHas('service_status', function (Builder $query) {
                                $query->where('closed', true);
                            })
                            ->where('citizen_services.user_id', auth()->id());
                    } else {
                        $query->select(DB::raw("count(citizen_services.*)"));
                    }
                }
            ]);

        $services = $services->orderBy('order')
            ->get();
        foreach ($services as $service) {
            foreach ($service->media as $media) {
                $service->logo = $media->getUrl();
            }
        }
        if ($services) {
            $this->response['success'] = true;
            $this->response['result'] = $services;
        } else {
            $this->response['success'] = false;
        }
        return response()->json($this->response);
    }

    // XBA va operator uchun xizmatlar kesimida foydalanilgan fuqarolar malumotnomasi
    public function getCitizensServices()
    {
        $status = request()->get('status', null);
//        return $status;
        $service_statuses = [];
        $service_key = request()->get('service_key', null);
        if ($service_key == 'all') {
            $service_id = null;
            $service_statuses = [];
        } else {
            $service = Service::where('key', $service_key)->first();
            $service_id = $service->id;
            $service_statuses = $service->statuses;
        }
        $condition = [];
        $condition1 = [];
        $passport = request()->get('passport', null);
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $user_id = request()->get('user_id', null);
        $service_status_id = request()->get('service_status_id', null);

        $occupation_id = request()->get('occupation_id', null);
        $education_degree = request()->get('education_degree', null);
        $computer_degree = request()->get('computer_degree', null);
        $experience = request()->get('experience', null);
        $driver_license_true = request()->get('driver_license_true', null);
        $infectious_disease_true = request()->get('infectious_disease_true', null);
        $family_status = request()->get('family_status', null);
        $qualification_has_been = request()->get('qualification_has_been', null);
        $gender = request()->get('gender', null);

        $languages = request()->get('languages', null);
        $sector_id = request()->get('sector_id', null);
        $want_abroad_country = request()->get('want_abroad_country', null);
        $city_id = request()->get('city_id', null);
        $driver_license = request()->get('driver_license', null);
        $driver_technical_license = request()->get('driver_technical_license', null);
        $age_from = request()->get('age_from', null);
        $age_to = request()->get('age_to', null);

        $unikal_id_from = request()->get('unikal_id_from', null);
        $unikal_id_to = request()->get('unikal_id_to', null);

        if ($passport) {
            $condition['passport'] = $passport;
        }
        if (auth()->user()->hasRole('agency') or auth()->user()->hasRole('operator') or auth()->user()->hasRole('representation') or auth()->user()->hasRole('sub_operator')) {
            $condition1['user_id'] = auth()->id();
        }
        if ((auth()->user()->hasRole('admin') or auth()->user()->hasRole('agency_admin')) and is_numeric($user_id)) {
            $condition1['user_id'] = $user_id;
        }
        if ($service_status_id) {
            $condition1['service_status_id'] = $service_status_id;
        }
        if ($service_id) {
            $condition1['service_id'] = $service_id;
        }

        if ($gender) {
            $condition['gender'] = $gender;
        }
        if (is_numeric($city_id)) {
            $condition['city_id'] = $city_id;
        }
        if ($occupation_id) {
            $condition1['data->occupation_id'] = $occupation_id;
        }
        if ($education_degree) {
            $condition1['data->education->degree'] = $education_degree;
        }
        if ($computer_degree) {
            $condition1['data->education->computer_degree'] = $computer_degree;
        }
        if ($experience) {
            $condition1['data->education->experience'] = $experience;
        }
        if ($driver_license_true) {
            $condition1['data->driver_license_true'] = $driver_license_true;
        }
        if ($infectious_disease_true) {
            $condition1['data->infectious_disease_true'] = $infectious_disease_true;
        }
        if ($family_status) {
            $condition1['data->family->family_status'] = $family_status;
        }
        if ($qualification_has_been) {
            $condition1['data->qualification_has_been'] = $qualification_has_been;
        }
        $report_citizen = CitizenService::with('service_status', 'citizen.region', 'citizen.city')
            ->where($condition1);
        if ($languages) {
            for ($i = 0; $i < sizeof($languages); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->languages', [['name' => $languages[$i], 'checked' => true]]);
            }

        }
        if ($occupation_id) {
            for ($i = 0; $i < sizeof($occupation_id); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->occupation_id', [(int)$occupation_id[$i]]);
            }
        }
        if ($sector_id) {
            for ($i = 0; $i < sizeof($sector_id); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->sector_id', [(int)$sector_id[$i]]);
            }
        }
        if ($want_abroad_country) {
            for ($i = 0; $i < sizeof($want_abroad_country); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->want_abroad_country', [(int)$want_abroad_country[$i]]);
            }
        }
        if ($driver_license) {
            for ($i = 0; $i < sizeof($driver_license); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->driver_license', [$driver_license[$i]]);
            }
        }
        if ($driver_technical_license) {
            for ($i = 0; $i < sizeof($driver_technical_license); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->driver_technical_license', [$driver_technical_license[$i]]);
            }
        }

        $report_citizen = $report_citizen->whereHas('citizen', function (Builder $query) use ($age_to, $age_from, $condition) {
            $query->where($condition);
            if ($age_from and $age_to) {
                $query->whereYear('citizens.birth_date', '>=', date('Y') - $age_to);
                $query->whereYear('citizens.birth_date', '<=', date('Y') - $age_from);
            }
        })
            ->whereHas('service', function (Builder $query) use ($status) {
                $query->whereHas('role_services', function (Builder $query) use ($status) {
                    $query->whereHas('role', function (Builder $query) use ($status) {
                        $query->where('name', $status);
                    });
                });
            });
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_citizen = $report_citizen
                ->where('created_at', '>=', $start)
                ->where('created_at', '<=', $end);
        }
        if ($unikal_id_from and $unikal_id_to) {
            $unikal_id = [];
            for ($i = $unikal_id_from; $i <= $unikal_id_to; $i++) {
                $unikal_id[] = (int)$i;
            }
            // return $unikal_id;
            $report_citizen = $report_citizen
                // ->whereBetween('data->unikal_id',[(int)$unikal_id_from,(int)$unikal_id_to]);
                // ->where('data->unikal_id', '>', '1');
                ->whereIn('data->unikal_id', $unikal_id);
        }

        if (\request()->get('getAll', false)) {
            $limit = $report_citizen->count() ? $report_citizen->count() : 1;
            $report_citizen = $report_citizen->paginate($limit);
        } else {
            $report_citizen = $report_citizen->paginate(request()->get('limit', 20));
        }
        return [
            'citizen_services' => $report_citizen,
            'service_statuses' => $service_statuses
        ];
    }



    public function getCitizenServicesCitizens()
    {
        ini_set('max_execution_time', 7200);
        $status = request()->get('status', null);
        $group_by = request()->get('group_by', null);
        $service_statuses = [];
        $service_key = request()->get('service_key', null);
        if ($service_key == 'all') {
            $service_id = null;
            $service_statuses = [];
        } else {
            $service = Service::where('key', $service_key)->first();
            $service_id = $service->id;
            $service_statuses = $service->statuses;
        }
        $condition = [];
        $condition1 = [];
        $condition2 = [];
        $passport = request()->get('passport', null);
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $region_id = request()->get('region_id', null);
        $service_status_id = request()->get('service_status_id', null);

        if ($passport) {
            $condition['passport'] = $passport;
        }

        if ($service_status_id) {
            $condition1['service_status_id'] = $service_status_id;
        }
        if ($service_id) {
            $condition1['service_id'] = $service_id;
        }
        if (is_numeric($region_id)) {
            $condition2['region_id'] = $region_id;
        }
        $report_citizen = CitizenService::with('service_status:id,service_id', 'citizen:id,s_name,f_name,m_name,passport,birth_date,city_id,region_id', 'citizen.region:id,name_cyrl', 'citizen.city:id,name_cyrl')
            ->where($condition1);

        $report_citizen = $report_citizen->whereHas('citizen', function (Builder $query) use ($condition, $condition2, $group_by) {
            $query->where($condition);
            if ($group_by && $group_by == 'citizen') {
                $query->where($condition2);
            }
        })
            ->whereHas('service', function (Builder $query) use ($status) {
                $query->whereHas('role_services', function (Builder $query) use ($status) {
                    $query->whereHas('role', function (Builder $query) use ($status) {
                        $query->where('name', $status);
                    });
                });
            });
        if ($group_by && $group_by == 'operator') {
            $report_citizen = $report_citizen
                ->whereHas('user', function (Builder $query) use ($condition2) {
                    $query->where($condition2);
                });
        }

        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_citizen = $report_citizen
                ->where('created_at', '>=', $start)
                ->where('created_at', '<=', $end);
        }
        $report_citizen = $report_citizen->select('id', 'citizen_id', 'service_id', 'service_status_id', 'created_at', 'user_id');
        if (\request()->get('getAll', false)) {
            $limit = $report_citizen->count() ? $report_citizen->count() : 1;
            $report_citizen = $report_citizen->paginate($limit);
        } else {
            $report_citizen = $report_citizen->paginate(request()->get('limit', 20));
        }
        return [
            'citizen_services' => $report_citizen,
            'service_statuses' => $service_statuses
        ];
    }

    public function getAbkmCitizenServicesCitizens()
    {
        ini_set('max_execution_time', 7200);
        $status = request()->get('status', null);
        $group_by = request()->get('group_by', null);
        $service_statuses = [];
        $service_key = request()->get('service_key', null);
        if ($service_key == 'all') {
            $service_id = null;
            $service_statuses = [];
        } else {
            $service = Service::where('key', $service_key)->first();
            $service_id = $service->id;
            $service_statuses = $service->statuses;
        }
        $condition = [];
        $condition1 = [];
        $condition2 = [];
        $passport = request()->get('passport', null);

        $from_date=request()->get('from_date', "2000-01-01");
        $to_date=request()->get('to_date', "2024-01-01");

        $city_id = request()->get('city_id', null);
        $service_status_id = request()->get('service_status_id', null);

        if ($passport) {
            $condition['passport'] = $passport;
        }

        if ($service_status_id) {
            $condition1['service_status_id'] = $service_status_id;
        }
        if ($service_id) {
            $condition1['service_id'] = $service_id;
        }
        if (is_numeric($city_id)) {
            $condition2['city_id'] = $city_id;
        }
        $report_citizen = CitizenService::with('service_status:id,service_id', 'citizen:id,s_name,f_name,m_name,passport,birth_date,city_id,region_id', 'citizen.region:id,name_cyrl', 'citizen.city:id,name_cyrl')
            ->where($condition1);


        if ($group_by && $group_by == 'abkm') {
            $report_citizen = $report_citizen
                ->whereHas('user', function (Builder $query) use ($status, $condition2) {
                    $query->where($condition2);
                    $query->whereHas('roles', function (Builder $query) use ($status) {
                        $query->where('name', $status);
                    });
                });
        }

        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_citizen = $report_citizen
                ->where('created_at', '>=', $start)
                ->where('created_at', '<=', $end);
        }
        $report_citizen = $report_citizen->select('id', 'citizen_id', 'service_id', 'service_status_id', 'created_at', 'user_id');
        if (\request()->get('getAll', false)) {
            $limit = $report_citizen->count() ? $report_citizen->count() : 1;
            $report_citizen = $report_citizen->paginate($limit);
        } else {
            $report_citizen = $report_citizen->paginate(request()->get('limit', 20));
        }
        return [
            'citizen_services' => $report_citizen,
            'service_statuses' => $service_statuses
        ];
    }

    public function getReportAgency()
    {
        $report = DB::table('citizens')
            ->leftJoin('regions', 'citizens.region_id', '=', 'regions.id');
        $report = DB::table('citizen_services')
            ->select(DB::raw("EXTRACT(YEAR FROM citizen_services.created_at) as year, EXTRACT(MONTH FROM citizen_services.created_at) as month"),
                DB::raw('COUNT(citizen_services.citizen_id) as citizens'),
                DB::raw("sum(case when citizen_services.service_id=10 then 1 else 0 end) as count_advice"),
                DB::raw("sum(case when citizen_services.service_id=11 then 1 else 0 end) as count_employment"),
                DB::raw("sum(case when citizen_services.service_id=12 then 1 else 0 end) as count_employment_abroad"),
                DB::raw("sum(case when citizen_services.service_id=13 then 1 else 0 end) as count_staffing_employer")
            )
            ->whereBetween('citizen_services.service_id', [10, 13])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->get()->toArray();

        $charge = DB::table('citizen_services')
            ->select(
                DB::raw("EXTRACT(YEAR FROM citizen_services.created_at) as year, EXTRACT(MONTH FROM citizen_services.created_at) as month"),
                'citizen_services.service_id',
                DB::raw("SUM(cast(citizen_services.data->>'service_charge' as int))")
            )
            ->whereBetween('citizen_services.service_id', [10, 13])
            ->groupBy('year', 'month', 'citizen_services.service_id')
            ->orderBy('year')
            ->get()->toArray();

        for ($i = 0; $i < sizeof($report); $i++) {
            for ($j = 0; $j < sizeof($charge); $j++) {
                if ($report[$i]->year == $charge[$j]->year and $report[$i]->month == $charge[$j]->month)
                    switch ($charge[$j]->service_id) {
                        case 10:
                            $report[$i]->sum_advice = $charge[$j]->sum;
                            break;
                        case 11:
                            $report[$i]->sum_employment = $charge[$j]->sum;
                            break;
                        case 12:
                            $report[$i]->sum_employment_abroad = $charge[$j]->sum;
                            break;
                        case 13:
                            $report[$i]->sum_staffing_employer = $charge[$j]->sum;
                            break;
                    }
            }
        }
        return $report;
    }

    public function getEmploymentDepartment()
    {
        $service_id = 7;//ish izlovchi xizmati id raqami 7 bulganligi uchun olindi
        $service_status_waiting = ServiceStatus::where('service_id', 7)->where('order', 1)->first();
        $service_status_finished = ServiceStatus::where('service_id', 7)->where('order', 2)->first();
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);

        $group_by = request('group_by', null);
        $table = 'users';
        $whom_id = 'user_id';
        if ($group_by && $group_by == 'citizen') {
            $table = 'citizens';
            $whom_id = 'citizen_id';
        }
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report = DB::table('regions')
                ->join($table, $table . '.region_id', '=', 'regions.id')
                ->leftJoin('citizen_services', function ($join) use ($table, $whom_id, $service_id, $start, $end) {
                    $join->on($table . '.id', '=', 'citizen_services.' . $whom_id);
                    $join->where('citizen_services.service_id', $service_id);
                    $join->where('citizen_services.created_at', '>=', $start . " 00:00:00");
                    $join->where('citizen_services.created_at', '<=', $end . " 23:59:59");
                });
        } else {
            $report = DB::table('regions')
                ->join($table, $table . '.region_id', '=', 'regions.id')
//                ->join('citizens', 'citizens.region_id', 'regions.id')
                ->leftJoin('citizen_services', function ($join) use ($table, $whom_id, $service_id) {
                    $join->on($table . '.id', '=', 'citizen_services.' . $whom_id);
                    $join->where('citizen_services.service_id', $service_id);
                });
        }
        $report = $report->select(
            $table . '.region_id as region_id',
            'regions.name_cyrl as region',
            DB::raw("sum(case when citizen_services.service_status_id= $service_status_waiting->id then 1 else 0 end) as count_waiting"),
            DB::raw("sum(case when citizen_services.service_status_id= $service_status_finished->id then 1 else 0 end) as count_finished")
        )
            ->groupBy('regions.name_cyrl', $table . '.region_id', 'regions.c_order')
            ->orderBy('regions.c_order')->get()->toArray();
        $sum = $this->getSumEmploymentDepartment();
        array_unshift($report, $sum);
        return $report;
    }

    public function getSumEmploymentDepartment()
    {

        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $service_id = 7;//ish izlovchi xizmati id raqami 7 bulganligi uchun olindi
        $service_status_waiting = ServiceStatus::where('service_id', 7)->where('order', 1)->first(); //ishga joylashmagan holati statusi
        $service_status_finished = ServiceStatus::where('service_id', 7)->where('order', 2)->first(); //ishga joylashgan holati statusi
        $count_waiting = DB::table('citizens')
            ->join('citizen_services', 'citizen_services.citizen_id', '=', 'citizens.id')
            ->where('citizen_services.service_id', $service_id)
            ->where('citizen_services.service_status_id', $service_status_waiting->id);
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $count_waiting = $count_waiting
                ->where('citizen_services.created_at', '>=', $start . " 00:00:00")
                ->where('citizen_services.created_at', '<=', $end . " 23:59:59");
        }
        $count_waiting = $count_waiting->count('citizen_services.*');
        $count_finished = DB::table('citizens')
            ->join('citizen_services', 'citizen_services.citizen_id', '=', 'citizens.id')
            ->where('citizen_services.service_id', $service_id)
            ->where('citizen_services.service_status_id', $service_status_finished->id);
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $count_finished = $count_finished
                ->where('citizen_services.created_at', '>=', $start . " 00:00:00")
                ->where('citizen_services.created_at', '<=', $end . " 23:59:59");
        }
        $count_finished = $count_finished->count('citizen_services.*');

        return [
            'region_id' => 'all',
            'region' => 'Жами',
            'count_waiting' => $count_waiting,
            'count_finished' => $count_finished,
        ];
    }

    public function getEmploymentDepartmentCitizens()
    {
        $region_id = request()->get('region_id', null);
        $condition = [];
        $condition1 = [];
        $condition2 = [];
        $passport = request()->get('passport', null);
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $service_status_id = request()->get('service_status_id', null);
        $occupation_id = request()->get('occupation_id', null);
        $education_degree = request()->get('education_degree', null);
        $computer_degree = request()->get('computer_degree', null);
        $experience = request()->get('experience', null);
        $driver_license_true = request()->get('driver_license_true', null);
        $infectious_disease_true = request()->get('infectious_disease_true', null);
        $family_status = request()->get('family_status', null);
        $qualification_has_been = request()->get('qualification_has_been', null);
        $gender = request()->get('gender', null);
        $languages = request()->get('languages', null);
        $sector_id = request()->get('sector_id', null);
        $want_abroad_country = request()->get('want_abroad_country', null);
        $city_id = request()->get('city_id', null);
        $driver_license = request()->get('driver_license', null);
        $driver_technical_license = request()->get('driver_technical_license', null);
        $age_from = request()->get('age_from', null);
        $age_to = request()->get('age_to', null);
        $unikal_id_from = request()->get('unikal_id_from', null);
        $unikal_id_to = request()->get('unikal_id_to', null);
        $uniq_id = request()->get('uniq_id', null);
        $service_statuses = [];
        $service_key = 'employee';
        $service = Service::where('key', $service_key)->first();
        $service_id = $service->id;
        $service_statuses = $service->statuses;

        $condition1['service_id'] = $service_id;

        $group_by = request('group_by', 'citizen');

        if ($passport) {
            $condition['passport'] = $passport;
        }
        if (is_numeric($city_id)) {
            $condition['city_id'] = $city_id;
        }

        if ($service_status_id) {
            $condition1['service_status_id'] = $service_status_id;
        }
        if (is_numeric($region_id)) {
            $condition2['region_id'] = $region_id;
        }
        if ($gender) {
            $condition['gender'] = $gender;
        }

        if ($education_degree) {
            $condition1['data->education->degree'] = $education_degree;
        }
        if ($computer_degree) {
            $condition1['data->education->computer_degree'] = $computer_degree;
        }
        if ($experience) {
            $condition1['data->education->experience'] = $experience;
        }
        if ($driver_license_true) {
            $condition1['data->driver_license_true'] = $driver_license_true;
        }
        if ($infectious_disease_true) {
            $condition1['data->infectious_disease_true'] = $infectious_disease_true;
        }
        if ($family_status) {
            $condition1['data->family->family_status'] = $family_status;
        }
        if ($qualification_has_been) {
            $condition1['data->qualification_has_been'] = $qualification_has_been;
        }

        $report_citizen = CitizenService::with('service_status', 'citizen.region', 'citizen.city')
            ->where($condition1);
        if ($uniq_id) {
            $report_citizen = $report_citizen->with('selection_job_seeker.finding_employer.employer', 'selection_job_seeker.vacancy.occupation')->whereHas('selection_job_seeker', function (Builder $query) use ($uniq_id) {
                $query->where('uniq_id', $uniq_id);
            });
        }
        if ($languages) {
            for ($i = 0; $i < sizeof($languages); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->languages', [['name' => $languages[$i], 'checked' => true]]);
            }

        }
        if ($occupation_id) {
            for ($i = 0; $i < sizeof($occupation_id); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->occupation_id', [(int)$occupation_id[$i]]);
            }
        }
        if ($sector_id) {
            for ($i = 0; $i < sizeof($sector_id); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->sector_id', [(int)$sector_id[$i]]);
            }
        }
        if ($want_abroad_country) {
            for ($i = 0; $i < sizeof($want_abroad_country); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->want_abroad_country', [(int)$want_abroad_country[$i]]);
            }
        }
        if ($driver_license) {
            for ($i = 0; $i < sizeof($driver_license); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->driver_license', [$driver_license[$i]]);
            }
        }
        if ($driver_technical_license) {
            for ($i = 0; $i < sizeof($driver_technical_license); $i++) {
                $report_citizen = $report_citizen
                    ->whereJsonContains('data->driver_technical_license', [$driver_technical_license[$i]]);
            }
        }


        $order = request()->get('order', 1);
        if ($order) {
            $service_status_waiting = ServiceStatus::where('service_id', 7)->where('order', $order)->first(); //ishga joylashmagan holati statusi
            $report_citizen = $report_citizen->where('service_status_id', $service_status_waiting->id);
            $service_statuses = ServiceStatus::where('service_id', 7)->where('order', $order)->get();
        }
        $report_citizen = $report_citizen->whereHas('citizen', function (Builder $query) use ($age_from, $age_to, $group_by, $condition2, $condition) {
            $query->where($condition);
            if ($age_from and $age_to) {
                $query->whereYear('citizens.birth_date', '>=', date('Y') - $age_to);
                $query->whereYear('citizens.birth_date', '<=', date('Y') - $age_from);
            }
            if ($group_by && $group_by == 'citizen') {
                $query->where($condition2);
            }
        });
        if ($group_by && $group_by == 'operator') {
            $report_citizen = $report_citizen
                ->whereHas('user', function (Builder $query) use ($group_by, $condition2) {
                    $query->where($condition2);
                });
        }
        if ($unikal_id_from and $unikal_id_to) {
            $unikal_id = [];
            for ($i = $unikal_id_from; $i <= $unikal_id_to; $i++) {
                $unikal_id[] = (int)$i;
            }
            // return $unikal_id;
            $report_citizen = $report_citizen
                // ->whereBetween('data->unikal_id',[(int)$unikal_id_from,(int)$unikal_id_to]);
                // ->where('data->unikal_id', '>', '1');
                ->whereIn('data->unikal_id', $unikal_id);
        }
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_citizen = $report_citizen
                ->where('created_at', '>=', $start . " 00:00:00")
                ->where('created_at', '<=', $end . " 23:59:59");
        }
        if (\request()->get('getAll', null)) {
            $limit = $report_citizen->count() ? $report_citizen->count() : 1;
            $report_citizen = $report_citizen->paginate($limit);
        } else {
            $report_citizen = $report_citizen->paginate(request()->get('limit', 20));
        }
        return [
            'citizen_services' => $report_citizen,
            'service_statuses' => $service_statuses
        ];


    }

    public function getReportAgencyApplicationTwo()
    {
        $user = auth()->user();
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $report_employment = CitizenService::where('user_id', $user->id)->with('citizen', 'service', 'service_status', 'citizen.region', 'citizen.city', 'payments', 'occupation', 'agency_employer', 'agency_employer.employer.country', 'country', 'citizen.citizen_services');
        $report_employment = $report_employment->whereHas('service', function (Builder $query) {
            $query->where('key', 'employment-abroad');
        });
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_employment = $report_employment
                ->where('citizen_services.created_at', '>=', $start . " 00:00:00")
                ->where('citizen_services.created_at', '<=', $end . " 23:59:59");
        }
        if (\request()->get('getAll', false)) {
            $limit = $report_employment->count() ? $report_employment->count() : 1;
            $report_employment = $report_employment->orderBy('id', 'desc')->paginate($limit);
        } else {
            $report_employment = $report_employment->orderBy('id', 'desc')->paginate(request()->get('limit', 10));
        }
        $report_employment = ReportAgencyApplicationTwoResource::collection($report_employment);
        return $report_employment;
    }

    public function getReportAgencyApplicationThree()
    {
        $user = auth()->user();
        $from_date = request()->get('from_date', '01-01-2019');
        $to_date = request()->get('to_date', '12-12-2024');

        $report_employment = CitizenService::where('user_id', $user->id)->with('citizen', 'service', 'service_status', 'agency_employer.agency', 'agency_employer.employer', 'agency_employer.vacancy', 'country');
        $report_employment = $report_employment->whereHas('service', function (Builder $query) {
            $query->where('key', 'employment-abroad');
        });
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_employment = $report_employment
                ->where('citizen_services.created_at', '>=', $start . " 00:00:00")
                ->where('citizen_services.created_at', '<=', $end . " 23:59:59");
        }
        return $report_employment;
    }

    public function getReportAgencyApplicationFour()
    {
        $user = auth()->user();
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $service_status = ServiceStatus::whereIn('order', [4, 5])->with('service');
        $service_status = $service_status->whereHas('service', function (Builder $query) {
            $query->where('key', 'employment-abroad');
        })->get()->toArray();

        $report_employment = CitizenService::where('user_id', $user->id)->with('citizen', 'service', 'service_status', 'agency_employer', 'country');
        $report_employment = $report_employment->whereHas('service', function (Builder $query) {
            $query->where('key', 'employment-abroad');
        });
        $report_employment = $report_employment->whereHas('service_status', function (Builder $query) use ($service_status) {
            $query->whereIn('id', [$service_status[0]['id'], $service_status[1]['id']]);
            // $query->where('id', $service_status[1]['id']);
        });
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_employment = $report_employment
                ->where('citizen_services.created_at', '>=', $start . " 00:00:00")
                ->where('citizen_services.created_at', '<=', $end . " 23:59:59");
        }

        if (\request()->get('getAll', false)) {
            $limit = $report_employment->count() ? $report_employment->count() : 1;
            $report_employment = $report_employment->paginate($limit);
        } else {
            $report_employment = $report_employment->paginate(request()->get('limit', 10));
        }

        return $report_employment;

    }

    public function getReportAgencyApplicationFive()
    {
        $user = auth()->user();
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $service_status = ServiceStatus::where('order', 3)->with('service');
        $service_status = $service_status->whereHas('service', function (Builder $query) {
            $query->where('key', 'employment-abroad');
        })->first();
//        return $service_status['id'];

        $report_employment = CitizenService::where('user_id', $user->id)->with('citizen', 'service', 'service_status', 'agency_employer', 'country');
        $report_employment = $report_employment->whereHas('service', function (Builder $query) {
            $query->where('key', 'employment-abroad');
        });
        $report_employment = $report_employment->whereHas('service_status', function (Builder $query) use ($service_status) {
            $query->where('id', $service_status->id);
        });
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_employment = $report_employment
                ->where('citizen_services.created_at', '>=', $start . " 00:00:00")
                ->where('citizen_services.created_at', '<=', $end . " 23:59:59");
        }

        if (\request()->get('getAll', false)) {
            $limit = $report_employment->count() ? $report_employment->count() : 1;
            $report_employment = $report_employment->paginate($limit);
        } else {
            $report_employment = $report_employment->paginate(request()->get('limit', 10));
        }

        return $report_employment;

    }

    public function getReportAgencyApplicationSix()
    {
        $user = auth()->user();
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $service_status = ServiceStatus::whereIn('order', [3, 4])->with('service');
        $service_status = $service_status->whereHas('service', function (Builder $query) {
            $query->where('key', 'employment-abroad');
        })->get();
//        return $service_status['id'];

        $report_employment = CitizenService::where('user_id', $user->id)->with('citizen');
        $report_employment = $report_employment->whereHas('service', function (Builder $query) {
            $query->where('key', 'employment-abroad');
        });
        $report_employment = $report_employment->whereHas('service_status', function (Builder $query) use ($service_status) {
            $query->whereIn('id', [$service_status[0]->id, $service_status[1]->id]);
        });
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_employment = $report_employment
                ->where('citizen_services.created_at', '>=', $start . " 00:00:00")
                ->where('citizen_services.created_at', '<=', $end . " 23:59:59");
        }

        if (\request()->get('getAll', false)) {
            $limit = $report_employment->count() ? $report_employment->count() : 1;
            $report_employment = $report_employment->paginate($limit);
        } else {
            $report_employment = $report_employment->paginate(request()->get('limit', 10));
        }

        return $report_employment;

    }

    public function getReportAgencyApplicationSeven()
    {
        $service_status = ServiceStatus::where('service_id', 12)->where('order', 3)->first();
        $report = DB::table('regions')
            ->join('citizens', 'citizens.region_id', 'regions.id')
            ->leftJoin('citizen_services', function ($join) {
                $join->on('citizens.id', '=', 'citizen_services.citizen_id');
                $join->whereIn('citizen_services.service_id', [10, 12]);
            });
        $report = $report->select('citizens.region_id', 'regions.name_cyrl as region',
            DB::raw("count(citizen_services.*) as count_services"),
            DB::raw("sum(case when citizen_services.service_id=12 then 1 else 0 end) as count_employment_abroad"),
            DB::raw("sum(case when citizen_services.service_status_id = $service_status->id
            and citizen_services.service_id=12 then 1 else 0 end)  as close_employment_abroad"),
            DB::raw("sum(case when citizen_services.service_id=10 then 1 else 0 end) as count_advice"))
            ->groupBy('regions.name_cyrl', 'citizens.region_id', 'regions.c_order')
            ->orderBy('regions.c_order')
            ->get()->toArray();

        $sum = $this->getSumReportAgencyApplicationSeven();
        array_unshift($report, $sum);
        return $report = [
            'report' => $report
        ];

    }

    public function getSumReportAgencyApplicationSeven()
    {
        $service_status = ServiceStatus::where('service_id', 12)->where('order', 3)->first();
        $all = DB::table('citizens')
            ->join('citizen_services', 'citizen_services.citizen_id', '=', 'citizens.id')
            ->select(
                DB::raw("count(citizen_services.*) as count_services"),
                DB::raw("sum(case when citizen_services.service_id=12 then 1 else 0 end) as count_employment_abroad"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status->id
                and citizen_services.service_id=12 then 1 else 0 end)  as close_employment_abroad"),
                DB::raw("sum(case when citizen_services.service_id=10 then 1 else 0 end) as count_advice"))
            ->whereIn('citizen_services.service_id', [10, 12])
            ->get()->toArray();

        return [
            'region_id' => 'all',
            'region' => 'Жами',
            'count_services' => $all[0]->count_services,
            'count_employment_abroad' => $all[0]->count_employment_abroad,
            'close_employment_abroad' => $all[0]->close_employment_abroad,
            'count_advice' => $all[0]->count_advice
        ];
    }

    public function getNeedHelpServices()
    {
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $report = null;

        $service_status_waiting = ServiceStatus::where('service_id', 9)->where('order', 1)->first(); //xizmat kursatish kutilayotganlar
        $service_status_finished = ServiceStatus::where('service_id', 9)->where('order', 2)->first(); //xizmat kursatilgan
        $service_status_canceled = ServiceStatus::where('service_id', 9)->where('order', 3)->first(); //bekor qilingan

        $report = DB::table('citizen_services')
            ->select(
                DB::raw('DATE(citizen_services.created_at) as date'),
                DB::raw("sum(case when citizen_services.service_id<>0 then 1 else 0 end) as total"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id  and data->> 'help_type_id'='1' then 1 else 0 end) as count_first_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='1' then 1 else 0 end) as count_first_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='1' then 1 else 0 end) as count_first_canceled"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id and data->> 'help_type_id'='2' then 1 else 0 end) as count_two_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='2'  then 1 else 0 end) as count_two_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='2'  then 1 else 0 end) as count_two_canceled"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id and data->> 'help_type_id'='3' then 1 else 0 end) as count_three_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='3'  then 1 else 0 end) as count_three_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='3'  then 1 else 0 end) as count_three_canceled"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id and data->> 'help_type_id'='4' then 1 else 0 end) as count_four_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='4'  then 1 else 0 end) as count_four_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='4'  then 1 else 0 end) as count_four_canceled"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id and data->> 'help_type_id'='5' then 1 else 0 end) as count_five_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='5'  then 1 else 0 end) as count_five_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='5'  then 1 else 0 end) as count_five_canceled"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id and data->> 'help_type_id'='6' then 1 else 0 end) as count_six_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='6'  then 1 else 0 end) as count_six_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='6'  then 1 else 0 end) as count_six_canceled"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id and data->> 'help_type_id'='7' then 1 else 0 end) as count_seven_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='7'  then 1 else 0 end) as count_seven_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='7'  then 1 else 0 end) as count_seven_canceled"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id and data->> 'help_type_id'='8' then 1 else 0 end) as count_eight_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='8'  then 1 else 0 end) as count_eight_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='8'  then 1 else 0 end) as count_eight_canceled"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id and data->> 'help_type_id'='9' then 1 else 0 end) as count_night_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='9'  then 1 else 0 end) as count_night_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='9'  then 1 else 0 end) as count_night_canceled"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id and data->> 'help_type_id'='10' then 1 else 0 end) as count_ten_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='10'  then 1 else 0 end) as count_ten_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='10'  then 1 else 0 end) as count_ten_canceled"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_waiting->id and data->> 'help_type_id'='11' then 1 else 0 end) as count_eleven_waiting"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_finished->id and data->> 'help_type_id'='11'  then 1 else 0 end) as count_eleven_finished"),
                DB::raw("sum(case when citizen_services.service_id=9 and citizen_services.service_status_id=$service_status_canceled->id and data->> 'help_type_id'='11'  then 1 else 0 end) as count_eleven_canceled")
            )
//            ->orderBy('citizen_services.created_at', 'desc')
            ->where('citizen_services.service_id', 9)
            ->whereIn('data->help_type_id', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])
            ->groupBy(DB::raw('Date(citizen_services.created_at)'))
            ->orderBy(DB::raw('Date(citizen_services.created_at)'));
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report = $report
                ->where('citizen_services.created_at', '>=', $start . " 00:00:00")
                ->where('citizen_services.created_at', '<=', $end . " 23:59:59");
        }
        $report = $report->get()->toArray();
//        $sum = $this->getSumNeedHelpServices();
//        array_unshift($report, $sum);
        return $report;

    }

    public function getNeedHelpCitizens()
    {

        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $order = request()->get('order', null);
        $date = request()->get('date', null);
        $help_type_id = request()->get('help_type_id', null);
        $passport = request()->get('passport', null);

        $report_citizen = CitizenService::with('service_status', 'citizen.region', 'citizen.city')
            ->where('service_id', 9);
        if ($order) {
            $service_status = ServiceStatus::where('service_id', 9)->where('order', $order)->first();
            $report_citizen = $report_citizen->where('service_status_id', $service_status->id);
        }
        if ($date) {
            $report_citizen = $report_citizen->where(DB::raw('Date(citizen_services.created_at)'), $date);
        }
        if (is_numeric($help_type_id)) {
            $report_citizen = $report_citizen->where('citizen_services.data->help_type_id', $help_type_id);
        }

        if ($passport) {
            $report_citizen = $report_citizen->whereHas('citizen', function (Builder $query) use ($passport) {
                $query->where('passport', $passport);
            });
        }

        if (auth()->user()->hasRole('department_financial_assistance')) {
            $report_citizen = $report_citizen->whereIn('citizen_services.data->help_type_id', [1, 2, 3, 4, 5, 6]);
        } else {
            $report_citizen = $report_citizen->whereIn('citizen_services.data->help_type_id', [5, 7, 8, 9, 10, 11]);
        }

        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report_citizen = $report_citizen
                ->where(DB::raw('Date(citizen_services.created_at)'), '>=', $start . " 00:00:00")
                ->where(DB::raw('Date(citizen_services.created_at)'), '<=', $end . " 23:59:59");
        }

        if (\request()->get('getAll', false)) {
            $limit = $report_citizen->count() ? $report_citizen->count() : 1;
            $report_citizen = $report_citizen->paginate($limit);
        } else {
            $report_citizen = $report_citizen->paginate(request()->get('limit', 15));
        }

        return $report_citizen;
    }

    public function getEducationReportOne()
    {
        $service_status_performed = ServiceStatus::where('service_id', 6)->where('order', 4)->first()->id;
        $service_status_finished = ServiceStatus::where('service_id', 6)->where('order', 5)->first()->id;
        $service_status_canceled = ServiceStatus::where('service_id', 6)->where('order', 2)->first()->id;
        $service_status_confirmed = ServiceStatus::where('service_id', 6)->where('order', 3)->first()->id;
        $report = DB::table('regions')
            ->join('citizens', 'citizens.region_id', 'regions.id')
            ->leftJoin('citizen_services', function ($join) {
                $join->on('citizens.id', '=', 'citizen_services.citizen_id');
                $join->where('citizen_services.service_id', 6);
            });
        $report = $report->select('citizens.region_id', 'regions.name_cyrl as region',
            DB::raw("count(citizen_services.*) as count_services"),
            DB::raw("sum(case when citizen_services.service_status_id = $service_status_confirmed then 1 else 0 end) as count_confirmed_education"),
            DB::raw("sum(case when citizen_services.service_status_id = $service_status_performed then 1 else 0 end) as count_performed_education"),
            DB::raw("sum(case when citizen_services.service_status_id = $service_status_canceled then 1 else 0 end) as count_canceled_education"),
            DB::raw("sum(case when citizen_services.service_status_id = $service_status_finished then 1 else 0 end)  as count_finished_education"))
            ->groupBy(['regions.name_cyrl', 'citizens.region_id', 'regions.c_order'])
            ->orderBy('regions.c_order')
            ->get()->toArray();

        $sum = $this->getSumEducationReportOne($service_status_confirmed, $service_status_performed, $service_status_canceled, $service_status_finished);
        array_unshift($report, $sum);
        return $report = [
            'report' => $report
        ];
    }

    public function getSumEducationReportOne($service_status_confirmed, $service_status_performed, $service_status_canceled, $service_status_finished)
    {
        $all = DB::table('citizens')
            ->join('citizen_services', 'citizen_services.citizen_id', '=', 'citizens.id')
            ->select(
                DB::raw("count(citizen_services.*) as count_services"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status_confirmed then 1 else 0 end) as count_confirmed_education"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status_performed then 1 else 0 end) as count_performed_education"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status_canceled then 1 else 0 end) as count_canceled_education"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status_finished then 1 else 0 end)  as count_finished_education"))
            ->where('citizen_services.service_id', 6)
            ->get()->toArray();

        return [
            'region_id' => 'all',
            'region' => 'Жами',
            'count_services' => $all[0]->count_services,
            'count_confirmed_education' => $all[0]->count_confirmed_education,
            'count_performed_education' => $all[0]->count_performed_education,
            'count_canceled_education' => $all[0]->count_canceled_education,
            'count_finished_education' => $all[0]->count_finished_education
        ];
    }

    public function getEducationReportOneByCity($region_id)
    {
        $service_status_performed = ServiceStatus::where('service_id', 6)->where('order', 4)->first()->id;
        $service_status_finished = ServiceStatus::where('service_id', 6)->where('order', 5)->first()->id;
        $service_status_canceled = ServiceStatus::where('service_id', 6)->where('order', 2)->first()->id;
        $service_status_confirmed = ServiceStatus::where('service_id', 6)->where('order', 3)->first()->id;
        $report = DB::table('cities')
            ->join('citizens', 'citizens.city_id', 'cities.id')
            ->leftJoin('citizen_services', function ($join) {
                $join->on('citizens.id', '=', 'citizen_services.citizen_id');
                $join->where('citizen_services.service_id', 6);
            });
        $report = $report->select('citizens.city_id', 'cities.name_cyrl as city',
            DB::raw("count(citizen_services.*) as count_services"),
            DB::raw("sum(case when citizen_services.service_status_id = $service_status_confirmed then 1 else 0 end) as count_confirmed_education"),
            DB::raw("sum(case when citizen_services.service_status_id = $service_status_performed then 1 else 0 end) as count_performed_education"),
            DB::raw("sum(case when citizen_services.service_status_id = $service_status_canceled then 1 else 0 end) as count_canceled_education"),
            DB::raw("sum(case when citizen_services.service_status_id = $service_status_finished then 1 else 0 end)  as count_finished_education"))
            ->groupBy(['cities.name_cyrl', 'citizens.city_id', 'cities.c_order']);
        if ($region_id != 'all')
            $report = $report
                ->where('cities.region_id', $region_id);

        $report = $report
            ->orderBy('cities.c_order')
            ->get()->toArray();

        $sum = $this->getSumEducationReportOneByCity($service_status_confirmed, $service_status_performed, $service_status_canceled, $service_status_finished, $region_id);
        array_unshift($report, $sum);
        return $report = [
            'report' => $report
        ];
    }

    public function getSumEducationReportOneByCity($service_status_confirmed, $service_status_performed, $service_status_canceled, $service_status_finished, $region_id)
    {
        $all = DB::table('citizens')
            ->join('citizen_services', 'citizen_services.citizen_id', '=', 'citizens.id')
            ->select(
                DB::raw("count(citizen_services.*) as count_services"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status_confirmed then 1 else 0 end) as count_confirmed_education"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status_performed then 1 else 0 end) as count_performed_education"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status_canceled then 1 else 0 end) as count_canceled_education"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status_finished then 1 else 0 end)  as count_finished_education"))
            ->where('citizen_services.service_id', 6);
        if ($region_id != 'all')
            $all = $all
                ->where('citizens.region_id', $region_id);
        $all = $all
            ->get()->toArray();

        return [
            'city_id' => 'all',
            'city' => 'Жами',
            'count_services' => $all[0]->count_services ? $all[0]->count_services : 0,
            'count_confirmed_education' => $all[0]->count_confirmed_education ? $all[0]->count_confirmed_education : 0,
            'count_performed_education' => $all[0]->count_performed_education ? $all[0]->count_performed_education : 0,
            'count_canceled_education' => $all[0]->count_canceled_education ? $all[0]->count_canceled_education : 0,
            'count_finished_education' => $all[0]->count_finished_education ? $all[0]->count_finished_education : 0
        ];
    }

    public function getEducationReportTwo()
    {
        $service_status_performed = ServiceStatus::where('service_id', 6)->where('order', 4)->first()->id;
        $report = DB::table('citizen_services')
            ->select(
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Қурилиш%' then 1 else 0 end) as count_construction"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Пайванд%' then 1 else 0 end) as count_welder"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Электр%' then 1 else 0 end) as count_electric"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Сантехн%' then 1 else 0 end) as count_plumber"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%бугалтер%' then 1 else 0 end) as count_accountant"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Компьютер%' then 1 else 0 end) as count_computer"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Автомобил%' then 1 else 0 end) as count_car"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Сартарош%' then 1 else 0 end) as count_barber"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Ошпаз%' then 1 else 0 end) as count_cook"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Тикувчи%' then 1 else 0 end) as count_fashioner"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Мебел%' then 1 else 0 end) as count_furniture"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Ғишт%' then 1 else 0 end) as count_bricklayer"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Рус%' then 1 else 0 end) as count_russian"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Ингли%' then 1 else 0 end) as count_english"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Корейс%' then 1 else 0 end) as count_korean")
            )
            ->where('citizen_services.service_id', 6)
            ->where('citizen_services.service_status_id', $service_status_performed)
            ->get()->toArray();
        $sum = $report[0]->count_construction + $report[0]->count_welder + $report[0]->count_electric + $report[0]->count_plumber + $report[0]->count_accountant + $report[0]->count_computer
            + $report[0]->count_car + $report[0]->count_barber + $report[0]->count_cook + $report[0]->count_fashioner + $report[0]->count_furniture + $report[0]->count_bricklayer;
        $all_prof = DB::table('citizen_services')
            ->select(
                DB::raw("count(citizen_services.id) as count_all")
            )
            ->where('citizen_services.service_id', 6)
            ->where('citizen_services.data->professional_type', 'Касб')
            ->where('citizen_services.service_status_id', $service_status_performed)
            ->get()->toArray();

        $report[0]->count_other = $all_prof[0]->count_all - $sum;
        $report[0]->count_professions = $all_prof[0]->count_all;

        $sum_lang = $report[0]->count_russian + $report[0]->count_english + $report[0]->count_korean;
        $all_lang = DB::table('citizen_services')
            ->select(
                DB::raw("count(citizen_services.id) as count_all")
            )
            ->where('citizen_services.service_id', 6)
            ->where('citizen_services.data->professional_type', 'Чет тили')
            ->where('citizen_services.service_status_id', $service_status_performed)
            ->get()->toArray();

        $report[0]->count_other_lang = $all_lang[0]->count_all - $sum_lang;
        $report[0]->count_langs = $all_lang[0]->count_all;

        $all = DB::table('citizen_services')
            ->select(
                DB::raw("count(citizen_services.id) as all")
            )
            ->where('citizen_services.service_id', 6)
            ->where('citizen_services.service_status_id', $service_status_performed)
            ->get()->toArray();

        $report[0]->all = $all[0]->all;
        return [
            'report' => $report
        ];
    }

    public function getEducationReportThree()
    {
        $service_status_performed = ServiceStatus::where('service_id', 6)->where('order', 4)->first()->id;
        $report = DB::table('regions')
//            ->join('citizens', 'citizens.region_id', 'regions.id')
            ->leftJoin('citizen_services', function ($join) use ($service_status_performed) {
                $join->on('regions.name_cyrl', '=', 'citizen_services.data->region_name');
                $join->where('citizen_services.service_id', 6)
                    ->where('citizen_services.service_status_id', $service_status_performed);
            });
        $report = $report
            ->select('regions.id as region_id', 'regions.name_cyrl as region',
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Қурилиш%' then 1 else 0 end) as count_construction"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Пайванд%' then 1 else 0 end) as count_welder"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Электр%' then 1 else 0 end) as count_electric"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Сантехн%' then 1 else 0 end) as count_plumber"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%бугалтер%' then 1 else 0 end) as count_accountant"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Компьютер%' then 1 else 0 end) as count_computer"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Автомобил%' then 1 else 0 end) as count_car"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Сартарош%' then 1 else 0 end) as count_barber"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Ошпаз%' then 1 else 0 end) as count_cook"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Тикувчи%' then 1 else 0 end) as count_fashioner"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Мебел%' then 1 else 0 end) as count_furniture"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Ғишт%' then 1 else 0 end) as count_bricklayer"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Рус%' then 1 else 0 end) as count_russian"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Ингли%' then 1 else 0 end) as count_english"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Корейс%' then 1 else 0 end) as count_korean")
            )
            ->groupBy(['regions.name_cyrl', 'regions.id', 'regions.c_order'])
            ->orderBy('regions.c_order')
            ->get()->toArray();

        $all_prof = DB::table('regions')
            ->leftJoin('citizen_services', function ($join) use ($service_status_performed) {
                $join->on('regions.name_cyrl', '=', 'citizen_services.data->region_name');
                $join->where('citizen_services.service_id', 6)
                    ->where('citizen_services.data->professional_type', 'Касб')
                    ->where('citizen_services.service_status_id', $service_status_performed);
            })
            ->select('citizen_services.data->region_name as region',
                DB::raw("count(citizen_services.id) as count_all")
            )
            ->groupBy(['regions.name_cyrl', 'citizen_services.data->region_name', 'regions.c_order'])
            ->orderBy('regions.c_order')
            ->get()->toArray();

        $all_lang = DB::table('regions')
            ->leftJoin('citizen_services', function ($join) use ($service_status_performed) {
                $join->on('regions.name_cyrl', '=', 'citizen_services.data->region_name');
                $join->where('citizen_services.service_id', 6)
                    ->where('citizen_services.data->professional_type', 'Чет тили')
                    ->where('citizen_services.service_status_id', $service_status_performed);
            })
            ->select('citizen_services.data->region_name as region',
                DB::raw("count(citizen_services.id) as count_all")
            )
            ->groupBy(['regions.name_cyrl', 'citizen_services.data->region_name', 'regions.c_order'])
            ->orderBy('regions.c_order')
            ->get()->toArray();

        $all = DB::table('regions')
            ->leftJoin('citizen_services', function ($join) use ($service_status_performed) {
                $join->on('regions.name_cyrl', '=', 'citizen_services.data->region_name');
                $join->where('citizen_services.service_id', 6)
                    ->where('citizen_services.service_status_id', $service_status_performed);

            })
            ->select('citizen_services.data->region_name as region',
                DB::raw("count(citizen_services.id) as all")
            )
            ->groupBy(['regions.name_cyrl', 'citizen_services.data->region_name', 'regions.c_order'])
            ->orderBy('regions.c_order')
            ->get()->toArray();

        foreach ($report as $key => $item) {
            $sum = $item->count_construction + $item->count_welder + $item->count_electric + $item->count_plumber + $item->count_accountant + $item->count_computer
                + $item->count_car + $item->count_barber + $item->count_cook + $item->count_fashioner + $item->count_furniture + $item->count_bricklayer;
            $item->count_other = $all_prof[$key]->count_all - $sum;

            $sum_lang = $item->count_russian + $item->count_english + $item->count_korean;
            $item->count_other_lang = $all_lang[$key]->count_all - $sum_lang;
            $item->count_all_lang = $all_lang[$key]->count_all;

            $item->all = $all[$key]->all;
        }

        $all_sum = $this->getSumEducationReportThree($service_status_performed);
        array_unshift($report, $all_sum);
        return [
            'report' => $report
        ];
    }

    public function getSumEducationReportThree($service_status_performed)
    {
        $report = DB::table('citizen_services')
            ->select(
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Қурилиш%' then 1 else 0 end) as count_construction"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Пайванд%' then 1 else 0 end) as count_welder"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Электр%' then 1 else 0 end) as count_electric"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Сантехн%' then 1 else 0 end) as count_plumber"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%бугалтер%' then 1 else 0 end) as count_accountant"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Компьютер%' then 1 else 0 end) as count_computer"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Автомобил%' then 1 else 0 end) as count_car"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Сартарош%' then 1 else 0 end) as count_barber"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Ошпаз%' then 1 else 0 end) as count_cook"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Тикувчи%' then 1 else 0 end) as count_fashioner"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Мебел%' then 1 else 0 end) as count_furniture"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Ғишт%' then 1 else 0 end) as count_bricklayer"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Рус%' then 1 else 0 end) as count_russian"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Ингли%' then 1 else 0 end) as count_english"),
                DB::raw("sum(case when citizen_services.data->>'name' ilike '%Корейс%' then 1 else 0 end) as count_korean")
            )
            ->where('citizen_services.service_id', 6)
            ->where('citizen_services.service_status_id', $service_status_performed)
            ->get()->toArray();

        $all_prof = DB::table('citizen_services')
            ->select(
                DB::raw("count(citizen_services.id) as count_all")
            )
            ->where('citizen_services.service_id', 6)
            ->where('citizen_services.data->professional_type', 'Касб')
            ->where('citizen_services.service_status_id', $service_status_performed)
            ->get()->toArray();

        $all_lang = DB::table('citizen_services')
            ->select(
                DB::raw("count(citizen_services.id) as count_all")
            )
            ->where('citizen_services.service_id', 6)
            ->where('citizen_services.data->professional_type', 'Чет тили')
            ->where('citizen_services.service_status_id', $service_status_performed)
            ->get()->toArray();


        $all = DB::table('citizen_services')
            ->select(
                DB::raw("count(citizen_services.id) as all")
            )
            ->where('citizen_services.service_id', 6)
            ->where('citizen_services.service_status_id', $service_status_performed)
            ->get()->toArray();

        foreach ($report as $key => $item) {
            $sum = $item->count_construction + $item->count_welder + $item->count_electric + $item->count_plumber + $item->count_accountant + $item->count_computer
                + $item->count_car + $item->count_barber + $item->count_cook + $item->count_fashioner + $item->count_furniture + $item->count_bricklayer;
            $item->count_other = $all_prof[$key]->count_all - $sum;

            $sum_lang = $item->count_russian + $item->count_english + $item->count_korean;
            $item->count_other_lang = $all_lang[$key]->count_all - $sum_lang;
            $item->count_all_lang = $all_lang[$key]->count_all;

            $item->all = $all[$key]->all;
        }

        return [
            'region_id' => 'all',
            'region' => 'Жами',
            'count_construction' => $report[0]->count_construction,
            'count_welder' => $report[0]->count_welder,
            'count_electric' => $report[0]->count_electric,
            'count_plumber' => $report[0]->count_plumber,
            'count_accountant' => $report[0]->count_accountant,
            'count_computer' => $report[0]->count_computer,
            'count_car' => $report[0]->count_car,
            'count_barber' => $report[0]->count_barber,
            'count_cook' => $report[0]->count_cook,
            'count_fashioner' => $report[0]->count_fashioner,
            'count_furniture' => $report[0]->count_furniture,
            'count_bricklayer' => $report[0]->count_bricklayer,
            'count_russian' => $report[0]->count_russian,
            'count_english' => $report[0]->count_english,
            'count_korean' => $report[0]->count_korean,
            'count_other' => $report[0]->count_other,
            'count_other_lang' => $report[0]->count_other_lang,
            'count_all_lang' => $report[0]->count_all_lang,
            'all' => $report[0]->all
        ];
    }

    public function getABKMReport()
    {
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $service_key = request()->get('service_key', 'education');
        $service = Service::where('key', $service_key)->first();
        $service_id = $service->id;
        $service_status_id = ServiceStatus::where('service_id', $service_id)->first()->id;
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report = DB::table('regions')
                ->join('users', 'users.region_id', '=', 'regions.id')
                ->leftJoin('citizen_services', function ($join) use ($start, $end, $service_id) {
                    $join->on('users.id', '=', 'citizen_services.user_id');
                    $join->where('citizen_services.created_at', '>=', $start . " 00:00:00");
                    $join->where('citizen_services.created_at', '<=', $end . " 23:59:59");
                });
        } else {
            $report = DB::table('regions')
                ->join('users', 'users.region_id', '=', 'regions.id')
                ->leftJoin('citizen_services', function ($join) use ($service_id) {
                    $join->on('citizen_services.user_id', '=', 'users.id');

                });
        }

        $report = $report->join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', function ($join) {
                $join->on('role_user.role_id', 'roles.id');
            })
            ->select('users.region_id', 'regions.name_cyrl as region',
                DB::raw("sum(case when citizen_services.service_id=$service_id and roles.name='sub_operator' then 1 else 0 end) as total"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status_id
            and citizen_services.service_id=$service_id and roles.name='sub_operator' then 1 else 0 end)  as counts"))
            ->whereNotNull('citizen_services.user_id')
            ->groupBy(['regions.name_cyrl', 'users.region_id', 'regions.c_order'])
            ->orderBy('regions.c_order');

        $report = $report->get()->toArray();
//        $sum = $this->getSumRegionInsurance();
//        array_unshift($report, $sum);
//        $all_data=['report'=>$report, 'report_user'=>$report_user];
        return $report;
    }

    public function getABKMReportByCity()
    {
        $from_date = request()->get('from_date', null);
        $to_date = request()->get('to_date', null);
        $region_id = request()->get('region_id', null);
        $service_key = request()->get('service_key', 'education');
        $status_order = request()->get('order', 1);
        $service_id = Service::where('key', $service_key)->first()->id;
        $service_status_id = ServiceStatus::where('service_id', $service_id)->where('order', $status_order)->first()->id;
        if ($from_date and $to_date) {
            $start = date("Y-m-d", strtotime($from_date));
            $end = date('Y-m-d', strtotime($to_date));
            $report = DB::table('cities')
                ->join('users', 'users.city_id', '=', 'cities.id')
                ->leftJoin('citizen_services', function ($join) use ($start, $end) {
                    $join->on('users.id', '=', 'citizen_services.user_id');
                    $join->where('citizen_services.created_at', '>=', $start . " 00:00:00");
                    $join->where('citizen_services.created_at', '<=', $end . " 23:59:59");
                });
        } else {
            $report = DB::table('cities')
                ->join('users', 'users.city_id', '=', 'cities.id')
                ->leftJoin('citizen_services', function ($join) {
                    $join->on('citizen_services.user_id', '=', 'users.id');
                });
        }

        $report = $report->join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', function ($join) {
                $join->on('role_user.role_id', 'roles.id');
                $join->where('roles.name', 'sub_operator');
            })
            ->select('users.city_id', 'users.fullname as city',
                DB::raw("sum(case when citizen_services.service_id=$service_id then 1 else 0 end) as total"),
                DB::raw("sum(case when citizen_services.service_status_id = $service_status_id
            and citizen_services.service_id=$service_id then 1 else 0 end)  as counts"))
            ->where('users.region_id', $region_id)
            ->groupBy(['users.fullname', 'users.city_id', 'cities.c_order'])
            ->orderBy('cities.c_order');

        $report = $report->get()->toArray();
//        $sum = $this->getSumRegionInsurance();
//        array_unshift($report, $sum);
//        $all_data=['report'=>$report, 'report_user'=>$report_user];
        return $report;
    }

    public function getReportSectorCitizenInfo()
    {
        $user =Auth::guard()->user();
        $region_id = $user->region_id;
        $city_id = request()->get('city_id', null);
        $makhalla_id = request()->get('makhalla_id', null);
        $sector_id = null;

        $service_key = request()->get('service_key', 'sector');
        $service_id = Service::where('key', $service_key)->first()->id;

        if($user->hasRole('city')){
            $city_id = $user->city_id;
        }
        if ($user->hasRole('sector')){
            $sector_id = $user->sector_id ? $user->sector_id : null;
            $city_id = $user->city_id;

        }
        $report_citizen = DB::table('cities')
            ->select(
                'cities.id as city_id', 'cities.name_cyrl', 'citizen_services.data->sector_id as sector_id',
                DB::raw("sum(case when citizen_services.id<>0 then 1 else 0 end) as total_citizen"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='employment' then 1 else 0 end) as count_family_problem_employment"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='education' then 1 else 0 end) as count_family_problem_education"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='medical_care' then 1 else 0 end) as count_family_problem_medical_care"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='communal' then 1 else 0 end) as count_family_problem_communal"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='entrepreneurship' then 1 else 0 end) as count_family_problem_entrepreneurship"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='house' then 1 else 0 end) as count_family_problem_house"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='other' then 1 else 0 end) as count_family_problem_other"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='lose_passport' then 1 else 0 end) as count_foreign_problem_lose_passport"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='unemployed' then 1 else 0 end) as count_foreign_problem_unemployed"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='unpaid' then 1 else 0 end) as count_foreign_problem_unpaid"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='repatriation' then 1 else 0 end) as count_foreign_problem_repatriation"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='financial' then 1 else 0 end) as count_foreign_problem_financial"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='other' then 1 else 0 end) as count_foreign_problem_other")
            )
            ->leftJoin('citizens', 'citizens.city_id', '=', 'cities.id')
            ->leftJoin('citizen_services', 'citizen_services.citizen_id', '=', 'citizens.id')
            ->groupBy(['cities.name_cyrl', 'cities.id', 'citizen_services.data->sector_id'])
            ->orderBy('cities.name_cyrl', 'ASC')
            ->orderBy('citizen_services.data->sector_id', 'ASC')
            ->where('citizens.region_id', $region_id)
            ->where('citizen_services.service_id', $service_id);



        if ($city_id and is_numeric($city_id)) {
            $report_citizen = $report_citizen
                ->where('citizens.city_id', $city_id);
        }

        if ($makhalla_id and is_numeric($makhalla_id)) {
            $report_citizen = $report_citizen
                ->where('citizen_services.data->address.makhalla_id', $makhalla_id);
        }

        if ($sector_id and is_numeric($sector_id)) {
            $report_citizen = $report_citizen
                ->where('citizen_services.data->sector_id', $sector_id);
        }

        if (\request()->get('getAll', false)) {
            $report_citizen = $report_citizen->get()->toArray();
            $sum = $this->getSumReportSectorCitizenInfo();
            array_unshift($report_citizen, $sum);
            $report_citizen = ['data' => $report_citizen];

        } else {
            $report_citizen = $report_citizen->paginate(request()->get('limit', 20));
        }

        return $report_citizen;
    }

    public function getSumReportSectorCitizenInfo()
    {
        $user =Auth::guard()->user();
        $region_id = $user->region_id;
        $city_id = request()->get('city_id', null);
        $makhalla_id = request()->get('makhalla_id', null);

        $service_key = request()->get('service_key', 'sector');
        $service_id = Service::where('key', $service_key)->first()->id;

        $sector_id = 'all';
        if($user->hasRole('city')){
            $city_id = $user->city_id;
        }
        if ($user->hasRole('sector')){
            $sector_id = $user->sector_id ? $user->sector_id : null;
            $city_id = $user->city_id;

        }
        $report_citizen = DB::table('citizen_services')
            ->select(
                DB::raw("sum(case when citizen_services.id<>0 then 1 else 0 end) as total_citizen"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='employment' then 1 else 0 end) as count_family_problem_employment"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='education' then 1 else 0 end) as count_family_problem_education"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='medical_care' then 1 else 0 end) as count_family_problem_medical_care"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='communal' then 1 else 0 end) as count_family_problem_communal"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='entrepreneurship' then 1 else 0 end) as count_family_problem_entrepreneurship"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='house' then 1 else 0 end) as count_family_problem_house"),
                DB::raw("sum(case when citizen_services.data->>'family_problems'='other' then 1 else 0 end) as count_family_problem_other"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='lose_passport' then 1 else 0 end) as count_foreign_problem_lose_passport"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='unemployed' then 1 else 0 end) as count_foreign_problem_unemployed"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='unpaid' then 1 else 0 end) as count_foreign_problem_unpaid"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='repatriation' then 1 else 0 end) as count_foreign_problem_repatriation"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='financial' then 1 else 0 end) as count_foreign_problem_financial"),
                DB::raw("sum(case when citizen_services.data->>'foreign_problems'='other' then 1 else 0 end) as count_foreign_problem_other")

            )
            ->leftJoin('citizens', 'citizens.id', '=', 'citizen_services.citizen_id')
            ->where('citizens.region_id', $region_id)
            ->where('citizen_services.service_id', $service_id)
        ;

        if ($city_id and is_numeric($city_id)) {
            $report_citizen = $report_citizen
                ->where('citizens.city_id', $city_id);
        }

        if ($makhalla_id and is_numeric($makhalla_id)) {
            $report_citizen = $report_citizen
                ->where('citizen_services.data->address.makhalla_id', $makhalla_id);
        }

        if ($sector_id and is_numeric($sector_id)) {
            $report_citizen = $report_citizen
                ->where('citizen_services.data->sector_id', $sector_id);
        }

        $report_citizen = $report_citizen ->get()->toArray();

        $data = [
            'city_id' => $city_id,
            'name_cyrl' => 'Жами',
            'sector_id' => $sector_id,
            'total_citizen' => $report_citizen[0]->total_citizen,
            'count_family_problem_employment' => $report_citizen[0]->count_family_problem_employment,
            'count_family_problem_education' => $report_citizen[0]->count_family_problem_education,
            'count_family_problem_medical_care' => $report_citizen[0]->count_family_problem_medical_care,
            'count_family_problem_communal' => $report_citizen[0]->count_family_problem_communal,
            'count_family_problem_house' => $report_citizen[0]->count_family_problem_house,
            'count_family_problem_entrepreneurship' => $report_citizen[0]->count_family_problem_entrepreneurship,
            'count_family_problem_other' => $report_citizen[0]->count_family_problem_other,
            'count_foreign_problem_lose_passport' => $report_citizen[0]->count_foreign_problem_lose_passport,
            'count_foreign_problem_unemployed' => $report_citizen[0]->count_foreign_problem_unemployed,
            'count_foreign_problem_unpaid' => $report_citizen[0]->count_foreign_problem_unpaid,
            'count_foreign_problem_repatriation' => $report_citizen[0]->count_foreign_problem_repatriation,
            'count_foreign_problem_financial' => $report_citizen[0]->count_foreign_problem_financial,
            'count_foreign_problem_other' => $report_citizen[0]->count_foreign_problem_other
        ];
        return $data;
    }

    public function getSectorCitizenInfo()
    {
        $service_key = request()->get('service_key', 'sector');
        $service_id = Service::where('key', $service_key)->first()->id;

        $report_citizen = CitizenService:: with('citizen:id,s_name,f_name,m_name','region:id,name_cyrl', 'city:id,name_cyrl', 'makhalla:id,name', 'country:id,name_cyrl')
            ->orderBy('id', 'desc')
            ->where('citizen_services.service_id', $service_id);

        $city_id = request()->get('city_id', null);
        $sector_id = request()->get('sector_id', null);
        $makhalla_id = request()->get('makhalla_id', null);

        if ($city_id and is_numeric($city_id)) {
            $report_citizen = $report_citizen
                ->where('citizen_services.data->address->city_id', $city_id);
        }

        if ($sector_id and is_numeric($sector_id)) {
            $report_citizen = $report_citizen
                ->where('citizen_services.data->sector_id', $sector_id);
        }

        if ($makhalla_id and is_numeric($makhalla_id)) {
            $report_citizen = $report_citizen
                ->where('citizen_services.data->address.makhalla_id', $makhalla_id);
        }

        if (\request()->get('getAll', false)) {
            $report_citizen = ['data' => $report_citizen->get()];

        } else {
            $report_citizen = $report_citizen ->paginate(request()->get('limit', 20));
        }

        return $report_citizen;
    }

}
