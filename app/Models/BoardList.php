<?php

namespace App\Models;

use App\Http\Resources\Board\BoardResource;
use App\Http\Resources\List\ListResource;
use App\Models\Utils\SearchUtils;
use DB;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Throwable;

/**
 * App\Models\BoardList
 *
 * @property int $id
 * @property int $board_id
 * @property string $description
 * @property int $sequence
 * @property int $archived
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BoardList newModelQuery()
 * @method static Builder|BoardList newQuery()
 * @method static Builder|BoardList query()
 * @method static Builder|BoardList whereArchived($value)
 * @method static Builder|BoardList whereBoardId($value)
 * @method static Builder|BoardList whereCreatedAt($value)
 * @method static Builder|BoardList whereDescription($value)
 * @method static Builder|BoardList whereId($value)
 * @method static Builder|BoardList whereSequence($value)
 * @method static Builder|BoardList whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BoardList extends Model
{
    protected $table = 'lists';
    use HasFactory;

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function cards(){
        return $this->hasMany(Card::class,'list_id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index()
    {
        return self::orderBy('sequence')->get();
    }

    /**
     * Display a listing of the resource.
     * @param array $data
     * @return Collection
     */
    public function search($data)
    {
        $model = BoardList::newModelQuery();

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
            throw new Exception('Nada Encontrado', -404);
        }

        return $data;
    }

    /**
     * Save the specified resource from api in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|BoardResource
     */
    public function saveData($data, $id = null)
    {

        try {
            DB::beginTransaction();

            $data = json_decode(json_encode($data));

            $board_list = new BoardList();

            if ($id) {
                $board_list = BoardList::findOrFail($id);
            }

            $sequence = $this->getNextSequence($data->board->id);
            $board_list->board_id = $id ? $board_list->board_id : $data->board->id;
            $board_list->description = $data->description;
            $board_list->sequence = $id ? $board_list->sequence : $sequence;
            $board_list->archived = $data->archived ?? 0;
            $board_list->save();


            DB::commit();
        } catch (Exception | Throwable $e) {
            DB::rollBack();
            throw new Exception($e);
        }

        return $this->show($board_list->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return ListResource|JsonResponse
     */
    public function remove($id)
    {
        try {
            DB::beginTransaction();
            $list = BoardList::find($id);

            if (!$list) {
                throw new Exception('Nada Encontrado', -404);
            }

            $list->archived = 1;
            $list->update();
            DB::commit();
        } catch (Throwable | Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $this->show($list->id);
    }

    private function getNextSequence($board_id){
        return BoardList::where('board_id','=',$board_id)
            ->selectRaw('coalesce(max(sequence),0) + 1 as sequence')
            ->first()->sequence;
    }

}
