<?php

namespace App\Http\Controllers\Api;

use App\Application;
use App\PhoneCode;
use App\Services\SmsService;
use App\Survey;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new SmsService();

    }

    public function sendSms(Request $request)
    {
//        dd($request->all());
        $validator = $this->service->goToValidateSendSms($request->all());
        if (!$validator->fails()) {
//            if (!$this->service->isPhoneUnique($request->all())) {
//                return response()->errorJson('Tizimda ushbu telefon raqam avval ishlatilgan, iltimos boshqa raqam kiriting!', 409);
//            }

            $result = $this->service->sendSms($request->all());
        } else {
            return response()->errorJson('Validation error', 400, $validator->failed());
        }
        if ($result['status'] == 422) {
            return response()->errorJson($result['msg'], $result['status']);
        }

        if ($result['status'] == 409) {
            return response()->errorJson($result['msg'], $result['status']);
        }
        if ($result['status'] == 200) {
            return response()->successJson($result['msg']);
        }
        return response()->successJson('Sms successfully sent!');

    }

    public function sendSmsCurl(Request $request)
    {
        $validator = $this->service->goToValidateSendSms($request->all());
        if (!$validator->fails()) {

            if (!$this->service->isPhoneUnique($request->all())) {
                return response()->errorJson('Tizimda ushbu telefon raqam avval ishlatilgan, iltimos boshqa raqam kiriting!', 409);
            }

            $result = $this->service->sendSmsCurl($request->all());
        } else {
            return response()->errorJson('Validation error', 400, $validator->failed());
        }
        if ($result['status'] == 422) {
            return response()->errorJson($result['msg'], $result['status']);
        }

        if ($result['status'] == 409) {
            return response()->errorJson($result['msg'], $result['status']);
        }
        if ($result['status'] == 200) {
            return response()->successJson($result['msg']);
        }
        return response()->successJson('Sms successfully sent!');

    }

    public function sendSmsApplicant($application)
    {
        $this->service->sendSmsApplicant($application);

    }

    public function sendSmsTransferCitizen($transfer)
    {
        $this->service->sendSmsTransferCitizen($transfer);

    }

    public function sendSmsStudentSupport($student_support)
    {
        $this->service->sendSmsStudentSupport($student_support);

    }

    public function confirmSms(Request $request)
    {
//       dd($request->all());
        $result = $this->service->confirmSms($request->all());
//dd($result['msg']);
        if ($result['msg'] === 'Validation fails') {
            return response()->errorJson($result['msg'], $result['status'], $result['error']);
        }

        if ($result['msg'] === 'Success!, Code match') {
            return response()->successJson($result['msg']);
        }

        if ($result['msg'] === 'Code does not match') {
            return response()->errorJson($result['msg'], $result['status']);
        }
    }

    public function send($phone, $message)
    {
        $this->service->send($phone, $message);
    }
}
