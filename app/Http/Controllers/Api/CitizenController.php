<?php
namespace App\Http\Controllers\Api;

use App\Models\Citizen;
use App\Http\Controllers\Controller;
use App\Repositories\CitizenRepository;
use App\Services\CitizenService;
use Illuminate\Http\Request;

class CitizenController extends Controller
{
    public $modelClass;
    private $repo;
    private $service;
    public function __construct()
    {
//        $this->middleware('logs', ['only' => ['show', 'passport', 'passportDataFromBase']]);
        $this->modelClass = new Citizen;
        $this->repo = new CitizenRepository;
        $this->service = new CitizenService();
    }
    public function index(Request $request)
    {
        $citizens = $this->service->getAll($request);

//        $citizens = Citizen::query()->get();
//        if($request->has('getAll')){
//            $citizens = $citizens->paginate($citizens->count());
//        } else {
//            $citizens = $citizens->paginate($request->get('limit', 30));
//        }
        return response()->successJson(['citizens' => $citizens]);
    }

    public function store(Request $request)
    {
//        dd($request);
        return $this->service->store($request);
    }

    public function show($id)
    {
        $citizen = $this->service->show($id);

        $this->response['result'] = [
            'citizen' => $citizen,
            'success' => true
        ];
        $response['result'] = [
            'citizen' => $citizen,
            'success' => true,
        ];
        return response()->json($this->response);
    }
    public function update(Request $request, $id)
    {
//        dd($request);
//        $citizen = $this->service->update($request, $id);
//
//        return $citizen;
        $result = $this->service->update($request, $id);

        return response()->successJson($result['citizen']);
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

    public function passport(Request $request)
    {
        $result = $this->service->passport($request);
        if($result['status'] == 404) {
            return response()->errorJson($result['msg'], 200);
        }
        if($result['status'] == 409) {
            return response()->errorJson($result['msg'], 200, [], [], $result['code']);
        }
        return response()->successJson($result['citizen']);
    }
    public function getPassport(Request $request)
    {
        $result = $this->service->idCard($request);
        if($result['status'] == 404) {
            return response()->errorJson($result['msg'], 200);
        }
        if($result['status'] == 409 || !isset($result['citizen'])) {
            return response()->errorJson($result['msg'], 200, [], [], $result['code']);
        }
        return response()->successJson($result['citizen']);
    }

}
