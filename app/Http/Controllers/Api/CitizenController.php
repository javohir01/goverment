<?php
namespace App\Http\Controllers\Api;

use App\Citizen;
use App\Http\Controllers\Controller;
use App\Repositories\CitizenRepository;
use App\Services\CitizenService;
use Illuminate\Http\Request;
use function response;

class CitizenController extends Controller
{
    public $modelClass;
    private $repo;
    private $service;
    protected $response;
    public function __construct()
    {
        $this->middleware('logs', ['only' => ['show', 'passport', 'passportDataFromBase']]);
        $this->modelClass = new Citizen;
        $this->repo = new CitizenRepository;
        $this->service = new CitizenService();
    }
    public function index(Request $request)
    {
        $citizens = $this->service->getAll($request);
        return response()->successJson(['citizens' => $citizens]);
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function show($id)
    {
        $citizen = $this->service->show($id);
        $this->response['result'] = [
            'citizen' => $citizen
        ];
        return response()->json($this->response);
    }

    public function update(Request $request, $id)
    {
        $citizen = $this->service->update($request, $id);

        return $citizen;
    }



}
