<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Http\Resources\Activity\ActivityResource;
use App\Http\Resources\Activity\ActivityResourceCollection;
use App\Models\Activity;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Throwable;

class ActivityController extends Controller
{
    private Activity $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response|ResourceCollection
     */
    public function index()
    {
        return new ActivityResourceCollection($this->activity->index());
    }

    /**
     * Display a listing of search the resource.
     *
     * @return ActivityResourceCollection
     */
    public function search(Request $request)
    {
        return new ActivityResourceCollection($this->activity->search($request->all()));
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
     * @return Response|ActivityResource|JsonResponse
     */
    public function store(ActivityRequest $request)
    {
        try {
            $data = $this->activity->saveData($request->all());
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('activities.store', null, $e);
        }

        return new ActivityResource($data, array('type' => 'store', 'route' => 'activities.store'));
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
            $data = $this->activity->show($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('activities.show', $id, $e);
        }

        return new ActivityResource($data, array('type' => 'show', 'route' => 'activities.show'));
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
     * @return Response|ActivityResource|JsonResponse
     */
    public function update(ActivityRequest $request, $id)
    {
        try {
            $data = $this->activity->saveData($request->all(), $id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('activities.update', null, $e);
        }

        return new ActivityResource($data, array('type' => 'update', 'route' => 'activities.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response|ActivityResource|JsonResponse
     */
    public function destroy($id)
    {
        try {
            $data = $this->activity->remove($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('activities.destroy', $id, $e);
        }

        return new ActivityResource($data, array('type' => 'destroy', 'route' => 'activities.destroy'));
    }
}
