<?php

namespace App\Repositories\BlogPosts;

use App\Models\BlogPost;
use App\Repositories\Interface\IBlogPosts;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class BlogPostsRepository.
 */
class BlogPostsRepository extends BaseRepository implements IBlogPosts
{
    /**
     * @return string
     *  Return the model
     */
    public function model(): string
    {
        return BlogPost::class;
    }

    public function searchBlogPosts($search)
    {
        return $this->model->with('user')
            ->when($search, function ($query, $search) {
                $query->where('title', $search)
                    ->orWhere('content', $search);
            });
    }

}
