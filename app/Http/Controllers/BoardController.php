<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardRequest;
use App\Http\Resources\Board\BoardResource;
use App\Http\Resources\Board\BoardResourceCollection;
use App\Models\Board;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Throwable;

class BoardController extends Controller
{
    private Board $board;

    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response|ResourceCollection
     */
    public function index()
    {
        return new BoardResourceCollection($this->board->index());
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
     * @return Response|BoardResource|JsonResponse
     */
    public function store(BoardRequest $request)
    {
        try {
            $data = $this->board->saveData($request->all());
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('boards.store', null, $e);
        }

        return new BoardResource($data, array('type' => 'store', 'route' => 'boards.store'));
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
            $data = $this->board->show($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('boards.show', $id, $e);
        }

        return new BoardResource($data, array('type' => 'show', 'route' => 'boards.show'));
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
     * @return Response|BoardResource|JsonResponse
     */
    public function update(BoardRequest $request, $id)
    {
        try {
            $data = $this->board->saveData($request->all(),$id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('boards.update', null, $e);
        }

        return new BoardResource($data, array('type' => 'update', 'route' => 'boards.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response|BoardResource|JsonResponse
     */
    public function destroy($id)
    {
        try {
            $data = $this->board->remove($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('boards.destroy', $id, $e);
        }

        return new BoardResource($data, array('type' => 'destroy', 'route' => 'boards.destroy'));
    }
}
