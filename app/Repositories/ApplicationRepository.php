<?php

namespace App\Repositories;

use App\Models\Application;
use App\Models\Citizen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplicationRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Application;
    }

    public function getQuery()
    {
        return $this->model->query();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @return string
     */
    public function getNumber($request)
    {
        $len = '00000';
        $number = '0';

        for ($int= 0; $int < strlen($len) - strlen($request->id) ; $int++){
            $number .= 0;
        }
        $number .= $request->id;
        return $number;

    }

    public function store($request)
    {

//        dd($request->id);
        $Application = $this->model::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'm_name' => $request->m_name,
            'birth_date' => $request->birth_date,
            'phone_number' => $request->phone_number,
            'region_id' => $request->region_id,
            'district_id' => $request->district_id,
            'social_id' => $request->social_id,
            'address' => $request->address,
            'passport' => $request->passport,
            'pin' => $request->pin,
            'status' => 0,
            'number' => '0',
            'code' => mt_rand(10000,99999),
            'created_at' => Carbon::now()->format('Y-m-d')
        ]);
//        dd($Application);
        $Application->update([
            'number' => str_pad($Application->id,6,"0",STR_PAD_LEFT),
        ]);


        $data['Application']=$Application;
//        dd($data);
        return $data;
    }

    public function guard()
    {
        return Auth::guard();
    }

    public function update($request, $id)
    {
        $Application  = Application::find($id);

        $user = $this->guard()->user();

        $Application->update([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'm_name' => $request->m_name,
            'birth_date' => $request->birth_date,
            'region_id' => $user->region_id,
            'district_id' => $user->district_id,
            'address' => $request->address,
            'passport' => $request->passport,
            'pin' => $request->pin,
            'created_at' => Carbon::now()->format('Y-m-d'),
        ]);

        $data['Application']=$Application;
        return $data;
    }

    public function toValidate($array, $status = null)
    {
        $rules = [
            'f_name' => 'required',
            'l_name' => 'required',
            'm_name' => 'required',
            'birth_date' => 'required',
            'region_id' => 'required',
            'district_id' => 'required',
            'social_id' => 'required',
            'address' => 'required',
            'passport' => 'required',
            'pin' => 'required',
            'remember_token' => 'nullable',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',

        ];
        $validator = Validator::make($array, $rules);
        return $validator;
    }

    public function checkApplication($passport, $id = NULL)
    {
        $passport = str_replace(' ', '', $passport);
        $Application = $this->getQuery()->where([
            ['passport', $passport]
        ]);

        if ($id) {
            $Application->where('id', '!=', $id);
        }

        $Application = $Application->first();
        return $Application ? true : false;
    }
    public function rejected($request)
    {
        $Application  = Application::find($request->id);
        return $Application->update([
            'status' => 2,
            'updated_at' => Now(),
        ]);

        $data['Application'] = $Application;
        return $data;
    }

    public function confirmed($request)
    {
        $Application  = Application::find($request->id);

        $citizen = Citizen::create([
            'f_name' => $Application->f_name,
            'l_name' => $Application->l_name,
            'm_name' => $Application->m_name,
            'birth_date' => $Application->birth_date,
            'phone_number' => $Application->phone_number,
            'region_id' => $Application->region_id,
            'district_id' => $Application->district_id,
            'social_id' => $Application->social_id,
            'address' => $Application->address,
            'passport' => $Application->passport,
            'pin' => $Application->pin,
            'number' => str_pad($Application->id,6,"0",STR_PAD_LEFT),
            'application_id' => $request->id,
            'code' => mt_rand(10000,99999),
            'created_at' => Carbon::now()->format('Y-m-d')
        ]);

        $Application->update([
            'status' => 1,
            'updated_at' => Now(),
        ]);
//        dd($citizen);

        $data = [
            'Application' => $Application,
            'citizen' => $citizen,
        ];
        return $data;
    }

    public function check($request)
    {
        $Application  = Application::find($request->id);
//        return $Application->update([
//            'status' => 2,
//            'updated_at' => Now(),
//        ]);
        $application = DB::table('applications')->where('applications.number' ,$request->number)->get();

//        $Application = Application::where([
//            ['number', $request->number]
//        ]);
//        dd($application);

        $application->where('code', '!=', $request->code);


        $application = $application->first();
        return $application ? true : false;
    }
}
