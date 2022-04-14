<?php


namespace App\Repositories;

use App\ComputedReport;
use App\Models\Citizen;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceRepository
{
    const RESOURCE_URL = 'http://resource.mehnat.uz/services';
    const RESOURCE_URL_MVD = 'https://resource1.mehnat.uz/services';
    const RESOURCE_URL_MVD_OLD = 'http://resource.mehnat.uz/services';
    const GNK_API = 'https://gnk-api.mehnat.uz/api/v1/company/getCompanyByTin';
    const RELATIVES_URL = 'http://relatives-api.argos.local/api/relatives/index/';
//    const RESOURCE_URL = 'https://resource1.mehnat.uz/services';
    public function getTin($passport)
    {
        $data_by_passport = [
            'version' => '1.0',
            'id' => 123232,
            'method' => 'soliq.person.passport',
            'params' => ['passport' => $passport]
        ];
        $client = new Client(['verify' => false]);
        $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
            'json' => $data_by_passport
        ]);
        $citizen_by_passport = json_decode((string)$response_by_passport->getBody(), true);
        $tin = $citizen_by_passport['result']['tin'] ?? null;

        return $tin;
    }

    public function getFxdyoDeathPin($pin)
    {
        $data_by_passport = [
            'version' => '1.0',
            'id' => 123232,
            'method' => 'fxdyo.death.pin',
            'params' => ['pin' => $pin]
        ];
        $client = new Client(['verify' => false]);
        try {
            $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
                'json' => $data_by_passport
            ]);
            $citizen_by_passport = json_decode((string)$response_by_passport->getBody(), true);
            $tin = $citizen_by_passport['result'][0] ?? null;

        } catch (RequestException   $e) {
            return null;
        } catch (ConnectException    $e) {
            return null;
        }

        return $tin;
    }


    public function getTinFromIps($passport)
    {
        $data_by_passport = [
            'version' => '1.0',
            'id' => 123232,
            'method' => 'ips.person.passport',
            'params' => ['passport' => $passport]
        ];
        $client = new Client(['verify' => false]);
        $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
            'json' => $data_by_passport
        ]);
        $citizen_by_passport = json_decode((string)$response_by_passport->getBody(), true);
        $tin = $citizen_by_passport['result']['tin'];

        return $tin;
    }

    public function getPin($tin)
    {
        $pin = null;
        $data_by_inn = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'ips.person.tin',
            'params' => ['tin' => $tin]
        ];

        try {
            $client = new Client(['verify' => false]);
            $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
                'json' => $data_by_inn
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $guzzleResult = $e->getResponse();
        }

        if (isset($guzzleResult) && $guzzleResult->getStatusCode() == 500) {
            return null;
        }

        $citizen_by_tin = json_decode((string)$response_by_passport->getBody(), true);

        if (is_array($citizen_by_tin['result'])) {
            if (array_key_exists('pin', $citizen_by_tin['result']) && (count($citizen_by_tin['result']['pin']) > 1)) {
                $pin = $citizen_by_tin['result']['pin'][1];
                return $pin;
            }
        }

    }

    public function getRelatives($pin)
    {
        $client = new Client(['verify' => false]);

        $response = $client->get(self::RELATIVES_URL . $pin);

        $data = json_decode((string)$response->getBody(), true);

        return $data['data']['relatives'] ?? null;
    }

    public function getLatinData($passport, $pin)
    {
        $data_by_pin = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'ips.person',
            'params' => [
                'passport' => $passport,
                'pin' => $pin
            ]
        ];
        $client = new Client(['verify' => false]);
        $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
            'json' => $data_by_pin
        ]);
        $result = json_decode((string)$response_by_passport->getBody(), true);

        return $result;
    }

    public function soliqData($passport)
    {
        $data_by_passport_soliq = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'soliq.person.passport',
            'params' => [
                'passport' => $passport
            ]
        ];
        $client = new Client(['verify' => false]);
        $response_by_passport = $client->post(self::RESOURCE_URL, [
            'json' => $data_by_passport_soliq
        ]);
        $result = json_decode((string)$response_by_passport->getBody(), true);
        return $result;
    }

    public function getPassportData($passport)
    {
        $client = new Client(['verify' => false]);
        $data = [
            'version' => '1.0',
            'id' => rand(0, 999999),
            'method' => 'soliq.person.passport',
            'params' => ['passport' => $passport]
        ];
        $response = $client->post(self::RESOURCE_URL, [
            'json' => $data
        ]);

        return json_decode((string)$response->getBody(), true);
    }

    public function getMvdPassportData($passport, $birth_date)
    {
        $client = new Client(['verify' => false]);
        $data = [
            'version' => '2.0',
            'id' => rand(0, 999999),
            'method' => 'mvd.person.lastdata',
            'params' => ['passport' => $passport, 'birth_date' => $birth_date]
        ];
        try {
            $response = $client->post(self::RESOURCE_URL_MVD_OLD, [
                'json' => $data,
                'timeout' => 1200,
                'connect_timeout' => 3,
            ]);
            return json_decode((string)$response->getBody(), true);

        } catch (RequestException   $e) {
            return null;
        } catch (ConnectException    $e) {
            return null;
        }

    }

    public function getIpsPersonData($passport, $pin)
    {
//        dd($passport, $pin);
        $client = new Client();
        $data = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'ips.person',
            'params' => [
                'passport' => $passport,
                'pin' => $pin
            ]
        ];
//        dd($data);
        try {
        $response = $client->post(self::RESOURCE_URL_MVD, [
            'json' => $data
        ]);
//        dd($response);
        return json_decode((string)$response->getBody(), true);
        } catch (RequestException   $e) {
            return null;
        } catch (ConnectException    $e) {
            return null;
        }
    }

    public static function getPassportImage($passport, $pin)
    {
        $data_by_pin = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'ips.person.photo',
            'params' => [
                'pin' => $pin,
                'passport' => $passport

            ]
        ];

        try {
            $client = new Client(['verify' => false]);
            $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
                'json' => $data_by_pin
            ]);
            $result = json_decode((string)$response_by_passport->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $guzzleResult = $e->getResponse();
        }

        if (isset($guzzleResult) && ($guzzleResult->getStatusCode() == 500 || $guzzleResult->getStatusCode() == 503)) {
            return [
                'result' => null
            ];
        }
        return $result;
    }

    public function getSpecialEduCenters()
    {
        $apiBaseUrl = 'https://abkm.mehnat.uz/api';
        $url = '/edu-centers';
        $client = new Client([
            'verify' => false,
        ]);


        try {
            $response = $client->request('GET', $apiBaseUrl . $url, [
                'query' => [],
                'timeout' => 5,
                'timeout' => 200,
                'connect_timeout' => 1.5,
                'headers' => ['Accept-Encoding' => 'gzip'],
            ]);

            $code = $response->getStatusCode();
            $body = $response->getBody();

            if ($code == 200 || $code == 201) {
                $data = json_decode((string)$body);
                return $data ? $data->data->directions : null;
            }
        } catch (\Exception $e) {
            return response()->errorJson($e->getMessage(), 500);
        }
        return false;
    }

    public function checkInIronFamily($request)
    {
        $apiBaseUrl = 'https://saxovat.argos.uz/api/api/v1/';
        $url = '/check-family?passport=' . $request->passport;
        $client = new Client([
            'verify' => false,
        ]);


        try {
            $response = $client->request('GET', $apiBaseUrl . $url, [
            ]);

            $code = $response->getStatusCode();
            $body = $response->getBody();

            if ($code == 200 || $code == 201) {
                $data = json_decode((string)$body);
                return $data ? $data : null;
            }
        } catch (\Exception $e) {
            return response()->errorJson($e->getMessage(), 500);
        }
        return false;
    }

    public function getCompanyInfo(Request $request)
    {
        $inn = $request->inn;
        $organization = [];

        $data_by_passport_soliq = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'ips.company.tin',
            'params' => [
                'tin' => $inn
            ]
        ];

        $data_by_yatt = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'soliq.company.tin',
            'params' => [
                'tin' => $inn
            ]
        ];

        $client = new Client(['verify' => false]);
        try {
            $response = $client->post(self::RESOURCE_URL_MVD, ['json' => $data_by_passport_soliq]);
            $data = json_decode((string)$response->getBody(), true);
            if (!empty($data['result']['TIN'])) {
                $organization = $data['result'];
            }
        } catch (\Exception $exception) {

        }

        if (!isset($organization['TIN'])) {
            $yatt_data = [];
            try {
                $yatt_response = $client->post(self::RESOURCE_URL_MVD, ['json' => $data_by_yatt]);
                $yatt_data = json_decode((string)$yatt_response->getBody(), true);
            } catch (\Exception $exception) {

            }

            if (!empty($yatt_data['result']['tin_yatt'])) {
                $result = $yatt_data['result'];
                $organization = [
                    'TIN' => $result['tin_yatt'],
                    'ACRON_UZ' => $result['name_yatt'],
                    'OKED_DESC_UZ' => $result['name_faoliyat'],
                    'HEAD_NM' => $result['name_yatt'],
                    'LE_NM_UZ' => $result['name_yatt'],
                ];
            }
        }
        if (!isset($organization['TIN'])) {
            $response = $client->get(self::GNK_API, [
                'query' => ['tin' => $inn]
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            if (isset($data['data']) && isset($data['data']['TIN'])) {
                $organization = [
                    'TIN' => $data['data']['TIN'],
                    'ACRON_UZ' => $data['data']['ANAME'],
                    'HEAD_NM' => $data['data']['HEADER'],
                ];
            }
        }
        if (empty($yatt_data['result']['tin_yatt']) && empty($data['result']['TIN'])) {
            $data_by_yatt['method'] = 'soliq.company.all';
            try {
                $response = $client->post(self::RESOURCE_URL_MVD, ['json' => $data_by_yatt]);
                $data = json_decode((string)$response->getBody(), true);
            } catch (\Exception $exception) {

            }

            if (!empty($data['result']['TIN'])) {
                $organization = $data['result'];
            } else {
                $organization = [];
            }
        }

        return $organization;
    }

    public function getYattInfo($inn)
    {
        $yatt_data = [];
        $organization = [];
        $data_by_yatt = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'soliq.company.tin',
            'params' => [
                'tin' => $inn
            ]
        ];
        $client = new Client(['verify' => false]);
        try {
            $yatt_response = $client->post(self::RESOURCE_URL_MVD, ['json' => $data_by_yatt]);
            $yatt_data = json_decode((string)$yatt_response->getBody(), true);
        } catch (\Exception $exception) {

        }

        if (!empty($yatt_data['result']['tin_yatt'])) {
            $result = $yatt_data['result'];
            $organization = [
                'TIN' => $result['tin_yatt'],
                'ACRON_UZ' => $result['name_yatt'],
                'OKED_DESC_UZ' => $result['name_faoliyat'],
                'HEAD_NM' => $result['name_yatt'],
                'LE_NM_UZ' => $result['name_yatt'],
                'date_end' => Carbon::parse($result['dateend'])->format('Y-m-d'),
                'date_reg' => Carbon::parse($result['date_reg'])->format('Y-m-d')
            ];
        }
        return $organization;
    }

    public function getYattInfoNew($pin)
    {
        $yatt_data = [];
        $organization = [];

        $data_by_yatt = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'soliq.new.yatt',
            'params' => [
                'pin' => $pin
            ]
        ];
        $client = new Client(['verify' => false]);
        try {
            $yatt_response = $client->post(self::RESOURCE_URL_MVD, ['json' => $data_by_yatt]);
            $yatt_data = json_decode((string)$yatt_response->getBody(), true);
        } catch (\Exception $exception) {

        }

        if (!empty($yatt_data['result']['company']['pinfl'])) {
            $result = $yatt_data['result']['company'];
            if($citizen = Citizen::wherePin($pin)->first()){
                $fullname = $citizen->surname.' '.$citizen->firstname.' '.$citizen->patronymic;
            }
            $organization = [
                'TIN' => $result['tin'],
                'ACRON_UZ' => $fullname ?? NULL,
                'date_end' => Carbon::parse($result['licenseEndDate'])->format('Y-m-d'),
                'date_reg' => Carbon::parse($result['registrationDate'])->format('Y-m-d')
            ];
        }
        return $organization;
    }

    public function dataStatistics($city_id)
    {
        $type = \request('type', 'young');
        $dbRaw = DB::raw("
                    COUNT(citizens.*) as all_youngs,
                    SUM(CASE WHEN citizens.status = 1 and citizens.is_employer IS false and citizen_status_id is null THEN 1 ELSE 0 END) as all_count,
                    SUM(CASE WHEN citizens.is_employer IS false and citizen_status_id is null THEN 1 ELSE 0 END) AS youngs,
                    SUM(CASE WHEN citizens.citizen_status_id = 2 THEN 1 ELSE 0 END) AS university_graduates
                ");

        if ($type == 'young') {
            $whereRaw = "date_part('year', age(citizens.birth_date)) BETWEEN 16 AND 30";
        }

        if ($type == 'woman') {
            $whereRaw = "date_part('year', age(birth_date)) BETWEEN 18 AND 55";
        }

        $result = DB::table('citizens')->select($dbRaw)->where('city_id', $city_id)->whereRaw($whereRaw);
        if ($type == 'woman') {
            $result = $result->whereGender(2);
        }
        return $result->get();
    }

    public function regionStatistics()
    {
        $report = ComputedReport::where('type', 1)->orderBy('id', 'desc')->first()->region;
        return json_decode($report);
    }

    public function getFulldata($pin)
    {
        $data = [];
        $citizen = [
            'birth_date' => '',
            'position' => '',
            'gender' => '',
            'f_name' => '',
            's_name' => '',
            'm_name' => ''
        ];
        $url = config('provider.newapi_url') . 'mediate/get-citizen-with-position/' . $pin;

        $client = new Client(['verify' => false]);
        $response_by_pin = $client->request("GET", $url);
        // $response_by_pin = $client->get($url);

        $response_elc = json_decode((string)$response_by_pin->getBody(), true);
        if ($response_elc['data'] != null) {
            $res = $response_elc['data'];
            $citizen['position'] = $res['position_name'] ?? '';
            $citizen['f_name'] = $res['person_name'];
            $citizen['s_name'] = $res['person_surname'];
            $citizen['m_name'] = $res['person_partonymic'];
            $citizen['gender'] = $res['person_sex'] ? 1 : 2;
            $citizen['work_place'] = $res['company_name'] ?? null;
            $citizen['position_date'] = $res['date_start'] ?? null;
            $citizen['company_tin'] = $res['company_tin'] ?? null;
            $citizen['tin'] = $res['person_tin'] ?? null;
            $citizen['order_date'] = $res['contract_date'] ?? null;
            $citizen['order_number'] = $res['contract_number'] ?? null;
            $citizen['rate'] = $res['contract_rate'] ?? null;
            $data['result'] = $citizen;
        }
        return $data;
    }

    public static function getFxdyoBirthPerson($tin, $pin)
    {
        $data_by_pin = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'fxdyo.birth.person',
            'params' => [
                'pin' => $pin,
                'tin' => $tin

            ]
        ];

        try {
            $client = new Client(['verify' => false]);
            $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
                'json' => $data_by_pin
            ]);
            $result = json_decode((string)$response_by_passport->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $guzzleResult = $e->getResponse();
        }

        if (isset($guzzleResult) && ($guzzleResult->getStatusCode() == 500 || $guzzleResult->getStatusCode() == 503)) {
            return [
                'result' => null
            ];
        }
        return $result;
    }

    public static function getFxdyoMarriagePerson($tin, $pin)
    {
        $data_by_pin = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'fxdyo.marriage.person',
            'params' => [
                'pin' => $pin,
                'tin' => $tin

            ]
        ];

        try {
            $client = new Client(['verify' => false]);
            $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
                'json' => $data_by_pin
            ]);
            $result = json_decode((string)$response_by_passport->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $guzzleResult = $e->getResponse();
        }

        if (isset($guzzleResult) && ($guzzleResult->getStatusCode() == 500 || $guzzleResult->getStatusCode() == 503)) {
            return [
                'result' => null
            ];
        }
        return $result;
    }

    public static function getFxdyoDeathPerson($pin)
    {
        $data_by_pin = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'fxdyo.death.pin',
            'params' => [
                'pin' => $pin,

            ]
        ];

        try {
            $client = new Client(['verify' => false]);
            $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
                'json' => $data_by_pin
            ]);
            $result = json_decode((string)$response_by_passport->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $guzzleResult = $e->getResponse();
        }

        if (isset($guzzleResult) && ($guzzleResult->getStatusCode() == 500 || $guzzleResult->getStatusCode() == 503)) {
            return [
                'result' => null
            ];
        }
        return $result;
    }

    public static function getMinfinSocialProtectionPin($pin)
    {
        $data_by_pin = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'minfin.socialProtection.pin',
            'params' => [
                'pin' => $pin

            ]
        ];

        try {
            $client = new Client(['verify' => false]);
            $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
                'json' => $data_by_pin
            ]);
            $result = json_decode((string)$response_by_passport->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $guzzleResult = $e->getResponse();
        }

        if (isset($guzzleResult) && ($guzzleResult->getStatusCode() == 500 || $guzzleResult->getStatusCode() == 503)) {
            return [
                'result' => null
            ];
        }
        return $result;
    }

    public static function getMvdConvictCitizen($pin)
    {
        $data_by_pin = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'mvd.convict.pin',
            'params' => [
                'pin' => $pin

            ]
        ];

        try {
            $client = new Client(['verify' => false]);
            $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
                'json' => $data_by_pin
            ]);
            $result = json_decode((string)$response_by_passport->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $guzzleResult = $e->getResponse();
        }

        if (isset($guzzleResult) && ($guzzleResult->getStatusCode() == 500 || $guzzleResult->getStatusCode() == 503)) {
            return [
                'result' => null
            ];
        }
        return $result;
    }

    public static function getUzEduSchoolPin($pin)
    {
        $data_by_pin = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'uzedu.school.pin',
            'params' => [
                'pin' => $pin

            ]
        ];

        try {
            $client = new Client(['verify' => false]);
            $response_by_passport = $client->post(self::RESOURCE_URL_MVD, [
                'json' => $data_by_pin
            ]);
            $result = json_decode((string)$response_by_passport->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $guzzleResult = $e->getResponse();
        }

        if (isset($guzzleResult) && ($guzzleResult->getStatusCode() == 500 || $guzzleResult->getStatusCode() == 503)) {
            return [
                'result' => null
            ];
        }
        return $result;
    }

    public function getPassport($passport, $tin){
        $client = new Client(['verify' => false]);
        $data = [
            'version' => '1.0',
            'id' => 7436,
            'method' => 'ips.person',
            'params' => [
                'passport' => $passport,
                'pin' => $tin
            ]
        ];
        try {
            $response = $client->post(self::RESOURCE_URL, [
                'json' => $data
            ]);

            return json_decode((string)$response->getBody(), true);
        } catch (RequestException   $e) {
            return null;
        } catch (ConnectException    $e) {
            return null;
        }
    }
}
