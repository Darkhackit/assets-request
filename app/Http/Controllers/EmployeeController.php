<?php

namespace App\Http\Controllers;

use App\Mail\SendPersonalPassword;
use App\Models\User;
use App\Rules\CheckSamePassword;
use App\Rules\MatchOldPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ADD EMPLOYEE|MANAGE ALL',['only' => 'create']);
        $this->middleware('permission:LIST EMPLOYEE|MANAGE ALL',['only' => 'index']);
        $this->middleware('permission:UPDATE EMPLOYEE|MANAGE ALL',['only' => 'show']);
        $this->middleware('permission:UPDATE EMPLOYEE|MANAGE ALL',['only' => 'update']);
        $this->middleware('permission:DELETE EMPLOYEE|MANAGE ALL',['only' => 'delete']);
    }
    public function index(): JsonResponse
    {
        return \response()->json([
            'employees' => User::query()
                ->when(request('q'),function ($query , $search){
                    $query->where('name','like', "%{$search}%")
                        ->orWhere('email','like', "%{$search}%");
                })
                ->paginate(\request('perPage'))
                ->withQueryString()
                ->through(fn ($employee) => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'role' => $employee->roles->first()?->name,
                ]),
        ],Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request,[
            'name' =>  ['required'],
            'email' =>  ['required','unique:users','email'],
            'role' => ['required'],
            'password' => [
                'required',
                'confirmed',
            ]
        ]);
        DB::beginTransaction();
        try {
            $employee = new User();
            $employee->name = strtoupper($request->name);
            $employee->email = $request->email;
            $employee->password = Hash::make($request->password);
            $employee->save();
            $employee->assignRole($request->role);
            DB::commit();
            return \response()->json($employee,Response::HTTP_CREATED);

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["errors" => [
                'server_error' => [$e->getMessage()]
            ]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function show($id): JsonResponse
    {
        $user = User::query()->where('id',$id)->first();
        return \response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->first() ? $user->roles->first()->name : [] ,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request): JsonResponse
    {
        $employee = User::where('id',$request->id)->first();
        $this->validate($request,[
            'name' => ['required'],
            'role' => ['required'],
            'email' => ['required','email','unique:users,email,'.$employee->id],
        ]);
        DB::beginTransaction();
        try {
            $employee->name = strtoupper($request->name);
            $employee->email = $request->email;
            $employee->update();
            foreach ($employee->roles as $role) {
                $employee->removeRole($role);
            }

            $employee->assignRole($request->role);

            DB::commit();
            return \response()->json($employee,Response::HTTP_CREATED);
        }catch (\Exception $exception){
            DB::rollBack();
            return \response()->json(['errors' => [
                'server_error' => $exception->getMessage()
            ]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function disableToggle($id): JsonResponse
    {
        $employee = User::query()->where('id',$id)->first();
        $employee->delete();

        return \response()->json(['success' => true],Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    public function passwordReset(Request $request): JsonResponse
    {
        $employee = User::query()->where('id',$request->id)->first();
        $this->validate($request,[
            'password' => [
                'required',
                'confirmed',
            ]
        ]);
        $employee->password = Hash::make($request->password);
        $employee->update();
        return \response()->json($employee,Response::HTTP_OK);
    }
    public function delete(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            foreach ($request->id as $item) {

                User::query()->where('id',$item)->first()->delete();
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

    public function send_mail($id): JsonResponse
    {
        $user = User::query()->where('id',$id)->first();
        Mail::to($user->email)->send(new SendPersonalPassword($user));

        return \response()->json(['success' => true]);
    }

    public function sendAll(): void
    {
        DB::table('users')->orderBy('id')->chunk(10,function (Collection $users) {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new SendPersonalPassword($user));
            }
        });
    }

    /**
     * @throws ValidationException
     */
    public function changePassword(Request $request): JsonResponse
    {
        $this->validate($request,[
            'current_password' => ['required',new MatchOldPassword()],
            'password' => ['required','confirmed','min:6',new CheckSamePassword()]
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password),
            'save_password' => $request->password
        ]);

        return response()->json(['message' => 'password updated']);
    }

    /**
     * @throws ValidationException
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $this->validate($request,[
            'email' => ['required','email','exists:users']
        ]);

        $password = Str::random(8);

        $user = User::query()->where('email',$request->email)->first();
        $user->save_password = $password;
        $user->password = Hash::make($password);

        $user->update();

        Mail::to($user->email)->send(new SendPersonalPassword($user));

        return \response()->json(['success' => true]);

    }
}
