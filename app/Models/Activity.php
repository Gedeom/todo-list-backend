<?php

namespace App\Models;

use App\Http\Resources\Card\CardResource;
use App\Models\Utils\SearchUtils;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * App\Models\Activity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity query()
 * @mixin \Eloquent
 */
class Activity extends Model
{
    protected $table = 'boards_x_activities';
    use HasFactory;

    public function board(){
        return $this->belongsTo(Board::class,'board_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index()
    {
        return self::get();
    }

    /**
     * Display a listing of the resource.
     * @param array $data
     * @return Collection
     */
    public function search($data)
    {
        $model = Activity::newModelQuery();

        $model = (SearchUtils::createQuery($data, $model))->get();

        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Collection
     */
    public function show($id)
    {
        $data = self::find($id);

        if (!$data) {
            throw new \Exception('Nada Encontrado', -404);
        }

        return $data;
    }

    /**
     * Save the specified resource from api in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|CardResource
     */
    public function saveData($data, $id = null)
    {
        $data = json_decode(json_encode($data));

        $activity = new Activity();
        $activity->board_id = $data->board_id;
        $activity->user_id = auth()->user()->id;
        $activity->description = $data->description;
        $activity->save();

        return $this->show($activity->id);

        try {
            DB::beginTransaction();

            $data = json_decode(json_encode($data));

            $board = new Board();

            if ($id) {
                $board = Board::findOrFail($id);
            }

            $board->user_id = auth()->user()->id;
            $board->description = $data->description;
            $board->archived = $data->archived ?? 0;
            $board->save();

            $members = array_unique(array_map(function ($member) {
                return $member->id;
            }, $data->members ?? []));


            $board->members()->sync($members);

            DB::commit();
        } catch (Exception | Throwable $e) {
            DB::rollBack();
            throw new Exception($e);
        }

        return $this->show($board->id);
    }
}
