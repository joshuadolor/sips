<?php

namespace App\Http\Api;

use App\Http\Api\BaseController;
use App\Models\ProductMovement as Model;
use DB;
use Illuminate\Support\Facades\Validator;

class ProductMovementController extends BaseController
{
    public function index()
    {
        $types = isset(request()->sales) ? ['sales'] : ['receive', 'transfer'];

        $data = $this->query()
            ->whereIn('type', $types)
            ->get();

        return $this->sendResponse($data);
    }

    public function onHand()
    {

        $user = auth()->user();
        $isNormalUser = $user->role === 0;
        $isAdminUser = $user->role === 1;

        if ($isNormalUser) {
            return $this->sendError("Forbidden", null, 403);
        }

        $company = $user->company;

        $query = DB::table('products as p')->leftJoin('product_movements as pm', 'pm.product_id', '=', 'p.id')->selectRaw(
            "p.item_code as 'Item Code',
            p.name as 'Item Name',
                p.quantity +
                SUM(
                    CASE
                        WHEN pm.type='receive' then pm.quantity
                        WHEN pm.type='sales' or pm.type='transfer' then (-pm.quantity)
                        ELSE 0
                    END) as 'Quantity on hand',
                SUM(CASE WHEN pm.type='sales' then pm.quantity else 0 END) as 'Quantity Sold',
                SUM(CASE WHEN pm.type='receive' then pm.quantity else 0 END) as 'Quantity Received',
                SUM(CASE WHEN pm.type='transfer' then pm.quantity else 0 END) as 'Quantity Transfered',
                SUM(
                    CASE
                        WHEN pm.type='receive' then (-pm.cost)
                        WHEN pm.type='sales' or pm.type='transfer' then (pm.cost)
                        ELSE 0
                END) as 'Balance'
            "
        )->groupBy('p.id', 'p.item_code', 'p.name', 'p.quantity');

        if ($isAdminUser) {
            $query = $query->where('p.company_id', $company->id);
        }

        return $this->sendResponse($query->get());
    }

    public function store()
    {
        $rules = [
            'product_id' => 'required',
            'agent_id' => 'required',
            'cost' => 'required',
            'quantity' => 'required',
            'type' => 'required',
        ];

        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError("Validation Exception", $validator->messages(), 422);
        }

        $data = request()->all();
        //override company
        $data = $this->modelData($data);

        DB::beginTransaction();
        try {
            $data = $this->query()->create($data);
            $product = $data->product;
            if ($data->type === 'receive') {
                $product->quantity += $data->quantity;
            } else {
                //sales and transfer
                $product->quantity -= $data->quantity;
            }
            $product->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return $this->sendResponse($data);
    }

    private function query()
    {
        $user = auth()->user();

        $isSuperAdmin = $user->role === 2;

        if ($isSuperAdmin) {
            return Model::orderBy('created_at', 'desc');
        }

        $company = $user->company;

        $query = Model::whereHas('product', function ($query) use ($company) {
            return $query->where('company_id', '=', $company->id);
        })->orderBy('created_at', 'desc');

        return $query;
    }

    private function modelData($data, $isUpdate = false)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role === 2;

        $model = new Model;
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
}
