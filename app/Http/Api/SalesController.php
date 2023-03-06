<?php

namespace App\Http\Api;

use App\Http\Api\BaseController;
use App\Models\ProductMovement as ProductMovementModel;
use App\Models\Sales as Model;
use DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends BaseController
{
    public function index()
    {
        $data = $this->query()
            ->get();

        return $this->sendResponse($data);
    }

    public function store()
    {
        $rules = [
            'product_movements' => 'required|array',
            'transaction_date' => 'required',
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
            $sales = $this->query()->create($data);
            foreach (request()->product_movements as $movement) {
                $createData = $this->modelData($movement);
                $createData['sale_id'] = $sales->id;
                $productMovement = ProductMovementModel::create($createData);

                $product = $productMovement->product;
                $product->quantity -= $createData['quantity'];
                $product->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            dd($e);
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

        $query = Model::where('company_id', '=', $company->id)
            ->orderBy('created_at', 'desc');

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
