<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use App\Http\Resources\List\ListResource;
use App\Http\Resources\List\ListResourceCollection;
use App\Models\BoardList;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Throwable;

class ListController extends Controller
{
    private BoardList $boardList;

    public function __construct(BoardList $boardList)
    {
        $this->boardList = $boardList;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response|ResourceCollection
     */
    public function index()
    {
        return new ListResourceCollection($this->boardList->index());
    }

    /**
     * Display a listing of search the resource.
     *
     * @return ListResourceCollection
     */
    public function search(Request $request)
    {
        return new ListResourceCollection($this->boardList->search($request->all()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response|ListResource|JsonResponse
     */
    public function store(ListRequest $request)
    {
        try {
            $data = $this->boardList->saveData($request->all());
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('lists.store', null, $e);
        }

        return new ListResource($data, array('type' => 'store', 'route' => 'lists.store'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $data = $this->boardList->show($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('lists.show', $id, $e);
        }

        return new ListResource($data, array('type' => 'show', 'route' => 'lists.show'));
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
     * @return Response|ListResource|JsonResponse
     */
    public function update(ListRequest $request, $id)
    {
        try {
            $data = $this->boardList->saveData($request->all(), $id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('lists.update', null, $e);
        }

        return new ListResource($data, array('type' => 'update', 'route' => 'lists.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response|ListResource|JsonResponse
     */
    public function destroy($id)
    {
        try {
            $data = $this->boardList->remove($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('lists.destroy', $id, $e);
        }

        return new ListResource($data, array('type' => 'destroy', 'route' => 'lists.destroy'));
    }
}
