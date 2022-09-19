<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Project\ProjectResourceCollection;
use App\Models\Card;
use App\Models\Category;
use App\Models\Project;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Throwable;

class ProjectController extends Controller
{
    private Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response|ResourceCollection
     */
    public function index()
    {
        return new ProjectResourceCollection($this->project->index());
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
     * @return Response|ProjectResource|JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        try {
            $data = $this->project->saveData($request->all());
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('projects.store', null, $e);
        }

        return new ProjectResource($data, array('type' => 'store', 'route' => 'projects.store'));
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
            $data = $this->project->show($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('projects.show', $id, $e);
        }

        return new ProjectResource($data, array('type' => 'show', 'route' => 'projects.show'));
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
     * @return Response|ProjectResource|JsonResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        try {
            $data = $this->project->saveData($request->all(), $id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('projects.update', null, $e);
        }

        return new ProjectResource($data, array('type' => 'update', 'route' => 'projects.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response|ProjectResource|JsonResponse
     */
    public function destroy($id)
    {
        try {
            $data = $this->project->remove($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('projects.destroy', $id, $e);
        }

        return new ProjectResource($data, array('type' => 'destroy', 'route' => 'projects.destroy'));
    }
}
