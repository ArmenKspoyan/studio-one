<?php

namespace App\Http\Controllers;

use App\Enum\Paginate;
use App\Http\Requests\BlogPost\BlogPostsRequest;
use App\Http\Requests\BlogPost\GetBlogPostsRequest;
use App\Http\Requests\BlogPost\UpdateBlogPostsRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\BlogPost;
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

    public function index(Request $request): View|Factory|Application
    {
        $search = $request->input('search');
        $blogPosts = $this->blogPosts
            ->with('user')
            ->searchBlogPosts($search)
            ->paginate(Paginate::PAGE_LIMIT->value);

        return view('blog-posts', ['blogPosts' => $blogPosts, 'search' => $search]);
    }

    public function create(): Factory|View|Application
    {
        return view('store-blog-post');
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

    public function getById(GetBlogPostsRequest $request): SuccessResource
    {
        $data = $request->validated();
        return SuccessResource::make([
            'message' => 'Blog Post gedit successfully!',
            'data' => $this->blogPosts->getById($data['id']),
        ]);

    }

    public function update(UpdateBlogPostsRequest $request): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $success = $this->blogPosts->updateById($data['id'], ['title' => $data['title'], 'content' => $data['content']]);

        if (!$success) {
            return ErrorResource::make([
                'message' => 'Something went wrong!'
            ]);
        }

        return SuccessResource::make([
            'message' => 'Blog post updated successfully.',
        ]);
    }

    public function showBlogPost(Request $request): View|Factory|Application
    {
        $id = $request->query('id');
        $blogPost = $this->blogPosts
            ->with(['user', 'comments.user'])
            ->getById($id);

        return view('show-blog-post', compact('blogPost'));
    }

}
