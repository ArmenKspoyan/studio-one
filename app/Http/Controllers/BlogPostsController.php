<?php

namespace App\Http\Controllers;

use App\Enum\Paginate;
use App\Http\Requests\BlogPost\BlogPostsRequest;
use App\Repositories\Interface\IBlogPosts;
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

}
