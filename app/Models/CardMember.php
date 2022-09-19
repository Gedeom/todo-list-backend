<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CardMember
 *
 * @property int $id
 * @property int $card_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CardMemberFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CardMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|CardMember whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardMember whereUserId($value)
 * @mixin \Eloquent
 */
class CardMember extends Model
{
    protected $table = 'cards_x_members';
    use HasFactory;
}
