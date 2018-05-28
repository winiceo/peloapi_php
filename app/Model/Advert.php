<?php

namespace App\Model;

use App\Scopes\StatusScope;

use DB;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;

use Illuminate\Database\Eloquent\SoftDeletes;

class Advert extends Model
{
    use SoftDeletes;

    const TABLE = 'advert';

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'coin_type',
        'trade_type',
        'country_code',
        'margin',
        'currency',
        'price',
        'min_price',
        'min_amount',
        'max_amount',
        'payment_window_minutes',
        'payment_provider',
        'message',
        'status'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }

    /**
     * Get the user for the discussion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo( User::class);
    }


    public static function feed(int $limit = 20): Collection
    {
        return static::feedQuery()->limit($limit)->get();
    }

    /**
      */
    public static function feedPaginated(int $perPage = 20): Paginator
    {
        return static::feedQuery()->paginate($perPage);
    }


    public static function feedByTagPaginated(Tag $tag, int $perPage = 20): Paginator
    {
        return static::feedQuery()
            ->join('taggables', function ($join) use ($tag) {
                $join->on('threads.id', 'taggables.taggable_id')
                    ->where('taggable_type', static::TABLE);
            })
            ->where('taggables.tag_id', $tag->id())
            ->paginate($perPage);
    }

    /**
     * This will order the threads by creation date and latest reply.
     */
    public static function feedQuery(): Builder
    {
//        return static::leftJoin('replies', function ($join) {
//            $join->on('threads.id', 'replies.replyable_id')
//                ->where('replies.replyable_type', static::TABLE);
//        })
//            ->orderBy('latest_creation', 'DESC')
//            ->groupBy('threads.id')
//            ->select('threads.*', DB::raw('
//                CASE WHEN COALESCE(MAX(replies.created_at), 0) > threads.created_at
//                THEN COALESCE(MAX(replies.created_at), 0)
//                ELSE threads.created_at
//                END AS latest_creation
//            '));


        return static:: orderBy('published_at', 'DESC')
            ->select('*');
    }

}
