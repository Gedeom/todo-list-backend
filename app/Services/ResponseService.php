<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class ResponseService
{
    /**
     * Default Responses.
     *
     * @return array
     */
    public static function default($config = array(), $id = null)
    {
        $route = $config['route'];

        switch ($config['type']) {
            case 'store':
                return [
                    'status' => true,
                    'msg' => 'Dado inserido com sucesso',
                    'url' => route($route)
                ];
                break;
            case 'show':
                return [
                    'status' => true,
                    'msg' => 'Requisição realizada com sucesso',
                    'url' => $id != null ? route($route, $id) : route($route)
                ];
                break;
            case 'search':
                return [
                    'status' => true,
                    'msg' => 'Requisição realizada com sucesso',
                    'url' => route($route)
                ];
                break;
            case 'update':
                return [
                    'status' => true,
                    'msg' => 'Dado Atualizado com sucesso',
                    'url' => $id != null ? route($route, $id) : route($route)
                ];
                break;
            case 'destroy':
                return [
                    'status' => true,
                    'msg' => 'Dado arquivado com sucesso',
                    'url' => $id != null ? route($route, $id) : route($route)
                ];
                break;
        }
    }

    /**
     * Register services.
     *
     * @return JsonResponse
     */
    public static function success($config = array(), $id = null)
    {
        $route = $config['route'];
        $msg = $config['msg'] ?? 'Sucesso!';
        $statusCode = $config['statusCode'] ?? 200;

        return response()->json([
            'status' => true,
            'msg' => $msg,
            'url' => $id != null ? route($route, $id) : route($route),
            'statusCode' => $statusCode
        ]);
    }

    /**
     * Register services.
     *
     * @return JsonResponse
     */
    public static function exception($route, $id = null, $e)
    {
        switch ($e->getCode()) {
            case -403:
            case -404:
            case -422:
                return response()->json([
                    'status' => false,
                    'statusCode' => abs($e->getCode()),
                    'msg' => $e->getMessage(),
                    'url' => $id != null ? route($route, $id) : route($route)
                ], abs($e->getCode()));
                break;
            default:
                if (app()->bound('sentry')) {
                    $sentry = app('sentry');
                    $user = auth()->user();
                    if ($user) {
                        $sentry->user_context(['id' => $user->id, 'name' => $user->name]);
                    }
                    $sentry->captureException($e);
                }
                return response()->json([
                    'status' => false,
                    'statusCode' => 500,
                    'msg' => 'Problema ao realizar a operação.' . $e->getMessage(),
                    'url' => $id != null ? route($route, $id) : route($route)
                ], 500);
                break;
        }
    }
}
