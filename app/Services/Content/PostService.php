<?php

namespace App\Services\Content;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostService extends CrudService
{
    public function __construct(PostRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function detail(Model $model): Model
    {
        return $model->load(['user:id,name', 'category:id,name,slug', 'tags:id,name,slug'])->loadCount(['tags']);
    }

    public function create(array $attributes): Model
    {
        return DB::transaction(function () use ($attributes) {
            $tags = $attributes['tags'] ?? null;
            unset($attributes['tags']);

            if (($attributes['status'] ?? null) === PostStatus::Published->value && empty($attributes['published_at'])) {
                $attributes['published_at'] = now();
            }

            /** @var Post $post */
            $post = parent::create($attributes);

            if (is_array($tags)) {
                $post->tags()->sync($tags);
            }

            return $post;
        });
    }

    public function update(Model $model, array $attributes): Model
    {
        return DB::transaction(function () use ($model, $attributes) {
            $tags = $attributes['tags'] ?? null;
            unset($attributes['tags']);

            $status = $attributes['status'] ?? $model->status;

            if ($status === PostStatus::Published->value && empty($attributes['published_at']) && $model->published_at === null) {
                $attributes['published_at'] = now();
            }

            /** @var Post $post */
            $post = parent::update($model, $attributes);

            if (is_array($tags)) {
                $post->tags()->sync($tags);
            }

            return $post;
        });
    }
}
