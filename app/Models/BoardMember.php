<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BoardMember
 *
 * @property int $id
 * @property int $board_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\BoardMemberFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BoardMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BoardMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|BoardMember whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardMember whereUserId($value)
 * @mixin \Eloquent
 */
class BoardMember extends Model
{
    protected $table = 'boards_x_members';
    use HasFactory;
}
