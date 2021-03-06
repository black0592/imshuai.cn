<?php

namespace App\Models;

#use Laravel\Scout\Searchable;


class Topic extends Model
{
    #use Searchable;

    protected $fillable = ['title', 'body', 'category_id',  'excerpt', 'slug'];

    protected $table = 'topics_copy';

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'topics_index';
    }


    //一个帖子属于一个分类
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

	//一个帖子属于一个用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //本地域排序
    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }
        // 预加载防止 N+1 问题
        return $query->with('user', 'category');
    }

    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }


    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    //帖子对应的标签
    public function tags()
    {
        return $this->belongsToMany(Tags::class,'topic_tag','topice_id','tag_id');
    }
}
