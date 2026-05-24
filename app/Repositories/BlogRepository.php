<?php

namespace App\Repositories;

use App\Models\Blog;

class BlogRepository
{
    public function paginate(int $perPage = 10)
    {
        return Blog::with('category')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function allPublished(int $limit = 10)
    {
        return Blog::where('status', 'published')
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function find(int $id): ?Blog
    {
        return Blog::find($id);
    }

    public function findBySlug(string $slug): ?Blog
    {
        return Blog::where('slug', $slug)->first();
    }

    public function create(array $data): Blog
    {
        return Blog::create($data);
    }

    public function update(Blog $blog, array $data): Blog
    {
        $blog->update($data);

        return $blog;
    }

    public function delete(Blog $blog): void
    {
        $blog->delete();
    }
}
