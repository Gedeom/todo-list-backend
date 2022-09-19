<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ActivityUserNotification
 *
 * @property int $id
 * @property int $board_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityUserNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityUserNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityUserNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityUserNotification whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityUserNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityUserNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityUserNotification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityUserNotification whereUserId($value)
 * @mixin \Eloquent
 */
class ActivityUserNotification extends Model
{
    protected $table = 'activities_x_users_notifications';
    use HasFactory;
}
