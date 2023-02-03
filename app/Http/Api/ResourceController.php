<?php

namespace App\Http\Api;

use App\Http\Api\BaseController;
use App\Models\Agent as AgentModel;
use App\Models\Company as CompanyModel;
use App\Models\Employee as EmployeeModel;
use App\Models\Payroll as PayrollModel;
use App\Models\Product as ProductModel;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Validator;

class ResourceController extends BaseController
{
    private function getConfig($resource)
    {
        $resourceMap = [
            'users' => [
                'model' => UserModel::class,
                'role' => 2,
            ],
            'companies' => [
                'model' => CompanyModel::class,
                'role' => 2,
                'rules' => [
                    'name' => 'required|unique:companies,name',
                ],
                'updateRules' => function ($id) {
                    return [
                        'name' => "required|unique:companies,name,$id",
                    ];
                },
            ],
            'employees' => [
                'model' => EmployeeModel::class,
                'role' => 1,
                'rules' => [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'employee_code' => 'required|unique:employees,employee_code',
                    'company_id' => 'required|exists:companies,id',
                ],
                'updateRules' => function ($id) {
                    return [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'employee_code' => "required|unique:employees,employee_code,$id",
                        'company_id' => 'required|exists:companies,id',
                    ];
                },

            ],
            'agents' => [
                'model' => AgentModel::class,
                'role' => 1,
                'rules' => [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'company_id' => 'required|exists:companies,id',
                ],
                'updateRules' => function ($id) {
                    return [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'company_id' => 'required|exists:companies,id',
                    ];
                },
            ],
            'products' => [
                'model' => ProductModel::class,
                'role' => 1,
            ],
            'payroll' => [
                'model' => PayrollModel::class,
                'role' => 0,
                'rules' => [
                    'date_from' => 'required|date',
                    'date_until' => 'required|date',
                    'employee_code' => 'required|exists:employees,employee_code',
                    'employee_name' => 'required',
                    'hours' => 'required',
                    'rate' => 'required',
                    'company_id' => 'required|exists:companies,id',
                ],
            ],
        ];

        return isset($resourceMap[$resource]) ? $resourceMap[$resource] : false;
    }

    private function userCanProceed($role)
    {
        return auth()->user()->role >= $role;
    }

    private function query($orm, $method, $params = null)
    {
        $isSuperAdmin = auth()->user()->role === 2;

        if ($isSuperAdmin) {
            return $orm->{$method}($params);
        }
        $query = $orm::where('company_id', auth()->user()->company_id);
        if (!$params) {
            return $query->{$method}();
        }

        return $query->{$method}($params);
    }

    public function index($resource)
    {
        $config = $this->getConfig($resource);
        if (!$config) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        $isValidUser = $this->userCanProceed($config['role']);
        if (!$isValidUser) {
            return $this->sendError("Resource not available", null, 403);
        }

        $orm = $config['model'];
        if (!$orm) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        //TODO: queries
        // (request()->all()) {
        //  $orm->where()
        // }

        $data = $this->query($orm, 'get');

        return $this->sendResponse($data);
    }

    public function store($resource)
    {
        $config = $this->getConfig($resource);
        if (!$config) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        $isValidUser = $this->userCanProceed($config['role']);
        if (!$isValidUser) {
            return $this->sendError("Resource not available", null, 403);
        }

        $validator = Validator::make(request()->all(), $config['rules']);

        if ($validator->fails()) {
            return $this->sendError("Validation Exception", $validator->messages(), 422);
        }

        $orm = $config['model'];
        if (!$orm) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        $data = request()->all();

        $data = $this->query($orm, 'create', $data);

        return $this->sendResponse($data);
    }

    public function update($resource, $id)
    {
        $config = $this->getConfig($resource);
        if (!$config) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        $isValidUser = $this->userCanProceed($config['role']);
        if (!$isValidUser) {
            return $this->sendError("Resource not available", null, 403);
        }

        $validator = Validator::make(request()->all(), $config['updateRules']($id));

        if ($validator->fails()) {
            return $this->sendError("Validation Exception", $validator->messages(), 422);
        }

        $orm = $config['model'];
        if (!$orm) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        $data = request()->all();
        $data = $orm::find($id)->update($data);

        return $this->sendResponse($data);
    }
}
