@extends('layouts.app')

@section('title', $blog->title)

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">{{ $blog->title }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ $blog->excerpt }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('blogs.edit', $blog) }}" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700">Edit</a>
                <a href="{{ route('blogs.preview', $blog) }}" class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">Preview</a>
            </div>
        </div>

        <x-blog-article :blog="$blog" />
    </div>
@endsection
