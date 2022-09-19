<?php

namespace App\Models;

use App\Http\Resources\Board\BoardResource;
use App\Http\Resources\Search\SearchResource;
use Database\Factories\BoardFactory;
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
 * App\Models\Board
 *
 * @property int $id
 * @property int $user_id
 * @property string $description
 * @property int $archived
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|BoardList[] $lists
 * @property-read int|null $lists_count
 * @property-read Collection|User[] $members
 * @property-read int|null $members_count
 * @method static BoardFactory factory(...$parameters)
 * @method static Builder|Board newModelQuery()
 * @method static Builder|Board newQuery()
 * @method static Builder|Board query()
 * @method static Builder|Board whereArchived($value)
 * @method static Builder|Board whereCreatedAt($value)
 * @method static Builder|Board whereDescription($value)
 * @method static Builder|Board whereId($value)
 * @method static Builder|Board whereUpdatedAt($value)
 * @method static Builder|Board whereUserId($value)
 * @mixin Eloquent
 */
class Board extends Model
{
    protected $table = 'boards';
    use HasFactory;

    public function lists()
    {
        return $this->hasMany(BoardList::class, 'board_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'boards_x_members', 'board_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
     * Display the specified resource.
     *
     * @param int $id
     * @return Collection
     */
    public function show($id)
    {
        $board = self::find($id);

        if (!$board) {
            throw new Exception('Nada Encontrado', -404);
        }

        return $board;
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

            $board = new Board();

            if($id){
                $board = Board::findOrFail($id);
            }

            $board->user_id = auth()->user()->id;
            $board->description = $data->description;
            $board->archived = $data->archived ?? 0;
            $board->save();

            $members = array_unique(array_map(function($member){
                return $member->id;
            },$data->members ?? []));


            $board->members()->sync($members);

            DB::commit();
        } catch (Exception | Throwable $e) {
            DB::rollBack();
            throw new Exception($e);
        }

        return $this->show($board->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return object
     */
    public function remove($id)
    {

        try {
            DB::beginTransaction();

            $board = Board::findOrFail($id);
            $board->archived = 1;
            $board->update();

            DB::commit();
        } catch (Exception | Throwable $e) {
            DB::rollBack();
            throw new Exception($e);
        }

        return $this->show($board->id);
    }
}
