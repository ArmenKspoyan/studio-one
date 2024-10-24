<?php

namespace App\Http\Controllers;

use App\Enum\Paginate;
use App\Http\Requests\BlogPost\BlogPostsRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\Interface\IBlogPosts;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BlogPostsController extends Controller
{

    public function __construct(
        protected readonly IBlogPosts $blogPosts,

    )
    {
    }

    public function index(): View|Factory|Application
    {
        $blogPosts = $this->blogPosts->with('user')->paginate(Paginate::PAGE_LIMIT->value);
        return view('blog-posts', ['blogPosts' => $blogPosts]);
    }

    public function store(BlogPostsRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $blogPost = $this->blogPosts->create([
            'user_id' => Auth::user()->id,
            'title' => $data['title'],
            'content' => $data['content'],
        ]);


        return Redirect::to('/blog-posts');
    }

    /**
     * @throws Exception
     */
    public function destroy(Request $request, int $id): SuccessResource|ErrorResource
    {
        $blogPost = $this->blogPosts->getById($id);
        if ($request->user()->cannot('delete', $blogPost)) {
            return ErrorResource::make([
                'message' => 'You dont have a permission to perform this action!'
            ]);
        }
        $this->blogPosts->deleteById($id);
        return SuccessResource::make([
            'message' => 'Blog Post deleted successfully!'
        ]);
    }

}
