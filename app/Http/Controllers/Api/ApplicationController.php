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
    public function update(Request $request, $id)
    {
        $result = $this->service->update($request, $id);

        return response()->successJson($result['application']);
    }

    public function destroy($id)
    {
        $citizen = $this->repo->getById($id);
        if ($citizen) {
            $citizen->delete();
            $this->response['success'] = true;
        } else {
            $this->response['success'] = false;
            $this->response['error'] = "Citizen not found";
        }
        return response()->json($this->response);
    }
}
