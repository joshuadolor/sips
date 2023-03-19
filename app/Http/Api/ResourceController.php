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
use Illuminate\Validation\Rule;

class ResourceController extends BaseController
{
    private function getConfig($resource)
    {
        $resourceMap = [
            'users' => [
                'model' => UserModel::class,
                'role' => ['get' => 2, 'create' => 2, 'update' => 2],
                'updateRules' => function ($id) {
                    return [];
                },
            ],
            'companies' => [
                'model' => CompanyModel::class,
                'role' => ['get' => 2, 'create' => 2, 'update' => 2],
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
                'role' => ['get' => 0, 'create' => 1, 'update' => 1],
                'rules' => [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'rate' => 'required|numeric|gt:0',
                    'employee_code' => 'required|unique:employees,employee_code',
                ],
                'updateRules' => function ($id) {
                    return [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'rate' => 'required|numeric|gt:0',
                        'employee_code' => "required|unique:employees,employee_code,$id",
                    ];
                },

            ],
            'agents' => [
                'model' => AgentModel::class,
                'role' => ['get' => 0, 'create' => 1, 'update' => 1],

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
                'role' => ['get' => 0, 'create' => 1, 'update' => 1],
                'rules' => [
                    'name' => [
                        "required",
                        Rule::unique('products')
                            ->where(function ($query) {
                                return $query->where('name', request()->name)
                                    ->where('company_id', request()->company_id);
                            })
                        ,
                    ],
                    'price' => 'required|numeric|min:0|not_in:0',
                    'item_code' => 'required|unique:products,item_code',
                ],
                'updateRules' => function ($id) {
                    return [
                        'name' => [
                            "required",
                            Rule::unique('products')
                                ->where(function ($query) use ($id) {
                                    return $query->where('name', request()->name)
                                        ->where('company_id', request()->company_id);
                                })
                                ->where('id', "<>", $id)
                            ,
                        ],
                        'price' => 'required|numeric|min:0|not_in:0',
                        'item_code' => "required|unique:products,item_code,$id",
                    ];
                },
            ],
            'payroll' => [
                'model' => PayrollModel::class,
                'role' => ['get' => 0, 'create' => 0, 'update' => 1],
                'rules' => [
                    'date_from' => 'required|date',
                    'date_until' => 'required|date',
                    'employee_code' => 'required|exists:employees,employee_code',
                    'employee_name' => 'required',
                    'hours' => 'required',
                    'rate' => 'required',
                ],
            ],
        ];

        return isset($resourceMap[$resource]) ? $resourceMap[$resource] : false;
    }

    private function userCanProceed($config, $method)
    {
        $role = $config['role'][$method];
        return auth()->user()->role >= $role;
    }

    private function query($orm)
    {
        $isSuperAdmin = auth()->user()->role === 2;

        if ($isSuperAdmin) {
            return $orm::orderBy('created_at', 'desc');
        }

        $query = $orm::where('company_id', auth()->user()->company_id);

        return $query;
    }

    public function index($resource)
    {
        $config = $this->getConfig($resource);
        if (!$config) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        $isValidUser = $this->userCanProceed($config, 'get');
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

        $data = $this->query($orm)->get();

        return $this->sendResponse($data);
    }

    private function modelData($orm, $data, $isUpdate = false)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role === 2;

        $model = new $orm();
        $fillables = $model->getFillable();

        if (in_array('created_by', $fillables) && !$isUpdate) {
            $data['created_by'] = $user->id;
        }

        if (in_array('updated_by', $fillables)) {
            $data['updated_by'] = $user->id;
        }

        if (in_array('user_id', $fillables) && !$isUpdate) {
            $data['user_id'] = $user->id;
        }

        if (in_array('company_id', $fillables)) {
            if (!$isSuperAdmin) {
                $data['company_id'] = auth()->user()->company_id;
            }

        }

        return $data;
    }

    public function store($resource)
    {
        $config = $this->getConfig($resource);
        if (!$config) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        $isValidUser = $this->userCanProceed($config, 'create');
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

        //override company
        $data = $this->modelData($orm, $data);

        $data = $this->query($orm)->create($data);

        return $this->sendResponse($data);
    }

    public function update($resource, $id)
    {
        $config = $this->getConfig($resource);
        if (!$config) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }

        $isValidUser = $this->userCanProceed($config, 'update');
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
        //override company
        $data = $this->modelData($orm, $data, true);

        $data = $this->query($orm)
            ->find($id)
            ->update($data);

        return $this->sendResponse($data);
    }
}
