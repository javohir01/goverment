<?php

namespace App\Repositories;

use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
            'number' => $this->getNumber($request),
            'code' => mt_rand(1000000,9999999),
            'created_at' => Carbon::now()->format('Y-m-d')
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
}
