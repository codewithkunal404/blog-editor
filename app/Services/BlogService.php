<?php

namespace App\Services;

use App\Models\Blog;
use App\Repositories\BlogRepository;

class BlogService
{
    public function __construct(protected BlogRepository $repository)
    {
    }

    public function paginate(int $perPage = 10)
    {
        return $this->repository->paginate($perPage);
    }

    public function get(int $id): ?Blog
    {
        return $this->repository->find($id);
    }

    public function getBySlug(string $slug): ?Blog
    {
        return $this->repository->findBySlug($slug);
    }

    public function create(array $data): Blog
    {
        return $this->repository->create($data);
    }

    public function update(Blog $blog, array $data): Blog
    {
        return $this->repository->update($blog, $data);
    }

    public function delete(Blog $blog): void
    {
        $this->repository->delete($blog);
    }
}
