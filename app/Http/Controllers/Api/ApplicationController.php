<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Citizen;
use App\Repositories\ApplicationRepository;
use App\Repositories\CitizenRepository;
use App\Services\ApplicationService;
use App\Services\CitizenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public $modelClass;
    private $repo;
    private $service;
    public function __construct()
    {
//        $this->middleware('logs', ['only' => ['show', 'passport', 'passportDataFromBase']]);
        $this->modelClass = new Application;
        $this->repo = new ApplicationRepository;
        $this->service = new ApplicationService();
    }

    public function index(Request $request)
    {
//        $user = Auth::user();
//        return $user;
        $applications = $this->service->getAll($request);

        return response()->successJson(['applications' => $applications]);
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function show($id)
    {
        $application = $this->service->show($id);

        $this->response['result'] = [
            'application' => $application,
            'success' => true
        ];
        $response['result'] = [
            'application' => $application,
            'success' => true,
        ];
        return response()->json($this->response);
    }

    public function rejected(Request $request)
    {
        $result = $this->service->rejected($request);

        return response()->successJson($result);
    }

    public function confirmed(Request $request)
    {
        $result = $this->service->confirmed($request);
        return response()->successJson($result);
    }
    public function getNumber(Request $request){
        dd($request->all());
    }
    public function check(Request $request)
    {
        $result = $this->service->check($request);
        return response()->successJson($result);
    }
    public function getPassport(Request $request)
    {
        $result = $this->service->idCard($request);
        if($result['status'] == 404) {
            return response()->errorJson($result['msg'], 200);
        }
        if($result['status'] == 409 || !isset($result['citizen'])) {
            return response()->errorJson($result['msg'], 200, [], $result['application']);
        }
        return response()->successJson($result['citizen']);
    }

}
