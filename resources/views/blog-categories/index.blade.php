@extends('layouts.app')

@section('title', 'Blog Categories')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">Blog Categories</h1>
                <p class="mt-2 text-sm text-slate-500">Create categories and assign posts to keep the blog organized.</p>
            </div>
            <a href="{{ route('blog-categories.create') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">New Category</a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Posts</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-slate-900">{{ $category->name }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $category->description ?: 'No description yet.' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $category->slug }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $category->blogs_count }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('blog-categories.edit', $category) }}" class="rounded-full bg-slate-100 px-3 py-1 text-slate-700 hover:bg-slate-200">Edit</a>
                                    <form action="{{ route('blog-categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-rose-100 px-3 py-1 text-rose-700 hover:bg-rose-200">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">No categories found. Create a category to use it on blog posts.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $categories->links() }}</div>
    </div>
@endsection
