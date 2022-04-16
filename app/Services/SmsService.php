<?php


namespace App\Services;


use App\Models\Citizen;
use App\Models\Application;
use App\Models\User;
use App\Models\PhoneCode;
use Dflydev\DotAccessData\Data;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class SmsService
{
    public function __construct()
    {
     $this->config  = [
            'login' =>  config('provider.sms_login'),
            'password' => config('provider.sms_password'),
            'host' => config('provider.sms_host_url')
        ];
    }

    public function sendSms($attributes)
    {
        if(!isset($attributes['phone_number'])) {
            return ['msg' => 'phone is required', 'status' => 422];
        }

        $code = rand(11111, 99999);
        PhoneCode::updateOrCreate(['phone_number' => $attributes['phone_number']],['code' => $code]);
        $message = 'Yoshlar daftari (yoshlardaftari.uz) axborot tizimi uchun kod: '.$code .'. Yangiliklardan xabardor bo\'lish uchun telegram kanalimizga obuna bo\'ling: https://t.me/yoshlaragentligi';
        $this->send($attributes['phone_number'], $message);
        return ['msg'=>'Sms successfully sent!', 'status'=> 200];

    }

    public function sendSmsApplicant($application)
    {
        $application->user_city = $application->user_city;
        $application->user_city_sector = $application->user_city_sector;

        $message = "Arizangiz ro'yxatga olindi. Ariza holatini tekshirish uchun ID-raqam: " . $application->number .
            "  Kod: " . $application->code . ". Murojaat uchun:  ". $application->city->name_uz ."  sektor kotibi: ". $application->user_city_sector->phone." ". $application->city->name_uz . "  Agentlik rahbari : ".$application->user_city->phone;

        if($application->status == 1){
            $message = 'Siz "Yoshlar daftari"ga mufaqqiyatli  kiritildingiz. Batafsil: yoshlardaftari.uz. ';
        }

        if($application->status == 2){
            $reason = $application->deny_reason_id ? ($application->applicationDenyReason->name .'. '. $application->deny_reason) : $application->deny_reason;
            $message = '"Yoshlar daftari"ga kirish uchun arizangiz bekor qilindi. Rad etish sababi: '. $application->deny_reason;
        }

        $this->send($application->phone, $message);

    }


    public function sendSmsTransferCitizen($transfer)
    {

        $message = self::transliterate($transfer->from_makhalla->name)." tomonida sizni " . self::transliterate($transfer->to_makhalla->name) .
            " ga ko'chirish uchun  ".date('d.m.Y', strtotime($transfer->created_at)) ." kuni ariza yuborildi";

        if($transfer->confirmed == 2){
            $message = self::transliterate($transfer->from_makhalla->name)." tomonida sizni ko'chirishga yuborilgan ariza " . self::transliterate($transfer->to_makhalla->name) .
                "  tomonidan   ".date('d.m.Y', strtotime($transfer->updated_at)) ." kuni rad etildi.";
        }


        $this->send($transfer->citizen->phone, $message);

    }


    public static function transliterate($textcyr = null, $textlat = null) {
        $cyr = array(
            'ё',  'ж',  'х',  'ц',  'ч',  'щ',   'ш',  'ъ',  'э',  'ю',  'я',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ь','қ','ҳ','ў','ғ',
            'Ё',  'Ж',  'Х',  'Ц',  'Ч',  'Щ',   'Ш',  'Ъ',  'Э',  'Ю',  'Я',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ь','Қ','Ҳ','Ў','Ғ');
        $lat = array(
            'yo', 'j', 'x', 'ts', 'ch', 'sh', 'sh', '\'', 'e', 'yu', 'ya', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', '\'','q','h','o\'','g\'',
            'Yo', 'J', 'X', 'Ts', 'Ch', 'Sh', 'Sh', '\'', 'E', 'Yu', 'Ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', '\'','Q','H','O\'','G\'');
        if($textcyr)
            return str_replace($cyr, $lat, $textcyr);
        else if($textlat)
            return str_replace($lat, $cyr, $textlat);
        else
            return null;
    }

    public function sendSmsStudentSupport($student_support)
    {
        $student_support->user_city = $student_support->user_city;
        $student_support->user_city_sector = $student_support->user_city_sector;

        $message = "Sizning “Talabaga madad” loyihasi uchun arizangiz qabul qilindi.. Murojaat uchun:  ". $student_support->city->name_uz ."  sektor kotibi: ". $student_support->user_city_sector->phone." ". $student_support->city->name_uz . "  agentlik rahbari : ".$student_support->user_city->phone;


        $this->send($student_support->phone, $message);

    }

    public function sendSmsOutCitizen($survey)
    {

        $message = 'Siz "Yoshlar daftari"dan chiqarildingiz. Chiqarish sababi: '.$survey->citizenHistory->out_reason->name_uz. '. Yangiliklardan xabardor bo\'lish uchun telegram kanalimizga obuna bo\'ling: https://t.me/yoshlaragentligi';


        $this->send($survey->phone, $message);

    }

    public function sendInSmsComplaint($complaint)
    {
        $citizen = Citizen::wherePin($complaint->pin)->first();
        $user =User::query()->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->where('role_user.role_id', 3)->where('city_id', $citizen->city_id)->select('id', 'phone', 'fullname')->first();

        $message = 'Sizning shikoyatingiz qabul qilindi. Mas\'ul shaxs-agentlik rahbari: '.$user->phone. ' . Yangiliklardan xabardor bo\'lish uchun telegram kanalimizga obuna bo\'ling: https://t.me/yoshlaragentligi';

        $this->send($citizen->phone, $message);

    }

    public function sendOutSmsComplaint($complaint)
    {
        $citizen = Citizen::wherePin($complaint->pin)->first();

        $message = 'Sizning shikoyatingizga javob berildi. Shikoyat mazmuni: '.$complaint->complaintType->name.'. To\'liq ma\'lumot uchun shaxsiy profilning "shikoyatlar" bo\'limiga kirib ko\'rishingiz  mumkin.';

        $this->send($citizen->phone, $message);

    }

    public function confirmSms($attibutes)
    {
        $validator = Validator::make($attibutes, [
            'phone_number' => 'required',
            'code' => 'required|max:5'
        ]);
        if ($validator->fails()) {
            return ['msg'=>'Validation fails', 'status'=>422, 'error'=> $validator->errors()];
        }

        $phone_code = PhoneCode::where(['phone_number' => $attibutes['phone_number'], 'code' => $attibutes['code']])->first();
        if($phone_code) {
            $phone_code->delete();
            return ['msg'=>'Success!, Code match', 'status', 'status' => 200];
        } else {
            return ['msg'=>'Code does not match', 'status'=>422];
        }
    }

    public function send($phone, $message)
    {
//        dd('998'.$phone);
        $sendable_message = [
            'version' => '1.0',
            'id' => 123232,
            'method' => 'opersms.send',
            'client_secret' => '7mbGEJxOF3khqDCg',
            'params' =>
                [
                    'phone' => '998'.$phone,
                    'message' => $message
                ]
        ];
        $client = new Client(['verify' => false]);
        $response = $client->post('https://sms.mehnat.uz/serve', [
            'json' => $sendable_message
        ]);

        if($response->getStatusCode() == 200){
            return true;
        } else{
            return false;
        }
    }

    public function goToValidateSendSms($array)
    {
        $rules = [
            'phone_number' => 'required'
        ];

        if(!$authUser = auth()->user()){
            $rules = [
                'phone_number' => 'required',
            ];
        }

        $validator = Validator::make($array, $rules);
        return $validator;
    }

    public function sendSmsMessageOperSms($phone, $message)
    {

        $data[] = ['phone' => $phone, 'text' => $message];
        $details = $this->sendOperSms($data);
        return $details;
    }


    public function sendOperSms($chunk = [])
    {

        $ch = curl_init($this->config['host']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "login=" . $this->config['login'] . "&password=" . $this->config['password'] . "&data=" . json_encode($chunk));

        $result = curl_exec($ch);

        if ($result)
            return json_decode($result, true);

        return curl_error($ch);
    }

    public function isPhoneUnique($attributes)
    {
        dd($attributes);
        $survey = Survey::query()->where('phone', $attributes['phone']);
        $application = Application::query()->where('phone_number', $attributes['phone_number']);
        $user = User::query()->where('phone_number', $attributes['phone_number']);

        if($authUser = auth()->user()){
           $survey = null;
           $application = null;

           if(isset($attributes['is_citizen'])){
               $survey = Survey::query()->where('phone', $attributes['phone']);
               $application = Application::query()->where('phone', $attributes['phone']);
               $survey = $survey->whereHas('citizen', function ($q) use ($attributes) {
                   $q->where('passport', '!=', $attributes['passport']);
               })->first();
               $application = $application->where('passport', '!=', $attributes['passport'])->first();
           }

           $user = $user->where('tin', '!=', $authUser->tin)->first();
        } else {
           $survey = $survey->whereHas('citizen', function ($q) use ($attributes) {
               $q->where('passport', '!=', $attributes['passport']);
           })->first();
           $application = $application->where('passport', '!=', $attributes['passport'])->first();
           $user = $user->first();
        }

        return !($survey or $application or $user);
    }
}
