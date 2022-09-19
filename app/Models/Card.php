<?php

namespace App\Models;

use App\Http\Resources\Card\CardResource;
use App\Models\Utils\SearchUtils;
use Database\Factories\CardFactory;
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
 * App\Models\Card
 *
 * @property int $id
 * @property int $list_id
 * @property int $category_id
 * @property int $project_id
 * @property string $name
 * @property string $description
 * @property int $sequence
 * @property string $expected_date
 * @property int $archived
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|User[] $members
 * @property-read int|null $members_count
 * @method static CardFactory factory(...$parameters)
 * @method static Builder|Card newModelQuery()
 * @method static Builder|Card newQuery()
 * @method static Builder|Card query()
 * @method static Builder|Card whereArchived($value)
 * @method static Builder|Card whereCategoryId($value)
 * @method static Builder|Card whereCreatedAt($value)
 * @method static Builder|Card whereDescription($value)
 * @method static Builder|Card whereDtPrevisao($value)
 * @method static Builder|Card whereId($value)
 * @method static Builder|Card whereListId($value)
 * @method static Builder|Card whereName($value)
 * @method static Builder|Card whereProjectId($value)
 * @method static Builder|Card whereSequence($value)
 * @method static Builder|Card whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Card extends Model
{
    protected $table = 'cards';
    use HasFactory;

    public static function getNextSequence($list_id)
    {
        return Card::where('list_id', '=', $list_id)
            ->selectRaw('coalesce(max(sequence),0) + 1 as sequence')
            ->first()
            ->sequence;
    }

    public function board_list()
    {
        return $this->belongsTo(BoardList::class, 'list_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'cards_x_members', 'card_id', 'user_id');
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
        $model = Card::join('lists as list', 'list.id', '=', 'list_id')
            ->selectRaw('cards.*');

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
     * @return JsonResponse|CardResource
     */
    public function saveData($data, $id = null)
    {
        try {
            DB::beginTransaction();

            $data = json_decode(json_encode($data));
            $card = new Card();

            if ($id) {
                $card = Card::findOrFail($id);
            }

            $sequence = self::getNextSequence($data->list->id);

            $card->name = $data->name;
            $card->description = $data->description;
            $card->expected_date = $data->expected_date;
            $card->list_id = $data->list->id;
            $card->category_id = $data->category->id;
            $card->project_id = $data->project->id;
            $card->sequence = $id ? $data->sequence : $sequence;
            $card->archived = $data->archived ?? 0;
            $card->save();

            $members = array_unique(array_map(function ($member) {
                return $member->id;
            }, $data->members ?? []));


            $card->members()->sync($members);

            DB::commit();
        } catch (Exception | Throwable $e) {
            DB::rollBack();
            throw new Exception($e);
        }

        return $this->show($card->id);
    }

    /**
     * Save the specified resource from api in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|CardResource
     */
    public function saveMove($data, $id)
    {
        try {
            DB::beginTransaction();
            $data = json_decode(json_encode($data));

            $card = Card::findOrFail($id);
            $card->list_id = $data->to_list_id;
            $card->sequence = $data->next_order;
            $card->update();

            $this->reoderList($data->to_list_id, $data->next_order, $id);
            $this->orderList($data->to_list_id);

            if($data->from_list_id != $data->to_list_id){
                $this->orderList($data->from_list_id);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e);
        }

        return $this->show($id);
    }

    private function reoderList($list_id, $card_sequence, $card_updated_id)
    {
        $cards_previous = Card::orderBy('sequence')
            ->where('sequence','<=', $card_sequence)
            ->where('id','<>', $card_updated_id)
            ->where('list_id', '=', $list_id)
            ->get();

        \Log::debug(json_encode($cards_previous));

        $cards_next = Card::orderBy('sequence')
            ->where('sequence','>=', $card_sequence)
            ->where('id','<>', $card_updated_id)
            ->where('list_id', '=', $list_id)
            ->get();

        \Log::debug(json_encode($cards_next));

        $sequence = $card_sequence + 1;

        foreach ($cards_previous as $index => $card_prev){
            $card_prev->sequence = $index + 1;
            $card_prev->update();
        }

        foreach ($cards_next as $card_next){
            $card_next->sequence = $sequence;
            $card_next->update();
        }
    }

    private function orderList($list_id)
    {
        $cards = Card::orderBy('sequence')
            ->where('list_id', '=', $list_id)
            ->get();

        foreach ($cards as $index => $card){
            $card->sequence = $index + 1;
            $card->update();
        }

    }
}
