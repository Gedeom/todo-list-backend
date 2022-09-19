<?php

namespace App\Http\Resources\Card;

use App\Http\Resources\Board\BoardResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\List\ListResource;
use App\Http\Resources\User\UserResource;
use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * @var
     */
    private $config;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $config = array())
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);

        $this->config = $config;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'sequence' => $this->sequence,
            'expected_date' => $this->expected_date,
            'category' => CategoryResource::make($this->category),
            'project' => CategoryResource::make($this->project),
            'members' => UserResource::collection($this->members),
            'archived' => $this->archived

        ];

        if (!\Route::is('lists.*')) {
            $data['list'] =  ListResource::make($this->board_list);
        }

        return $data;
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return ResponseService::default($this->config,$this->id);
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Illuminate\Http\Response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode(200);
    }
}
