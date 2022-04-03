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
        $query = Citizen::query();

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
        return [
            'current_page' => $request->page ?? 1,
            'per_page' => $request->limit,
            'data' => $query
                ->with('region:id,name_cyrl')
                ->with('district')
                ->get(),
            'total' => $query->count() < $request->limit ? $citizens->count() : -1
        ];
    }
    public function store($request)
    {
        $user = Auth::user();
        $validator = $this->repository->toValidate($request->all());
        $msg = "";
        $citizen = $this->repository->store($request);
        return response()->successJson(['citizen' => $citizen]);

//        if (!$validator->fails()){
//            if ($user->role_id == Citizen::ADMIN){
//                return response()->errorJson('Рухсат мавжуд емас', 101);
//            }
//            if ($user->role_id == Citizen::REGION){
//                return response()->errorJson('Рухсат мавжуд емас', 101);
//            }
//            if ($user->role_id == Citizen::DISTRICT){
//                if ($request->city_id != $user->city_id){
//                    return response()->errorJson('Рухсат мавжуд емас', 101);
//                }
//                $citizen = $this->repository->store($request);
//                return response()->successJson(['citizen' => $citizen]);
//            }
//        }
//        else{
//            $errors = $validator->failed();
//            if(empty($errors)) {
//                $msg = "Соҳалар нотўғри киритилди";
//            }
//            return response()->errorJson($msg, 400, $errors);
//        }

    }

    public function show($id)
    {
        $user = Auth::user();
        $query = Citizen::query();
        $query->where(['id' => $id]);
//            ->with('region:id, name_cyrl')
//            ->with('district:id, name_cyrl');

        if (empty($query->first())){
            return response()->errorJson('Бундай ид ли фойдаланувчи мавжуд емас', 409);
        }
        return $query->first();
    }

    public function update($request, $id){
        $msg = "";
        $validator = $this->repository->toValidate($request->all());

        $citizen = $this->repository->update($request, $id);
        return ['status' => 200, 'citizen' => $citizen];
        $citizen = DB::table('citizens')->where(['id' => $id])->first();
    }
}
