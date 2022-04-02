<?php


namespace App\Repositories;


use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Citizen;
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
        $user = $this->guard()->user();

        $citizen = $this->model::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'fathers_name' => $request->fathers_name,
            'birth_date' => $request->birth_date,
            'region_id' => $request->region_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'passport' => $request->passport,
            'tin' => $request->tin,
            'created_at' => Carbon::now()->format('Y-m-d'),
        ]);


        $data['citizen']=$citizen;
        return $data;
    }
    public function citizenOutOfReport($request)
    {
        $citizen = Citizen::find($request->citizen_id);

        if(isset($request->reason) && $request->reason == 1) {
            $land = SeparatedLand::updateOrCreate(
                [
                    'citizen_id' => $request->citizen_id
                ],
                [
                    'area' => $request->area,
                    'date_start' => $request->date_start,
                    'decision' => $request->decision,
                    'x' => $request->x,
                    'y' => $request->y
                ]
            );
            $citizen->citizen_status_id = Citizen::CITIZEN_STATUSES['separatedLand'];
            $data['land'] = $land;
        }

        if(isset($request->reason) && $request->reason == 2) {
            $credit = SeparatedCredit::updateOrCreate(
                [
                    'citizen_id' => $request->citizen_id
                ],
                [
                    'bank_id' => $request->bank_id,
                    'amount' => $request->amount,
                    'given_date' => $request->given_date,
                    'credit_id' => $request->credit_id,
                    'contract' => $request->contract
                ]);
            $citizen->citizen_status_id = Citizen::CITIZEN_STATUSES['credited'];

            $data['credit'] = $credit;

        }

        if(isset($request->reason) && $request->reason == 3) {
            $employment = Employment::updateOrCreate(
                [
                    'citizen_id' => $request->citizen_id
                ],
                [
                    'company_tin' => $request->company_tin,
                    'company_name' => $request->company_name,
                    'order_date' => $request->order_date,
                    'order_number' => $request->order_number
                ]);
            $citizen->citizen_status_id = Citizen::CITIZEN_STATUSES['employment'];

            $data['employment'] = $employment;

        }



        $citizen->out_date = date('Y-m-d');
        $citizen->status = 2;
        $citizen->save();
        return $data;
    }
    public function update($request, $id)
    {
        $citizen = $this->getById($id);

        dd($citizen);

        return $user->is_read;
        $data['citizen']=$citizen;

        return $data;
    }

    public function transformPassportDetails($passport)
    {
        $tin = $this->getTin($passport);
        $pin = $this->getPin($tin);
        $ips_citizen = $this->getLatinData($passport, $pin);
        $soliq_citizen = $this->soliqData($passport);

        return [
            'passport' => $passport,
            'f_name' => $ips_citizen['result']['name_latin'],
            's_name' => $ips_citizen['result']['surname_latin'],
            'm_name' => $ips_citizen['result']['patronym_latin'],
            'gender' => $soliq_citizen['result']['gender'],
            'address' => $soliq_citizen['result']['address'],
            'birth_date' => implode("-", array_reverse(explode(".", $soliq_citizen['result']['date_birth']))),
            'tin' => $tin,
            'user_id' => auth()->id()
        ];
    }

    public function createCitizenByPassport($passport)
    {
        $citizen = Citizen::create($this->transformPassportDetails($passport));
        return $citizen;
    }

    public function getDoc($tin)
    {
        $doc_num = null;
        $data_by_inn = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'ips.person.tin',
            'params' => ['tin' => $tin]
        ];
        $client = new Client();
        $response_by_passport = $client->post('http://resource.mehnat.uz/services', [
            'json' => $data_by_inn
        ]);
        $citizen_by_doc = json_decode((string)$response_by_passport->getBody(), true);
//        if($citizen_by_doc['error'] == null) {
//            $doc_num = $citizen_by_doc['result'];
//        }
        if (is_array($citizen_by_doc['result'])) {
            if (array_key_exists('doc_num', $citizen_by_doc['result']) && (count($citizen_by_doc['result']['doc_num']) > 1)) {
                $doc_num = $citizen_by_doc['result']['doc_num'][1];
            }
        }
        return $doc_num;
    }

    public function guard()
    {
        return Auth::guard();
    }

    public function toValidate($array, $status = null)
    {
        $rules = [
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'fathers_name' => 'nullable',
            'birth_date' => 'nullable',
            'region_id' => 'nullable',
            'city_id' => 'nullable',
            'address' => 'nullable',
            'passport' => 'nullable',
            'tin' => 'nullable',
            'remember_token' => 'nullable',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',

        ];
        $validator = Validator::make($array, $rules);
        return $validator;
    }

    public function goToValidate($array)
    {

        if(isset($array['reason']) && $array['reason'] == 1) {
            $rules = [
                'citizen_id'=> 'required',
                'area' => 'required',
                'date_start' => 'required',
                'decision' => 'required',
                'x' => 'required',
                'y' => 'required'
            ];

        }

        if(isset($array['reason']) && $array['reason'] == 2) {
            $rules = [
                'citizen_id'=> 'required',
                'bank_id' => 'required',
                'amount' => 'required',
                'given_date' => 'required',
                'contract' => 'required',
                'credit_id' => 'required'
            ];

        }

        if(isset($array['reason']) && $array['reason'] == 3) {
            $rules = [
                'citizen_id'=> 'required',
                'company_tin' => 'required|digits:9',
                'company_name' => 'required',
                'order_date' => 'required',
                'order_number' => 'required'
            ];

        }
        if (isset($array['reason']) && $array['reason'] == 4) {
            $rules = [
                'pin' => 'required',
                'survey_id' => 'required',
                'name' => 'required',
                'city_name' => 'required',
                'direction_id' => 'required',
                'period' => 'required',
                'supporting_document' => 'required'
            ];
        }

        if (isset($array['reason']) && $array['reason'] == 5) {
            $rules = [
                'pin' => 'required',
                'survey_id' => 'required',
                'source_id' => 'required',
                'goal_id' => 'required',
                'amount' => 'required',
                'given_date' => 'required',
                'supporting_document' => 'required'
            ];
        }

        if (isset($array['reason']) && $array['reason'] == 6) {
            $rules = [
                'pin' => 'required',
                'survey_id' => 'required',
                'source_id' => 'required',
                'goal_id' => 'required',
                'amount' => 'required',
                'given_date' => 'required',
                'supporting_document' => 'required'
            ];
        }

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

    public function all($attributes)
    {
        $query = Citizen::query()
            ->leftJoin('citizen_actions', 'citizens.pin', '=', 'citizen_actions.pin')
            ->filter($attributes);

        if ($attributes['is_employer'])
        {
            $query->where('citizen_actions.is_employer', '=', $attributes['is_employer']);
        }

        if ($attributes['citizen_status_id'])
        {
            $query->where('citizen_actions.citizen_status_ids', 'like', '%,'.$attributes['citizen_status_id'].',%');
        }

        if ($attributes['status'])
        {
            $query->where('citizen_actions.status', '=', $attributes['status']);
        }

        return $query->paginate($attributes['limit']);
    }

    public function updateOrCreate($attributes)
    {
        return Citizen::updateOrCreate(
            [
                'pin' => $attributes['pin']
            ],
            [
                'passport' => $attributes['passport'],
                'surname' => $attributes['surname'],
                'firstname' => $attributes['firstname'],
                'patronymic' => $attributes['patronymic'],
                'birth_date' => $attributes['birth_date'],
                'gender' => $attributes['gender'],
                'living_place' => $attributes['living_place'],
                'age' => $attributes['age'],
                'region_id' => $attributes['region_id'],
                'city_id' => $attributes['city_id'],
                'city_sector' => $attributes['city_sector'],
                'makhalla_id' => $attributes['makhalla_id'],
            ]
        );
    }
}
