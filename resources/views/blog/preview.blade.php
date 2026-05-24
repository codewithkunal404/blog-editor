@php
    $pageTitle = $blog->seo_title ?: ($blog->title ?: 'Blog Preview');
    $pageDescription = $blog->seo_description ?: ($blog->excerpt ?: '');
    $pageImage = $blog->seo_image ?: $blog->featured_image;
    $canonicalUrl = ! empty($blog->id) ? route('blogs.preview', $blog) : url()->current();
@endphp

@extends('layouts.app')

@section('title', $pageTitle)

@push('head')
    @if ($pageDescription)
        <meta name="description" content="{{ $pageDescription }}">
    @endif
    @if (! empty($blog->seo_keywords))
        <meta name="keywords" content="{{ $blog->seo_keywords }}">
    @endif
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $pageTitle }}">
    @if ($pageDescription)
        <meta property="og:description" content="{{ $pageDescription }}">
    @endif
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if ($pageImage)
        <meta property="og:image" content="{{ $pageImage }}">
    @endif
    @if (! empty($blog->published_at))
        <meta property="article:published_time" content="{{ $blog->published_at->toIso8601String() }}">
    @endif

    <meta name="twitter:card" content="{{ $pageImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    @if ($pageDescription)
        <meta name="twitter:description" content="{{ $pageDescription }}">
    @endif
    @if ($pageImage)
        <meta name="twitter:image" content="{{ $pageImage }}">
    @endif
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Preview Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-blue-100 p-2">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Preview Mode</p>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $pageTitle }}</h1>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                @if (! empty($blog->id))
                    <a href="{{ route('blogs.edit', $blog) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Post
                    </a>
                @else
                    <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back
                    </a>
                @endif
            </div>
        </div>

        <!-- Blog Article -->
        <x-blog-article :blog="$blog" />
    </div>
@endsection
