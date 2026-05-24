<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(): View
    {
        $categories = BlogCategory::withCount('blogs')
            ->orderBy('name')
            ->paginate(12);

        return view('blog-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('blog-categories.create', ['category' => new BlogCategory()]);
    }

    public function store(Request $request): RedirectResponse
    {
        BlogCategory::create($this->validateCategory($request));

        return Redirect::route('blog-categories.index')->with('success', 'Blog category created successfully.');
    }

    public function edit(BlogCategory $blogCategory): View
    {
        return view('blog-categories.edit', ['category' => $blogCategory]);
    }

    public function update(Request $request, BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->update($this->validateCategory($request, $blogCategory->id));

        return Redirect::route('blog-categories.index')->with('success', 'Blog category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->delete();

        return Redirect::route('blog-categories.index')->with('success', 'Blog category deleted successfully.');
    }

    protected function validateCategory(Request $request, ?int $categoryId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blog_categories,slug' . ($categoryId ? ",$categoryId" : '')],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
