<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\category\CategoryResource;
use App\Http\Resources\Category\CategoryResourceCollection;
use App\Models\Category;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Throwable;

class CategoryController extends Controller
{
    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response|ResourceCollection
     */
    public function index()
    {
        return new CategoryResourceCollection($this->category->index());
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
     * @return Response|CategoryResource|JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        try {
            $data = $this->category->saveData($request->all());
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('categories.store', null, $e);
        }

        return new CategoryResource($data, array('type' => 'store', 'route' => 'categories.store'));
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
            $data = $this->category->show($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('categories.show', $id, $e);
        }

        return new CategoryResource($data, array('type' => 'show', 'route' => 'categories.show'));
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
     * @return Response|CategoryResource|JsonResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        try {
            $data = $this->category->saveData($request->all(), $id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('categories.update', null, $e);
        }

        return new CategoryResource($data, array('type' => 'update', 'route' => 'categories.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response|CategoryResource|JsonResponse
     */
    public function destroy($id)
    {
        try {
            $data = $this->category->remove($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('categories.destroy', $id, $e);
        }

        return new CategoryResource($data, array('type' => 'destroy', 'route' => 'categories.destroy'));
    }
}
