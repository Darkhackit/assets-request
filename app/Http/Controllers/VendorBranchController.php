<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorBranch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VendorBranchController extends Controller
{
    public function index(): JsonResponse
    {
        return \response()->json([
            'vendors' => VendorBranch::query()
                ->when(request('q'),function ($query , $search) {
                    $query->where('name','like', "%{$search}%");
                })
                ->latest('updated_at')
                ->paginate(\request('perPage'))
                ->withQueryString()
                ->through(fn ($vendor) => [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'address' => $vendor->address,
                    'active' => $vendor->active,
                    "vendor" => $vendor->vendor?->name,
                    "updated_by" => $vendor->updatedBy?->name,
                    "user" => $vendor->user?->name
                ]),
        ],Response::HTTP_OK);
    }
    /**
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request,[
            'vendor' => ['required'],
            'name' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $vendor = Vendor::query()->where('name',$request->vendor)->first();
            $vendor_branch = new VendorBranch();
            $vendor_branch->name = $request->name;
            $vendor_branch->address = $request->address;
            $vendor_branch->active = $request->active;
            $vendor_branch->vendor_id = $vendor->id;
            $vendor_branch->user_id = auth()->id();
            $vendor_branch->save();
            DB::commit();

            return response()->json(['success' => true]);

        }catch (\Exception $exception) {
            DB::rollBack();
            return  response()->json(['errors' => [
                "name" => [$exception->getMessage()]
            ]]);
        }
    }
    public function show(VendorBranch $vendorBranch): JsonResponse
    {
        return \response()->json([
            'id' => $vendorBranch->id,
            'name' => $vendorBranch->name,
            'address' => $vendorBranch->address,
            'active' => $vendorBranch->active,
            'vendor' => $vendorBranch->vendor?->name,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, VendorBranch $vendorBranch): JsonResponse
    {
        $this->validate($request,[
            'vendor' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            $vendor = Vendor::query()->where('name',$request->vendor)->first();
            $vendorBranch->name = $request->name;
            $vendorBranch->address = $request->address;
            $vendorBranch->active = $request->active;
            $vendorBranch->updated_by = auth()->id();
            $vendorBranch->vendor_id = $vendor->id;

            $vendorBranch->update();

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
                VendorBranch::where('id',$item)->first()->delete();
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
    public function names(string $val): JsonResponse
    {
        $vendor = Vendor::query()->where('name',$val)->first();
        $permissions = VendorBranch::query()->where('vendor_id',$vendor->id)->where('active',true)->pluck('name');
        return \response()->json($permissions);
    }
}
