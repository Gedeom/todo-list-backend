<?php

namespace App\Http\Resources\List;

use App\Http\Resources\Board\BoardResource;
use App\Http\Resources\Card\CardResource;
use App\Http\Resources\User\UserResource;
use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
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
            'description' => $this->description,
            'sequence' => $this->sequence,
            'board' => BoardResource::make($this->board),
            'archived' => $this->archived,
        ];

        if (!\Route::is('cards.*')) {
            $data['cards'] =  CardResource::collection($this->cards()->orderBy('sequence')->get());
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
