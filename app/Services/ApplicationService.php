<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Citizen;
use App\Models\Region;
use App\Models\Role;
use App\Repositories\ApplicationRepository;
use App\Repositories\ResourceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationService
{
    protected $response = [
        'success' => true,
        'result' => [],
        'error' => []
    ];

    private $repo;
    private $resourceRepo;

    public function __construct()
    {
        $this->repo = new ApplicationRepository();
        $this->resourceRepo = new ResourceRepository;

    }

    public function guard()
    {
        return Auth::guard();
    }

//    public function getAll(Request $request)
//    {
//        $user = $this->guard()->user();
//        $query = Application::query();
//
//        if ($user->role_id == Application::REGION){
//            $query->where(['region_id' => $user->region_id]);
//        }
//        if ($user->role_id == Application::DISTRICT){
//            $query->where(['district_id' => $user->district_id]);
////                ->with('region:id,name_cyrl')
////                ->with('district')
////                ->get();
//        }
////
//        return [
//            'current_page' => $request->page ?? 1,
//            'per_page' => $request->limit,
//            'data' => $query
//                ->with('region:id,name_cyrl')
//                ->with('district')
//                ->get(),
//            'total' => $query->count() < $request->limit ? $Applications->count() : -1
//        ];
//    }

    public function getAll(Request $request)
    {
        $user = Auth::user();
        $query = Application::query()
            ->with('region:id,name_cyrl')
            ->with('district')
            ->with('socialStatus')
            ->with('denyReason');

//        dd($query->get());
//        return $user->role_id;
        if ($user->role_id === Application::REGION_GOVERNMENT) {
            $query->where(['region_id' => $user->region_id]);
        }
        if ($user->role_id === Application::DISTRICT_GOVERNMENT) {
            $query->where(['district_id' => $user->district_id]);
        }

        if (!empty($request->all()['status'])) {
            $query->where(['status' => $request->all()['status']]);
        }
        if (!empty($request->all()['region_id'])) {
            $query->where(['region_id' => $request->all()['region_id']]);
        }
        if (!empty($request->all()['district_id'])) {
            $query->where(['district_id' => $request->all()['district_id']]);
        }
        if (!empty($request->all()['social_id'])) {
            $query->where(['social_id' => $request->all()['social_id']]);
        }
        if (!empty($request->all()['l_name'])) {
            $query->where('Applications.l_name', 'like', '%' . $request->all()['l_name'] . '%');
        }
        if (!empty($request->all()['f_name'])) {
            $query->where('Applications.f_name', 'like', '%' . $request->all()['f_name'] . '%');
        }
        if (!empty($request->all()['m_name'])) {
            $query->where('Applications.m_name', 'like', '%' . $request->all()['m_name'] . '%');
        }
        if (!empty($request->all()['passport'])) {
            $query->where('Applications.passport', 'like', '%' . $request->all()['passport'] . '%');
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
            'data' => $query->get(),
            'total' => $query->count() < $request->limit ? $query->count() : -1,
        ];
    }

    public function store($request)
    {
//        $user = Auth::user();

        $validator = $this->repo->toValidate($request->all());
        $msg = "";
//        $Application = $this->repo->store($request);
//        return response()->successJson(['Application' => $Application]);

        if (!$validator->fails()){
//            dd('keldi');
            $Application = $this->repo->store($request);
            return response()->successJson(['Application' => $Application]);
        }
        else{
//            dd('kemadi');
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
//        $query = Application::query();
        $query = Application::query()
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

    public function update($request, $id)
    {
        $msg = "";
        $validator = $this->repo->toValidate($request->all());

        if (!$validator->fails()) {

            if (!$this->repo->checkApplication($request->passport, $id)) {
                $Application = $this->repo->update($request, $id);
                return ['status' => 200, 'Application' => $Application];
            } else {
                return ['msg' => 'Bu ma\'lumotlar bazada mavjud', 'status' => 409];
            }
        } else {
            $errors = $validator->failed();
            if (empty($errors)) {
                $msg = "Соҳалар нотўғри киритилди";
            }
            return ['msg' => $msg, 'status' => 422, 'error' => $errors];
        }
    }

    public function rejected($request)
    {
        $application = $this->repo->rejected($request);
        return ['status' => 200, 'application' => $application];
    }

    public function confirmed($request)
    {
        $data = $this->repo->confirmed($request);
        return ['status' => 200, 'data' => $data];
    }

    public function check($request)
    {
        $application = $this->repo->check($request);
        return ['status' => 200, 'application' => $application];
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
            $query = Application::query()->where(['pin' => $pin]);
            $application = $query->get();
            if(count($application) === 0){
                $result = ['citizen' => [
                    'pin' => $pin,
                    'l_name' => $data['result']['surname_latin'],
                    "f_name" => $data['result']['name_latin'],
                    "m_name" => $data['result']['patronym_latin'],
                    "birth_date" => date('d.m.Y', strtotime($birth_year)),
                ]];
                $application = $query->get();
//            dd($result['citizen']);
                return ['status' => 200, 'citizen' => $result];
            } else {
                return ['msg' => 'Bu ma\'lumotlar bazada mavjud', 'status' => 409, 'application' => $application];
            }

        }
    }
}
