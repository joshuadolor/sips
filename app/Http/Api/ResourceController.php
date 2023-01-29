<?php

namespace App\Http\Api;

use App\Http\Api\BaseController;
use App\Models\Agent as AgentModel;
use App\Models\Company as CompanyModel;
use App\Models\Employee as EmployeeModel;
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
            ],
            'companies' => [
                'model' => CompanyModel::class,
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
                'rules' => [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'employee_code' => 'required|unique:employees,employee_code',
                ],
                'updateRules' => function ($id) {
                    return [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'employee_code' => "required|unique:employees,employee_code,$id",
                    ];
                },

            ],
            'agents' => [
                'model' => AgentModel::class,
                'rules' => [
                    'first_name' => 'required',
                    'last_name' => 'required',
                ],
                'updateRules' => function ($id) {
                    return [
                        'first_name' => 'required',
                        'last_name' => 'required',
                    ];
                },
            ],
            'products' => [
                'model' => ProductModel::class,
            ],
        ];

        return isset($resourceMap[$resource]) ? $resourceMap[$resource] : false;
    }
    public function index($resource)
    {
        $config = $this->getConfig($resource);
        if (!$config) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        $orm = $config['model'];
        if (!$orm) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        //TODO: queries
        // (request()->all()) {
        //  $orm->where()
        // }

        $data = $orm::get();

        return $this->sendResponse($data);
    }

    public function store($resource)
    {
        $config = $this->getConfig($resource);
        if (!$config) {
            return $this->sendError("Resource: $resource, not found", null, 400);
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
        $data = $orm::create($data);

        return $this->sendResponse($data);
    }

    public function update($resource, $id)
    {
        $config = $this->getConfig($resource);
        if (!$config) {
            return $this->sendError("Resource: $resource, not found", null, 400);
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
