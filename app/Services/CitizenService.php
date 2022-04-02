<?php

namespace App\Services;

use App\Models\Citizen;
use App\Models\Region;
use App\Repositories\CitizenRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CitizenService
{

    /**
     * @var CitizenRepository
     */
    private $repository;

    const REGION_ID = 21;

    public function __construct()
    {
        $this->repository = new CitizenRepository();
    }

    public function getAll(Request $request)
    {
        $user = Auth::user();
        $region = Region::query();
        $query = Citizen::query();
//            ->with('region:id, name_cyrl')
//            ->with('district:id')
//            ->get();
//
        return [
            'current_page' => $request->page ?? 1,
            'per_page' => $request->limit,
            'data' => $query
                ->with('region:id,name_cyrl')
                ->with('district')
                ->get(),
            'total' => $query->count() < $request->limit ? $citizens->count() : -1
        ];

        if ($user->role_id == Citizen::ADMIN){
            return $query->get();
        }
        if ($user->role_id == Citizen::REGION){
            $query->where(['region_id' => $user->region_id]);
            return $query->get();
        }
        if ($user->role_id == Citizen::DISTRICT){
            $query->where(['district_id' => $user->district_id]);
            return $query->get();
        }
    }
    public function store($request)
    {
        $user = Auth::user();
        $validator = $this->repository->toValidate($request->all());
        $msg = "";
        if (!$validator->fails()){
            if ($user->role_id == Citizen::ADMIN){
                return response()->errorJson('Рухсат мавжуд емас', 101);
            }
            if ($user->role_id == Citizen::REGION){
                return response()->errorJson('Рухсат мавжуд емас', 101);
            }
            if ($user->role_id == Citizen::CITY){
                if ($request->city_id != $user->city_id){
                    return response()->errorJson('Рухсат мавжуд емас', 101);
                }
                $citizen = $this->repository->store($request);
                return response()->successJson(['citizen' => $citizen]);
            }
        }
        else{
            $errors = $validator->failed();
            if(empty($errors)) {
                $msg = "Соҳалар нотўғри киритилди";
            }
            return response()->errorJson($msg, 400, $errors);
        }

    }

    public function show($id)
    {
        $user = Auth::user();
        $query = Citizen::query();
        return $query->where(['id' => $id])->get();

        if (empty($query->first())){
            return response()->errorJson('Бундай ид ли фойдаланувчи мавжуд емас', 409);
        }
        if ($user->role_id == Citizen::ADMIN){
            return $query->first();
        }
        if ($user->role_id == Citizen::REGION){
            $query->where(['region_id' => $user->region_id]);
            if (empty($query->first())){
                return response()->errorJson('Рухсат мавжуд емас', 101);
            }
            return $query->first();
        }
        if ($user->role_id == Citizen::CITY){
            $query->where(['city_id' => $user->city_id]);
            if (empty($query->first())){
                return response()->errorJson('Рухсат мавжуд емас', 101);
            }
            return $query->first();
        }

    }

    public function update($request, $id){
        $citizen = DB::table('citizens')->where(['id' => $id])->first();


    }


//    {
//        $type = \request('type', 'young');
//        $user = $this->repository->guard()->user();
//        $passports = ['AA0215962'];
//        if(($user->id == 262) && !in_array($request->passport, $passports)) {
//            return response()->errorJson('Маълумот топилмади!', 404);
//        }
//
//
//        $citizen = $this->repository->getQuery()->where('passport', $request->passport)->first();
//        $citizenActionCheck = $citizen->citizenAction;
//        if($citizenActionCheck && !$citizenActionCheck->survey_create) {
//            return response()->errorJson('Фуқарони ёшлар (аёллар) дафтарига рўйҳатга олиш учун фуқаро бандлиги таъминланмаган ва ижтимоий тоифаси аниқланмаган бўлиши керак!', 409);
//        }
//
//        if($citizen && ($user->city_id != $citizen->city_id)) {
//            return response()->errorJson('Маълумот топилмади!', 404);
//        }
//        if($citizen) {
//
//            $birth_year = $citizen->birth_date;
//            $birth_year = (int) explode('-', $birth_year)[0];
//            $current = (int) date('Y');
//            $age = (int) $current - $birth_year;
//
//            if($type && $type == 'woman') {
//                $gender = $citizen->gender;
//                if($gender == 1) {
//                    return response()->errorJson('Фуқаро жинси эркак бўлганлиги сабабли, аёллар дафтарига киритиб бўлмайди!', 400);
//                }
//
//                if($age < 18) {
//                    return response()->errorJson('Фуқаро 18 ёшдан кичик бўлганлиги сабабли, аёллар дафтарига киритиб бўлмайди!', 400);
//                }
//
//                if($age > 55) {
//                    return response()->errorJson('Фуқаро 55 ёшдан катта бўлганлиги сабабли, аёллар дафтарига киритиб бўлмайди!', 400);
//                }
//            }
//
//            if($type && $type == 'young') {
//                if($birth_year) {
//
//                    if($age > 30) {
//                        return response()->errorJson('Фуқаро 30 ёшдан катта бўлганлиги сабабли, ёшлар дафтарига киритиб бўлмайди!', 400);
//                    }
//                }
//            }
//            return response()->successJson($citizen);
//        } else {
//            return response()->errorJson('Фуқаро топилмади!', 404);
//        }
//    }
//
//    public function passport($request)
//    {
//        $type = \request('type', 'young');
//        $user = $this->repository->guard()->user();
//        $passports = ['AA0215962'];
//
//        if(($user->id == 262) && !in_array($request->passport, $passports)) {
//            return ['msg' => 'Маълумот топилмади', 'status' => 404];
//        }
//        $result = [];
//        if($request->birth_date){
//            $data = $this->resourceRepo->getMvdPassportData($request->passport, $request->birth_date);
//            if (!isset($data['result']['pPinpp'])) {
//                $error = isset($data['error']) ? $data['error'] : [];
//                return ['msg' => 'Маълумот топилмади', 'status' => 404];
//            } else {
//                $result = ['citizen' => $data['result']];
//            }
//        } else {
//            $data = $this->resourceRepo->getPassportData($request->passport);
//            if (isset($data['result'])) {
//
//                $tin = $data['result']['tin'] ?? null;
//
//                if (isset($tin)) {
//                    $data['result']['tin'] = $tin;
//
//                    $pin = $this->resourceRepo->getPin($tin);
//
//                    if (!is_null($pin)) {
//                        $data['result']['pin'] = $pin;
//                        $result = ['citizen' => $data['result']];
//                    } else {
//                        return ['msg' => 'Pin not found', 'status' => 404];
//                    }
//                } else {
//                    return ['msg' => 'Tin not found', 'status' => 404];
//                }
//
//            } else {
//                $error = isset($data['error']) ? $data['error'] : [];
//                return ['msg' => 'Маълумот топилмади', 'status' => 404, 'error' => $error];
//            }
//        }
//
//        if(isset($data['result']) && !empty($data['result'])) {
//
//            $birth_year = $data['result']['date_birth'] ?? $data['result']['pDateBirth'];
//            $birth_year = (int) explode('.', $birth_year)[2];
//            $current = (int) date('Y');
//            $age = (int) $current - $birth_year;
//
//            $check = $this->repository->getQuery()->where('passport', $request->passport)->first();
//
//            if($check) {
//                return ['msg' => 'Ушбу фуқаро аввал рўйхатга олинган!', 'status' => 409, 'code' => 'db'];
//            }
//
//            if($type && $type == 'woman') {
//                $gender = $data['result']['gender'] ?? $data['result']['pSex'];
//                if($gender == 1) {
//                    return ['msg' => 'Фуқаро жинси эркак бўлганлиги сабабли, аёллар дафтарига киритиб бўлмайди!', 'status' => 409, 'code' => 'db'];
//                }
//
//                if($age < 18) {
//                    return ['msg' => 'Фуқаро 18 ёшдан кичик бўлганлиги сабабли, аёллар дафтарига киритиб бўлмайди!', 'status' => 409, 'code' => 'db'];
//                }
//
//                if($age > 55) {
//                    return ['msg' => 'Фуқаро 55 ёшдан катта бўлганлиги сабабли, аёллар дафтарига киритиб бўлмайди!', 'status' => 409, 'code' => 'db'];
//                }
//            }
//
//            if($type && $type == 'young') {
//                if($birth_year) {
//
//                    if($age > 30) {
//                        return ['msg' => 'Фуқаро 30 ёшдан катта бўлганлиги сабабли, ёшлар дафтарига киритиб бўлмайди!', 'status' => 409, 'code' => 'db'];
//                    }
//                }
//            }
//            return ['status' => 200, 'citizen' => $result];
//        }
//    }
//
//    public function update($request, $id)
//    {
//        $msg = "";
//        $validator = $this->repository->toValidate($request->all());
//
//        if (!$validator->fails()) {
//            if($this->repository->getQuery()->where('id', '!=', $id)->where('phone', $request->phone)->first()) {
//                return ['msg' => 'Ушбу телефон рақами аввал киритилган!', 'status' => 409];
//            }
//            if(!$this->repository->checkCitizen($request->passport, $id)) {
//                $citizen = $this->repository->update($request, $id);
//                return  ['status' => 200, 'citizen' => $citizen];
//            } else {
//                return ['msg' => 'Bu ma\'lumotlar bazada mavjud', 'status' => 409];
//            }
//        } else {
//            $errors = $validator->failed();
//            if(empty($errors)) {
//                $msg = "Соҳалар нотўғри киритилди";
//            }
//            return ['msg' => $msg, 'status' => 422, 'error' => $errors];
//        }
//    }
//
//
//    public function show($citizen)
//    {
//        $citizen->region;
//        $citizen->city;
//        $citizen->add_field;
//        $citizen->field;
//        $citizen->other_social;
//        $citizen->family_status;
//        $citizen->place_status;
//        $citizen->reason;
//        $citizen->makhalla;
////        $citizen->citizen_status;
//        $citizen->student;
//        $citizen->university;
//        $citizen->retiree;
//        $citizen->school_graduate;
//        $citizen->self_employment;
//        $citizen->migrant;
//        $citizen->seperated_land;
////        $citizen->with('citizen_status');
//        if($citizen->credited) {
//            $citizen->credited->bank;
//        }
//
//        if($citizen->complaints) {
//            $citizen->complaints;
//            $citizen->complaints->complaintType;
//            $citizen->complaints->complaintDenyReasons;
//        }
//
//        $citizen->employment;
//
//        $position = null;
//        try {
//            if($citizen->is_employer && $citizen->citizen_status_id != 8) {
//                $position_data = $this->resourceRepo->getFulldata($citizen->pin);
//                if(!empty($position_data))
//                    $position = $position_data['result'];
//            }
//        } catch(\Exception $exception) {
//
//        }
//        $citizen->position = $position;
//        return $citizen;
//    }
//
//    public function getSeparatedLandCitizen(Request $request)
//    {
//        $condition = [];
//
//        $citizens = app(Pipeline::class)
//            ->send($this->repository->getQuery())
//            ->through([
//                \App\QueryFilters\Pin::class,
//                \App\QueryFilters\Name::class,
//                \App\QueryFilters\Passport::class,
//                \App\QueryFilters\Region::class,
//                \App\QueryFilters\District::class,
//                \App\QueryFilters\Gender::class,
//                \App\QueryFilters\Makhalla::class,
//                \App\QueryFilters\LivingPlace::class,
//            ])
//            ->thenReturn()
//            ->with('region:id,name_cyrl')
//            ->with('seperated_land')
//            ->with('makhalla')
//            ->with('city');
//
//        $citizens = $citizens->select([
//            'citizens.id',
//            'citizens.firstname',
//            'citizens.surname',
//            'citizens.patronymic',
//            'citizens.pin',
//            'citizens.passport',
//            'citizens.region_id',
//            'citizens.city_id',
//            'citizens.living_place',
//            'citizens.makhalla_id',
//            'citizens.birth_date',
//            'citizens.gender'
//        ])->where($condition)
//            ->leftJoin('separated_credits', function($join) {
//                $join->on('separated_credits.pin', '=', 'citizens.pin');
//            });
//
//        if ($request->get('count')) {
//            return $citizens->count();
//        }
//
//        return $citizens->paginate($request->get('limit', 50));
//    }

}
