<?php

namespace App\Http\Api;

use App\Http\Api\BaseController;
use App\Models\Agent as AgentModel;
use App\Models\Company as CompanyModel;
use App\Models\Employee as EmployeeModel;
use App\Models\Product as ProductModel;
use App\Models\User as UserModel;

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
            ],
            'employees' => [
                'model' => EmployeeModel::class,
            ],
            'agents' => [
                'model' => AgentModel::class,
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

    public function create($resource, $data)
    {
        $config = $this->getConfig($resource);
        if (!$config) {
            return $this->sendError("Resource: $resource, not found", null, 400);
        }
        $orm = $config['model'];

        $data = $orm::create($data);

        return $this->sendResponse($data);
    }
}
