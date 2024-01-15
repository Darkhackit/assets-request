<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

class VendorController extends Controller
{
    public function index(): JsonResponse
    {
        return \response()->json([
            'vendors' => Vendor::query()
                ->when(request('q'),function ($query , $search) {
                    $query->where('name','like', "%{$search}%");
                })
                ->paginate(\request('perPage'))
                ->withQueryString()
                ->through(fn ($vendor) => [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'address' => $vendor->address,
                    'active' => $vendor->active,
                    "user" => $vendor->user?->name,
                    "updated_by" => $vendor->updatedBy?->name
                ]),
        ],Response::HTTP_OK);
    }
    /**
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:vendors'],
        ]);
        DB::beginTransaction();
        try {

            $vendor = new Vendor();
            $vendor->name = $request->name;
            $vendor->address = $request->address;
            $vendor->active = $request->active;
            $vendor->user_id = auth()->id();
            $vendor->save();

            DB::commit();

            return response()->json(['success' => true]);

        }catch (\Exception $exception) {
            DB::rollBack();
            return  response()->json(['errors' => [
                "name" => [$exception->getMessage()]
            ]]);
        }
    }
    public function show(Vendor $vendor): JsonResponse
    {
        return \response()->json([
            'id' => $vendor->id,
            'name' => $vendor->name,
            'address' => $vendor->address,
            'active' => $vendor->active,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, Vendor $vendor): JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:vendors,name,'.$vendor->id],
        ]);
        DB::beginTransaction();
        try {

            $vendor->name = $request->name;
            $vendor->address = $request->address;
            $vendor->active = $request->active;
            $vendor->updated_by = auth()->id();

            $vendor->update();

            DB::commit();

            return response()->json(['success' => true]);

        }catch (\Exception $exception) {
            DB::rollBack();
            return  response()->json(['errors' => [
                "name" => [$exception->getMessage()]
            ]]);
        }
    }
    public function delete(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            foreach ($request->id as $item) {
                Vendor::where('id',$item)->first()->delete();
            }
            DB::commit();
            return \response()->json(['success' => true],Response::HTTP_OK);
        }catch (\Exception $exception) {
            DB::rollBack();
            return \response()->json(['errors' => [
                'server_error' => $exception->getMessage()
            ]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function names(): JsonResponse
    {
        $permissions = Vendor::query()->where('active',true)->pluck('name');
        return \response()->json($permissions);
    }
}
