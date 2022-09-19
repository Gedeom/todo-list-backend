<?php

namespace App\Http\Controllers;

use App\Http\Requests\Card\CardRequest;
use App\Http\Requests\Card\MoveCardRequest;
use App\Http\Resources\Card\CardResource;
use App\Http\Resources\Card\CardResourceCollection;
use App\Models\Card;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Throwable;

class CardController extends Controller
{
    private Card $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response|ResourceCollection
     */
    public function index()
    {
        return new CardResourceCollection($this->card->index());
    }

    /**
     * Display a listing of search the resource.
     *
     * @return CardResourceCollection
     */
    public function search(Request $request)
    {
        return new CardResourceCollection($this->card->search($request->all()));
    }

    /**
     * Display a listing of search the resource.
     *
     * @return CardResourceCollection
     */
    public function moveCard(Request $request, $id)
    {
        try {
            $data = $this->card->saveMove($request->all(), $id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('cards.move', null, $e);
        }

        return new CardResource($data, array('type' => 'update', 'route' => 'cards.move'));    }

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
     * @return Response|CardResource|JsonResponse
     */
    public function store(CardRequest $request)
    {
        try {
            $data = $this->card->saveData($request->all());
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('cards.store', null, $e);
        }

        return new CardResource($data, array('type' => 'store', 'route' => 'cards.store'));
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
            $data = $this->card->show($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('cards.show', $id, $e);
        }

        return new CardResource($data, array('type' => 'show', 'route' => 'cards.show'));
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
     * @return Response|CardResource|JsonResponse
     */
    public function update(CardRequest $request, $id)
    {
        try {
            $data = $this->card->saveData($request->all(), $id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('cards.update', null, $e);
        }

        return new CardResource($data, array('type' => 'update', 'route' => 'cards.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response|CardResource|JsonResponse
     */
    public function destroy($id)
    {
        try {
            $data = $this->card->remove($id);
        } catch (Throwable | Exception $e) {
            return ResponseService::exception('cards.destroy', $id, $e);
        }

        return new CardResource($data, array('type' => 'destroy', 'route' => 'cards.destroy'));
    }
}
