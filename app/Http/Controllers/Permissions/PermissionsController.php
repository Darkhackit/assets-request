<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ADD PERMISSION|MANAGE ALL',['only' => 'create']);
        $this->middleware('permission:LIST PERMISSION|MANAGE ALL',['only' => 'index']);
        $this->middleware('permission:UPDATE PERMISSION|MANAGE ALL',['only' => 'show']);
        $this->middleware('permission:UPDATE PERMISSION|MANAGE ALL',['only' => 'update']);
        $this->middleware('permission:DELETE PERMISSION|MANAGE ALL',['only' => 'delete']);
    }
    public function index(): JsonResponse
    {
        return \response()->json([
            'permissions' => Permission::query()
                ->when(request('q'),function ($query , $search) {
                    $query->where('name','like', "%{$search}%");
                })
                ->paginate(\request('perPage'))
                ->withQueryString()
                ->through(fn ($permission) => [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'roles' => $permission->roles->map(function ($role) {
                        return $role->name;
                    }),
                    'users' => User::permission($permission->name)->get()->map(function ($user) {
                        return $user->username;
                    }),
                ]),
        ],Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:permissions']
        ]);
        DB::beginTransaction();
        try {
            $permission = new Permission();
            $permission->name = strtoupper($request->name);

            $permission->save();

            DB::commit();
            return \response()->json($permission,Response::HTTP_CREATED);

        }catch (\Exception $e) {

            DB::rollBack();

            return response()->json(["errors" => [
                'server_error' => [$e->getMessage()]
            ]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function show(Permission $permission): JsonResponse
    {
        return \response()->json([
            'id' => $permission->id,
            'name' => $permission->name
        ],Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, Permission $permission): JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:permissions,name,'.$permission->id]
        ]);
        DB::beginTransaction();
        try {
            $permission->name = strtoupper($request->name);
            $permission->update();
            DB::commit();
            return \response()->json($permission,Response::HTTP_CREATED);
        }catch (\Exception $exception){
            DB::rollBack();
            return \response()->json(['errors' => [
                'server_error' => $exception->getMessage()
            ]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
    public function delete(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            foreach ($request->id as $item) {
                Permission::where('id',$item)->first()->delete();
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
    public function names($val): JsonResponse
    {
        $str = strtoupper($val);
        $permissions = Permission::where('name','like',"%{$str}%")->pluck('name');
        return \response()->json($permissions);
    }
}
