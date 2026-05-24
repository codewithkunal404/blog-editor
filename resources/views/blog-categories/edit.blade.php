@extends('layouts.app')

@section('title', 'Edit Blog Category')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Edit Blog Category</h1>
            <p class="mt-2 text-sm text-slate-500">Update the category name, slug, or description.</p>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
            <form method="POST" action="{{ route('blog-categories.update', $category) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('blog-categories.partials.form-fields')
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('blog-categories.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-800 hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
