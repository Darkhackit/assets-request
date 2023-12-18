<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ADD ROLE|MANAGE ALL',['only' => 'create']);
        $this->middleware('permission:LIST ROLE|MANAGE ALL',['only' => 'index']);
        $this->middleware('permission:UPDATE ROLE|MANAGE ALL',['only' => 'show']);
        $this->middleware('permission:UPDATE ROLE|MANAGE ALL',['only' => 'update']);
        $this->middleware('permission:DELETE ROLE|MANAGE ALL',['only' => 'delete']);
    }
    public function index(): \Illuminate\Http\JsonResponse
    {
        return \response()->json([
            'roles' => Role::query()
                ->when(request('q'),function ($query , $search) {
                    $query->where('name','like', "%{$search}%");
                })
                ->paginate(\request('perPage'))
                ->withQueryString()
                ->through(fn ($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return $permission->name;
                    }),
                    'users' => $role->users->map(function ($user) {
                        return $user->username;
                    }),
                ]),
        ],Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:roles'],
            'permissions*' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            $role = new Role();
            $role->name = strtoupper($request->name);
            $role->save();

            $role->syncPermissions($request->permissions);

            DB::commit();
            return \response()->json($role,Response::HTTP_CREATED);

        }catch (\Exception $e) {

            DB::rollBack();

            return response()->json(["errors" => [
                'server_error' => [$e->getMessage()]
            ]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function show(Role $role): \Illuminate\Http\JsonResponse
    {
        return \response()->json([
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $role->permissions->map(function ($permission) {
                return $permission->name;
            })
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, Role $role): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:permissions,name,'.$role->id],
            'permissions*' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            $role->name = strtoupper($request->name);
            $role->update();
            $roles = $role->permissions;
            foreach ($roles as $r) {
                $role->revokePermissionTo($r->id);
            }
            $role->syncPermissions($request->permissions);
            DB::commit();
            return \response()->json($role,Response::HTTP_CREATED);
        }catch (\Exception $exception){
            DB::rollBack();
            return \response()->json(['errors' => [
                'server_error' => $exception->getMessage()
            ]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            foreach ($request->id as $item) {
                Role::where('id',$item)->first()->delete();
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
    public function names($val): \Illuminate\Http\JsonResponse
    {
        $str = strtoupper($val);
        $roles = Role::where('name','like',"%{$str}%")->pluck('name');
        return \response()->json($roles);
    }
}
