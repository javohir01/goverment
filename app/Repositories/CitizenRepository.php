<?php


namespace App\Repositories;


use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Citizen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CitizenRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Citizen;
    }

    public function getQuery()
    {
        return $this->model->query();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }



    public function store($request)
    {
        $user = Auth::user();
        $len = '00000';
        $number = '0';

        for ($int= 0; $int < strlen($len) - strlen($request->id) ; $int++){
            $number .= 0;
            return $number;
        }
        $number .= $request->id;

        $citizen = $this->model::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'm_name' => $request->m_name,
            'birth_date' => $request->birth_date,
            'region_id' => $user->region_id,
            'district_id' => $user->district_id,
            'social_id' => $request->social_id,
            'address' => $request->address,
            'passport' => $request->passport,
            'pin' => $request->pin,
            'number' => $number,
            'code' => mt_rand(1000000,9999999),
            'created_at' => Carbon::now()->format('Y-m-d'),
        ]);

        $data['citizen']=$citizen;
        return $data;
    }

    public function guard()
    {
        return Auth::guard();
    }

    public function update($request, $id)
    {
        $citizen  = Citizen::find($id);

        $user = $this->guard()->user();

        $citizen->update([
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

        $data['citizen']=$citizen;
        return $data;
    }

    public function toValidate($array, $status = null)
    {
        $rules = [
            'f_name' => 'required',
            'l_name' => 'required',
            'm_name' => 'required',
            'birth_date' => 'required',
            'region_id' => 'nullable',
            'district_id' => 'nullable',
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

    public function checkCitizen($passport, $id = NULL)
    {
        $passport = str_replace(' ', '', $passport);
        $citizen = $this->getQuery()->where([
            ['passport', $passport]
        ]);

        if ($id) {
            $citizen->where('id', '!=', $id);
        }

        $citizen = $citizen->first();
        return $citizen ? true : false;
    }

//    public function citizenOutOfReport($request)
//    {
//        $citizen = Citizen::find($request->citizen_id);
//
//        if(isset($request->reason) && $request->reason == 1) {
//            $land = SeparatedLand::updateOrCreate(
//                [
//                    'citizen_id' => $request->citizen_id
//                ],
//                [
//                    'area' => $request->area,
//                    'date_start' => $request->date_start,
//                    'decision' => $request->decision,
//                    'x' => $request->x,
//                    'y' => $request->y
//                ]
//            );
////            $citizen->citizen_status_id = Citizen::CITIZEN_STATUSES['separatedLand'];
//            $data['land'] = $land;
//        }
//
//        if(isset($request->reason) && $request->reason == 2) {
//            $credit = SeparatedCredit::updateOrCreate(
//                [
//                    'citizen_id' => $request->citizen_id
//                ],
//                [
//                    'bank_id' => $request->bank_id,
//                    'amount' => $request->amount,
//                    'given_date' => $request->given_date,
//                    'credit_id' => $request->credit_id,
//                    'contract' => $request->contract
//                ]);
////            $citizen->citizen_status_id = Citizen::CITIZEN_STATUSES['credited'];
//
//            $data['credit'] = $credit;
//
//        }
//
//        if(isset($request->reason) && $request->reason == 3) {
//            $employment = Employment::updateOrCreate(
//                [
//                    'citizen_id' => $request->citizen_id
//                ],
//                [
//                    'company_tin' => $request->company_tin,
//                    'company_name' => $request->company_name,
//                    'order_date' => $request->order_date,
//                    'order_number' => $request->order_number
//                ]);
////            $citizen->citizen_status_id = Citizen::CITIZEN_STATUSES['employment'];
//
//            $data['employment'] = $employment;
//
//        }
//
//
//
//        $citizen->out_date = date('Y-m-d');
//        $citizen->status = 2;
//        $citizen->save();
//        return $data;
//    }
//
//
//    public function transformPassportDetails($passport)
//    {
//        $tin = $this->getTin($passport);
//        $pin = $this->getPin($tin);
//        $ips_citizen = $this->getLatinData($passport, $pin);
//        $soliq_citizen = $this->soliqData($passport);
//
//        return [
//            'passport' => $passport,
//            'f_name' => $ips_citizen['result']['name_latin'],
//            's_name' => $ips_citizen['result']['surname_latin'],
//            'm_name' => $ips_citizen['result']['patronym_latin'],
//            'gender' => $soliq_citizen['result']['gender'],
//            'address' => $soliq_citizen['result']['address'],
//            'birth_date' => implode("-", array_reverse(explode(".", $soliq_citizen['result']['date_birth']))),
//            'tin' => $tin,
//            'user_id' => auth()->id()
//        ];
//    }
//
//    public function createCitizenByPassport($passport)
//    {
//        $citizen = Citizen::create($this->transformPassportDetails($passport));
//        return $citizen;
//    }
//
//    public function getDoc($tin)
//    {
//        $doc_num = null;
//        $data_by_inn = [
//            'version' => '1.0',
//            'id' => 7436,
//            'method' => 'ips.person.tin',
//            'params' => ['tin' => $tin]
//        ];
//        $client = new Client();
//        $response_by_passport = $client->post('http://resource.mehnat.uz/services', [
//            'json' => $data_by_inn
//        ]);
//        $citizen_by_doc = json_decode((string)$response_by_passport->getBody(), true);
////        if($citizen_by_doc['error'] == null) {
////            $doc_num = $citizen_by_doc['result'];
////        }
//        if (is_array($citizen_by_doc['result'])) {
//            if (array_key_exists('doc_num', $citizen_by_doc['result']) && (count($citizen_by_doc['result']['doc_num']) > 1)) {
//                $doc_num = $citizen_by_doc['result']['doc_num'][1];
//            }
//        }
//        return $doc_num;
//    }
//
//    public function guard()
//    {
//        return Auth::guard();
//    }
//
//
//
//
//    public function checkCitizen($passport, $id = NULL)
//    {
//        $passport = str_replace(' ', '', $passport);
//        $citizen = $this->getQuery()->where([
//            ['passport', $passport]
//        ]);
//
//        if ($id) {
//            $citizen->where('id', '!=', $id);
//        }
//
//        $citizen = $citizen->first();
//        return $citizen ? true : false;
//    }
//
//
//    public function updateOrCreate($attributes)
//    {
//        return Citizen::updateOrCreate(
//            [
//                'pin' => $attributes['pin']
//            ],
//            [
//                'passport' => $attributes['passport'],
//                'l_name' => $attributes['l_ame'],
//                'f_name' => $attributes['f_name'],
//                'patronymic' => $attributes['patronymic'],
//                'birth_date' => $attributes['birth_date'],
//                'gender' => $attributes['gender'],
//                'living_place' => $attributes['living_place'],
//                'age' => $attributes['age'],
//                'region_id' => $attributes['region_id'],
//                'city_id' => $attributes['city_id'],
//                'city_sector' => $attributes['city_sector'],
//                'makhalla_id' => $attributes['makhalla_id'],
//            ]
//        );
//    }
//
//    public function getTin($passport)
//    {
//        $data_by_passport = [
//            'version' => '1.0',
//            'id' => 123232,
//            'method' => 'soliq.person.passport',
//            'params' => ['passport' => $passport]
//        ];
//        $client = new Client();
//        $response_by_passport = $client->post(self::RESOURCE_URL, [
//            'json' => $data_by_passport
//        ]);
//        $citizen_by_passport = json_decode((string)$response_by_passport->getBody(), true);
//        $tin = $citizen_by_passport['result']['tin'];
//
//        return $tin;
//    }
//
//    public function getIpsTin($passport)
//    {
//        $data_by_passport = [
//            'version' => '1.0',
//            'id' => 123232,
//            'method' => 'ips.person.passport',
//            'params' => ['passport' => $passport]
//        ];
//        $client = new Client();
//        $response_by_passport = $client->post(self::RESOURCE_URL, [
//            'json' => $data_by_passport
//        ]);
//        $citizen_by_passport = json_decode((string)$response_by_passport->getBody(), true);
//        $tin = $citizen_by_passport['result']['tin'];
//
//        return $tin;
//    }
//
//    public function getPin($tin)
//    {
//        $pin = null;
//        $data_by_inn = [
//            'version' => '1.0',
//            'id' => 7436,
//            'method' => 'ips.person.tin',
//            'params' => ['tin' => $tin]
//        ];
//        $client = new Client();
//
//        try {
//            $response_by_passport = $client->post(self::RESOURCE_URL, [
//                'json' => $data_by_inn
//            ]);
//        } catch (Exception $e) {
//            return $e->getMessage();
//        }
//
//        $citizen_by_tin = json_decode((string)$response_by_passport->getBody(), true);
//        if (is_array($citizen_by_tin['result'])) {
//            if (array_key_exists('pin', $citizen_by_tin['result']) && (count($citizen_by_tin['result']['pin']) > 1)) {
//                $pin = $citizen_by_tin['result']['pin'][1];
//            }
//        }
//
//        return $pin;
//    }
//
//    public function getLatinData($passport, $pin)
//    {
//        $data_by_pin = [
//            'version' => '1.0',
//            'id' => 7436,
//            'method' => 'ips.person',
//            'params' => [
//                'passport' => $passport,
//                'pin' => $pin
//            ]
//        ];
//        $client = new Client();
//        $response_by_passport = $client->post(self::RESOURCE_URL, [
//            'json' => $data_by_pin
//        ]);
//        $result = json_decode((string)$response_by_passport->getBody(), true);
//
//        return $result;
//    }
//
//    public function soliqData($passport)
//    {
//        $data_by_passport_soliq = [
//            'version' => '1.0',
//            'id' => 7436,
//            'method' => 'soliq.person.passport',
//            'params' => [
//                'passport' => $passport
//            ]
//        ];
//        $client = new Client();
//        $response_by_passport = $client->post(self::RESOURCE_URL, [
//            'json' => $data_by_passport_soliq
//        ]);
//        $result = json_decode((string)$response_by_passport->getBody(), true);
//        return $result;
//    }
//
//    public function getPassportData($passport)
//    {
//        $client = new Client();
//        $data = [
//            'version' => '1.0',
//            'id' => rand(0, 999999),
//            'method' => 'soliq.person.passport',
//            'params' => ['passport' => $passport]
//        ];
//        $response = $client->post(self::RESOURCE_URL, [
//            'json' => $data
//        ]);
//
//        return json_decode((string)$response->getBody(), true);
//    }
//
//    public function getMvdPassportData($passport, $birth_date)
//    {
//        $client = new Client();
//        $data = [
//            'version' => '2.0',
//            'id' => rand(0, 999999),
//            'method' => 'mvd.person.lastdata',
//            'params' => ['passport' => $passport, 'birth_date' => $birth_date]
//        ];
//        $response = $client->post(self::RESOURCE_URL_MVD, [
//            'json' => $data
//        ]);
//
//        return json_decode((string)$response->getBody(), true);
//    }
//
//
//
////    public function createCitizenByPassport($passport)
////    {
////        $citizen = Citizen::create($this->transformPassportDetails($passport));
////        return $citizen;
////    }
////
////    public function getDoc($tin)
////    {
////        $doc_num = null;
////        $data_by_inn = [
////            'version' => '1.0',
////            'id' => 7436,
////            'method' => 'ips.person.tin',
////            'params' => ['tin' => $tin]
////        ];
////        $client = new Client();
////        $response_by_passport = $client->post('http://resource.mehnat.uz/services', [
////            'json' => $data_by_inn
////        ]);
////        $citizen_by_doc = json_decode((string)$response_by_passport->getBody(), true);
//////        if($citizen_by_doc['error'] == null) {
//////            $doc_num = $citizen_by_doc['result'];
//////        }
////        if (is_array($citizen_by_doc['result'])) {
////            if (array_key_exists('doc_num', $citizen_by_doc['result']) && (count($citizen_by_doc['result']['doc_num']) > 1)) {
////                $doc_num = $citizen_by_doc['result']['doc_num'][1];
////            }
////        }
////
////        return $doc_num;
////
////    }
//
//    public function getIpsPerson($passport)
//    {
//        $citizen = null;
//        $tin = $this->getIpsTin($passport);
//        $pin = '';
//        if ($tin) {
//            $pin = $this->getPin($tin);
//            if ($pin) {
//                $citizen = $this->getLatinData($passport, $pin);
//            }
//        }
//
//        $citizen['result'] = array_merge($citizen['result'], ['pin' => $pin]);
//
//        return $citizen;
//    }
}
