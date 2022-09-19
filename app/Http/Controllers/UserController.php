<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserResourceCollection;
use App\Models\User;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Throwable;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response|ResourceCollection
     */
    public function index()
    {
        return new UserResourceCollection($this->user->index());
    }

    public function login(LoginRequest $request)
    {
        $credentials = (object) $request->only('email', 'password');

        try {
            $token = $this
                ->user
                ->login($credentials);

            $user = User::where('email', '=', $request->email)
                ->select('id', 'name', 'email')
                ->first();

            $user->token = $token;

        } catch (Throwable | Exception $e) {
            return ResponseService::exception('users.login', null, $e);
        }

        return response()->json($user);
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return JsonResponse|User|Response
     */
    public function logout(Request $request)
    {
        try {
            $this
                ->user
                ->logout($request->input('token'));
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('users.logout', null, $e);
        }

        return response(['status' => true, 'msg' => 'Deslogado com sucesso'], 200);
    }

    /**
     * Check Token
     *
     * @param Request $request
     * @return JsonResponse|User|Response
     */
    public function checkToken(Request $request)
    {
        try {
            $this
                ->user
                ->checkToken();

        } catch (Throwable | Exception $e) {
            return ResponseService::exception('users.checkToken', null, $e);
        }

        return response(['status' => true, 'msg' => 'Token válido!'], 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response|ResponseService|UserResource
     */
    public function create()
    {
        try {
            throw new Exception('Não implementado!');
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('users.create',null, $e);
        }

        return new UserResource($data, array('type' => 'create', 'route' => 'users.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|UserResource
     */
    public function store(Request $request)
    {
        try {
            throw new Exception('Não implementado!');
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('users.store',null, $e);
        }

        return new UserResource($data, array('type' => 'store', 'route' => 'users.store'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse|UserResource
     */
    public function show($id)
    {
        try {
            throw new Exception('Não implementado!');
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('users.show', $id, $e);
        }

        return new UserResource($data, array('type' => 'show', 'route' => 'users.show'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|UserResource
     */
    public function update(Request $request, $id)
    {
        try {
            throw new Exception('Não implementado!');
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('users.update', null, $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse|UserResource
     */
    public function destroy($id)
    {
        try {
            $data = $this->user->remove($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('users.destroy', $id, $e);
        }

        return new UserResource($data, array('type' => 'destroy', 'route' => 'users.destroy'));
    }
}
