<?php

namespace App\Services;

use App\Models\Citizen;
use App\Models\Region;
use App\Models\Role;
use App\Repositories\CitizenRepository;
use App\Repositories\ResourceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CitizenService
{
    private $resourceRepo;

    private $repository;

    public function __construct()
    {
        $this->repository = new CitizenRepository();
        $this->resourceRepo = new ResourceRepository;

    }

    public function guard()
    {
        return Auth::guard();
    }

    public function getAll(Request $request)
    {
        $user = Auth::user();
        $query = Citizen::query()
            ->with('region:id,name_cyrl')
            ->with('district')
            ->with('socialStatus');


        if ($user->role == Citizen::REGION_GOVERNMENT){
            $query->where(['region_id' => $user->region_id]);
        }
        if ($user->role_id == Citizen::DISTRICT_GOVERNMENT){
            $query->where(['district_id' => $user->district_id]);
        }

        if (!empty($request->all()['region_id'])){
            $query->where(['region_id' => $request->all()['region_id']]);
        }
        if (!empty($request->all()['district_id'])){
            $query->where(['district_id' => $request->all()['district_id']]);
        }
        if (!empty($request->all()['social_id'])){
            $query->where(['social_id' => $request->all()['social_id']]);
        }
        if (!empty($request->all()['l_name'])){
            $query->where('citizens.l_name', 'like', '%'. $request->all()['l_name'].'%');
        }
        if (!empty($request->all()['f_name'])){
            $query->where('citizens.f_name', 'like', '%'. $request->all()['f_name'].'%');
        }
        if (!empty($request->all()['m_name'])){
            $query->where('citizens.m_name', 'like', '%'. $request->all()['m_name'].'%');
        }
        if (!empty($request->all()['passport'])){
            $query->where('citizens.passport', 'like', '%'. $request->all()['passport'].'%');
        }

//        $query->paginate($request->limit)->toArray();
//        if($request->has('getAll')){
//            $query = $query->paginate($query->count());
//        } else {
//            $query = $query->paginate($request->get('limit', 30));
//        }
        return [
            'current_page' => $request->page ?? 1,
            'per_page' => $request->limit,
            'data' => $citizens,
            'total' => $citizens->count() < $request->limit ? $citizens->count() : -1
        ];

//



    }
    public function passport($request)
    {
        $type = \request('type', 'young');
        $user = $this->repository->guard()->user();
        $passports = ['AA0215962'];

        if(($user->id == 262) && !in_array($request->passport, $passports)) {
            return ['msg' => 'Маълумот топилмади', 'status' => 404];
        }
        $result = [];
        if($request->birth_date){
            $data = $this->resourceRepo->getMvdPassportData($request->passport, $request->birth_date);
            if (!isset($data['result']['pPinpp'])) {
                $error = isset($data['error']) ? $data['error'] : [];
                return ['msg' => 'Маълумот топилмади', 'status' => 404];
            } else {
                $result = ['citizen' => $data['result']];
            }
        } else {
            $data = $this->resourceRepo->getPassportData($request->passport);
            if (isset($data['result'])) {

                $tin = $data['result']['tin'] ?? null;

                if (isset($tin)) {
                    $data['result']['tin'] = $tin;

                    $pin = $this->resourceRepo->getPin($tin);

                    if (!is_null($pin)) {
                        $data['result']['pin'] = $pin;
                        $result = ['citizen' => $data['result']];
                    } else {
                        return ['msg' => 'Pin not found', 'status' => 404];
                    }
                } else {
                    return ['msg' => 'Tin not found', 'status' => 404];
                }

            } else {
                $error = isset($data['error']) ? $data['error'] : [];
                return ['msg' => 'Маълумот топилмади', 'status' => 404, 'error' => $error];
            }
        }

        if(isset($data['result']) && !empty($data['result'])) {

            $birth_year = $data['result']['date_birth'] ?? $data['result']['pDateBirth'];
            $birth_year = (int) explode('.', $birth_year)[2];
            $current = (int) date('Y');
            $age = (int) $current - $birth_year;

            $check = $this->repository->getQuery()->where('passport', $request->passport)->first();

            if($check) {
                return ['msg' => 'Ушбу фуқаро аввал рўйхатга олинган!', 'status' => 409, 'code' => 'db'];
            }

            if($type && $type == 'woman') {
                $gender = $data['result']['gender'] ?? $data['result']['pSex'];
                if($gender == 1) {
                    return ['msg' => 'Фуқаро жинси эркак бўлганлиги сабабли, аёллар дафтарига киритиб бўлмайди!', 'status' => 409, 'code' => 'db'];
                }

                if($age < 18) {
                    return ['msg' => 'Фуқаро 18 ёшдан кичик бўлганлиги сабабли, аёллар дафтарига киритиб бўлмайди!', 'status' => 409, 'code' => 'db'];
                }

                if($age > 55) {
                    return ['msg' => 'Фуқаро 55 ёшдан катта бўлганлиги сабабли, аёллар дафтарига киритиб бўлмайди!', 'status' => 409, 'code' => 'db'];
                }
            }

            if($type && $type == 'young') {
                if($birth_year) {

                    if($age > 30) {
                        return ['msg' => 'Фуқаро 30 ёшдан катта бўлганлиги сабабли, ёшлар дафтарига киритиб бўлмайди!', 'status' => 409, 'code' => 'db'];
                    }
                }
            }
            return ['status' => 200, 'citizen' => $result];
        }
    }
    public function store($request)
    {

        $user = Auth::user();

        $validator = $this->repository->toValidate($request->all());
        $msg = "";

//        $citizen = $this->repository->store($request);

//        return response()->successJson(['citizen' => $citizen]);

        if (!$validator->fails()){


            if ($user->role_id == Role::ADMIN){
                return response()->errorJson('Рухсат мавжуд емас', 101);
            }
            if ($user->role_id == Role::REGION){
                return response()->errorJson('Рухсат мавжуд емас', 101);
            }
            if ($user->role_id == Role::DISTRICT){
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
//        $query = Citizen::query();
        $query = Citizen::query()
            ->with('region:id,name_cyrl')
            ->with('district')
            ->with('socialStatus');
        $query->where(['id' => $id]);
//            ->with('region:id, name_cyrl')
//            ->with('district');

        if (empty($query->first())){
            return response()->errorJson('Бундай ид ли фойдаланувчи мавжуд емас', 409);
        }
        return $query->first();
    }

    public function update($request, $id){
//        $msg = "";
//        $validator = $this->repository->toValidate($request->all());
//
//        $citizen = $this->repository->update($request, $id);
//        return ['status' => 200, 'citizen' => $citizen];
//        $citizen = DB::table('citizens')->where(['id' => $id])->first();

        $msg = "";
        $validator = $this->repository->toValidate($request->all());

        if (!$validator->fails()) {

            if(!$this->repository->checkCitizen($request->passport, $id)) {
                $citizen = $this->repository->update($request, $id);
                return  ['status' => 200, 'citizen' => $citizen];
            } else {
                return ['msg' => 'Bu ma\'lumotlar bazada mavjud', 'status' => 409];
            }
        } else {
            $errors = $validator->failed();
            if(empty($errors)) {
                $msg = "Соҳалар нотўғри киритилди";
            }
            return ['msg' => $msg, 'status' => 422, 'error' => $errors];
        }
    }

    public function idCard($request)
    {
//        dd($request->all());
        $data = $this->resourceRepo->getIpsPersonData($request->passport, $request->pin);
//        dd($data);
        if (!isset($data['result'])) return ['msg' => 'Маълумот топилмади', 'status' => 404, 'error' => []];
        else {

            $pin = $request->pin;
            $birth_year = $data['result']['birth_date'];

            $result = ['citizen' => [
                'pin' => $pin,
                'l_name' => $data['result']['surname_latin'],
                "f_name" => $data['result']['name_latin'],
                "m_name" => $data['result']['patronym_latin'],
                "birth_date" => date('d.m.Y', strtotime($birth_year)),
            ]];
//            dd($result['citizen']);
            return ['status' => 200, 'citizen' => $result];
        }
    }

}
