@php
    $pageTitle = $blog->seo_title ?: ($blog->title ?: 'Blog Post');
    $pageDescription = $blog->seo_description ?: ($blog->excerpt ?: '');
    $pageImage = $blog->seo_image ?: $blog->featured_image;
    $canonicalUrl = route('blog.public-show', $blog->slug);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    
    @if ($pageDescription)
        <meta name="description" content="{{ $pageDescription }}">
    @endif
    @if (! empty($blog->seo_keywords))
        <meta name="keywords" content="{{ $blog->seo_keywords }}">
    @endif
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

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-white text-slate-900">
    <!-- Navigation -->
    <nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur-sm">
        <div class="mx-auto max-w-6xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-2xl font-bold text-slate-900">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.753 2 16.253s4.5 10 10 10 10-4.5 10-10-4.5-10-10-10z"></path>
                    </svg>
                    Blog
                </a>
                <div class="flex items-center gap-6">
                    <a href="{{ url('/') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">Home</a>
                    <a href="#" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">Articles</a>
                    <a href="#" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">About</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Featured Image -->
    @if (! empty($blog->featured_image))
        <div class="relative h-96 w-full overflow-hidden bg-slate-100">
            <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="h-full w-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <!-- Article Header -->
        <article class="space-y-8">
            <!-- Title and Meta -->
            <header class="space-y-6">
                <!-- Category Badge -->
                @if ($blog->category)
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 012-2h6a2 2 0 012 2v2h2a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V7a2 2 0 012-2h2V3z"></path>
                            </svg>
                            {{ $blog->category->name }}
                        </span>
                    </div>
                @endif

                <!-- Title -->
                <h1 class="text-5xl font-bold leading-tight text-slate-950">{{ $blog->title }}</h1>

                <!-- Excerpt -->
                @if (! empty($blog->excerpt))
                    <p class="text-xl leading-relaxed text-slate-600">{{ $blog->excerpt }}</p>
                @endif

                <!-- Article Meta -->
                <div class="flex flex-wrap items-center gap-6 border-t border-b border-slate-200 py-6 text-sm text-slate-600">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $blog->published_at?->format('M d, Y') ?? 'Not published' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>5 min read</span>
                    </div>
                </div>
            </header>

            <!-- Article Content -->
            <div class="prose prose-lg max-w-none space-y-8 text-slate-700">
                @php
                    $content = is_array($blog->content_json) ? $blog->content_json : json_decode($blog->content_json ?? '[]', true);
                    $styleAttr = function (array $styles = []) {
                        return collect($styles)
                            ->filter(fn ($value) => $value !== null && $value !== '')
                            ->map(fn ($value, $key) => \Illuminate\Support\Str::kebab($key) . ': ' . e($value))
                            ->implode('; ');
                    };
                @endphp

                @if (! empty($content) && is_array($content))
                    @foreach ($content as $block)
                        @php
                            $type = $block['type'] ?? '';
                            $props = $block['props'] ?? $block;
                            $styles = $block['styles'] ?? [];
                        @endphp

                        @if ($type === 'heading')
                            @php $level = in_array((int) ($props['level'] ?? 2), [1, 2, 3, 4, 5, 6], true) ? (int) $props['level'] : 2; @endphp
                            @if ($level === 1)
                                <h1 style="{{ $styleAttr($styles) }}" class="text-4xl font-bold text-slate-950">{{ $props['text'] ?? $props['content'] ?? '' }}</h1>
                            @elseif ($level === 2)
                                <h2 style="{{ $styleAttr($styles) }}" class="text-3xl font-bold text-slate-950 mt-8">{{ $props['text'] ?? $props['content'] ?? '' }}</h2>
                            @elseif ($level === 3)
                                <h3 style="{{ $styleAttr($styles) }}" class="text-2xl font-bold text-slate-950 mt-6">{{ $props['text'] ?? $props['content'] ?? '' }}</h3>
                            @elseif ($level === 4)
                                <h4 style="{{ $styleAttr($styles) }}" class="text-xl font-semibold text-slate-950 mt-4">{{ $props['text'] ?? $props['content'] ?? '' }}</h4>
                            @elseif ($level === 5)
                                <h5 style="{{ $styleAttr($styles) }}" class="text-lg font-semibold text-slate-950 mt-4">{{ $props['text'] ?? $props['content'] ?? '' }}</h5>
                            @else
                                <h6 style="{{ $styleAttr($styles) }}" class="text-base font-semibold text-slate-950 mt-4">{{ $props['text'] ?? $props['content'] ?? '' }}</h6>
                            @endif
                        @elseif ($type === 'paragraph')
                            <p style="{{ $styleAttr($styles) }}" class="text-lg leading-8 text-slate-700">{{ $props['text'] ?? $props['content'] ?? '' }}</p>
                        @elseif ($type === 'image')
                            @if (! empty($props['url']))
                                <figure class="my-8 space-y-3">
                                    <img src="{{ $props['url'] }}" alt="{{ $props['alt'] ?? '' }}" style="{{ $styleAttr($styles) }}" class="w-full rounded-lg object-cover shadow-lg" />
                                    @if (! empty($props['caption']))
                                        <figcaption class="text-center text-sm text-slate-500">{{ $props['caption'] }}</figcaption>
                                    @endif
                                </figure>
                            @endif
                        @elseif ($type === 'image_grid')
                            @php $images = collect(explode("\n", $props['images'] ?? ''))->map(fn ($item) => trim($item))->filter(); @endphp
                            @if ($images->isNotEmpty())
                                <div style="grid-template-columns: repeat({{ (int) ($props['columns'] ?? 2) }}, minmax(0, 1fr)); gap: {{ $styles['gap'] ?? '16px' }};" class="my-8 grid">
                                    @foreach ($images as $image)
                                        <img src="{{ $image }}" alt="" style="height: {{ $styles['imageHeight'] ?? '220px' }}; border-radius: {{ $styles['borderRadius'] ?? '12px' }};" class="w-full object-cover shadow-md" />
                                    @endforeach
                                </div>
                            @endif
                        @elseif ($type === 'quote')
                            <blockquote style="{{ $styleAttr($styles) }}" class="my-8 border-l-4 border-slate-300 bg-slate-50 py-6 pl-6 pr-4 text-lg italic text-slate-700">
                                <p>{{ $props['text'] ?? '' }}</p>
                                @if (! empty($props['author']))
                                    <footer class="mt-4 text-sm font-semibold text-slate-600">— {{ $props['author'] }}</footer>
                                @endif
                            </blockquote>
                        @elseif ($type === 'button')
                            <div class="my-6">
                                <a href="{{ $props['url'] ?? '#' }}" style="{{ $styleAttr($styles) }}" class="inline-flex rounded-lg bg-slate-900 px-8 py-4 font-semibold text-white transition hover:bg-slate-800">{{ $props['text'] ?? 'Click Here' }}</a>
                            </div>
                        @elseif ($type === 'social_links')
                            @php 
                                $links = collect(explode("\n", $props['links'] ?? ''))->map(fn ($item) => trim($item))->filter();
                                $sizeMap = ['small' => '32px', 'medium' => '40px', 'large' => '48px', 'extra-large' => '56px'];
                                $size = $sizeMap[$props['size'] ?? 'medium'] ?? '40px';
                                $svgIcons = [
                                    'facebook' => '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
                                    'twitter' => '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 002.856-3.915 10 10 0 01-2.856.973 5 5 0 00-8.66 4.59 14.23 14.23 0 01-10.337-5.196 5 5 0 001.551 6.759 5 5 0 01-2.265-.616v.06a5 5 0 004.009 4.905 5 5 0 01-2.26.086 5 5 0 004.666 3.472 10.029 10.029 0 01-6.2 2.14A14.233 14.233 0 0023 5.892a10.009 10.009 0 001.953-5.322z"/></svg>',
                                    'linkedin' => '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"/></svg>',
                                    'instagram' => '<svg fill="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/></svg>',
                                    'github' => '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v 3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>',
                                    'youtube' => '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
                                    'whatsapp' => '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.47-.148-.67.15-.23.297-.921 1.165-.949 1.404-.029.239-.097.36-.609.878-.513.519-1.921.196-2.465-.086-.545-.282-2.236-1.122-3.476-2.229-1.241-1.106-1.977-2.469-2.205-2.766-.228-.297-.024-.458.171-.606.175-.149.389-.39.584-.585.195-.195.259-.334.388-.557.129-.223.065-.417-.033-.586-.099-.17-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a6.963 6.963 0 00-6.961 6.961 6.968 6.968 0 006.961 6.961 6.968 6.968 0 006.961-6.961 6.963 6.963 0 00-6.957-6.961m0-1.261a8.23 8.23 0 018.23 8.23 8.23 8.23 0 01-8.23 8.23 8.23 8.23 0 01-8.23-8.23 8.23 8.23 0 018.23-8.23"/></svg>'
                                ];
                                $animationClass = [
                                    'hover-scale' => 'hover:scale-110',
                                    'hover-rotate' => 'hover:rotate-12',
                                    'hover-lift' => 'hover:-translate-y-1',
                                    'pulse' => 'animate-pulse',
                                    'bounce' => 'animate-bounce',
                                    'none' => ''
                                ][$props['animation'] ?? 'hover-scale'] ?? '';
                            @endphp
                            @if ($links->isNotEmpty())
                                <div style="display: flex; flex-direction: {{ $props['layout'] === 'vertical' ? 'column' : 'row' }}; gap: {{ $styles['gap'] ?? '12px' }};" class="my-8 flex">
                                    @foreach ($links as $link)
                                        @php
                                            [$platform, $url] = array_pad(explode('|', $link, 2), 2, '#');
                                            $platform = strtolower(trim($platform));
                                            $url = trim($url);
                                            $svg = $svgIcons[$platform] ?? '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>';
                                        @endphp
                                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" title="{{ ucfirst($platform) }}" style="width: {{ $size }}; height: {{ $size }}; display: flex; align-items: center; justify-content: center; border-radius: {{ $props['borderRadius'] ?? '50%' }}; background: {{ $props['backgroundColor'] ?? '#f3f4f6' }}; color: {{ $props['iconColor'] ?? '#374151' }}; text-decoration: none; transition: all 0.3s ease;" class="{{ $animationClass }}" onmouseover="this.style.background='{{ $props['hoverBackgroundColor'] ?? '#111827' }}';this.style.color='{{ $props['hoverIconColor'] ?? '#ffffff' }}'" onmouseout="this.style.background='{{ $props['backgroundColor'] ?? '#f3f4f6' }}';this.style.color='{{ $props['iconColor'] ?? '#374151' }}'">
                                            <div style="width: 60%; height: 60%; display: flex; align-items: center; justify-content: center;">
                                                {!! $svg !!}
                                            </div>
                                            @if (($props['showLabel'] ?? 'no') === 'yes')
                                                <span style="font-size: 12px; margin-top: 4px;">{{ ucfirst($platform) }}</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        @elseif ($type === 'list')
                            @php $items = collect(explode("\n", $props['items'] ?? ''))->map(fn ($item) => trim($item))->filter(); @endphp
                            @if (($props['ordered'] ?? 'no') === 'yes')
                                <ol style="{{ $styleAttr($styles) }}" class="my-6 list-decimal space-y-3 pl-8 text-lg">
                                    @foreach ($items as $item)
                                        <li class="text-slate-700">{{ $item }}</li>
                                    @endforeach
                                </ol>
                            @else
                                <ul style="{{ $styleAttr($styles) }}" class="my-6 list-disc space-y-3 pl-8 text-lg">
                                    @foreach ($items as $item)
                                        <li class="text-slate-700">{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @elseif ($type === 'section')
                            <section style="{{ $styleAttr($styles) }}" class="my-8 rounded-lg bg-slate-50 p-8">
                                @if (! empty($props['title']))
                                    <h2 class="text-2xl font-bold text-slate-950">{{ $props['title'] }}</h2>
                                @endif
                                @if (! empty($props['content']))
                                    <p class="mt-4 text-lg leading-8 text-slate-700">{{ $props['content'] }}</p>
                                @endif
                            </section>
                        @elseif ($type === 'divider')
                            <hr style="{{ $styleAttr($styles) }}" class="my-8 border-slate-200">
                        @elseif ($type === 'grid')
                            @php $items = collect(explode("\n", $props['items'] ?? ''))->map(fn ($item) => trim($item))->filter(); @endphp
                            <div style="grid-template-columns: repeat({{ (int) ($props['columns'] ?? 2) }}, minmax(0, 1fr)); gap: {{ $props['gap'] ?? '20px' }};" class="my-8 grid">
                                @foreach ($items as $item)
                                    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">{{ $item }}</div>
                                @endforeach
                            </div>
                        @elseif ($type === 'row')
                            @php $items = collect(explode("\n", $props['items'] ?? ''))->map(fn ($item) => trim($item))->filter(); @endphp
                            <div style="grid-template-columns: repeat({{ (int) ($props['columns'] ?? 3) }}, minmax(0, 1fr)); gap: {{ $styles['gap'] ?? '16px' }};" class="my-8 grid">
                                @foreach ($items as $item)
                                    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">{{ $item }}</div>
                                @endforeach
                            </div>
                        @elseif ($type === 'link')
                            @if (! empty($props['url']))
                                <p><a href="{{ $props['url'] }}" target="{{ $props['target'] ?? '_self' }}" rel="{{ ($props['target'] ?? '_self') === '_blank' ? 'noopener noreferrer' : '' }}" class="font-semibold text-slate-900 underline decoration-slate-300 underline-offset-4 hover:decoration-slate-900">{{ $props['text'] ?? $props['label'] ?? $props['url'] }}</a></p>
                            @endif
                        @elseif ($type === 'cta')
                            <section style="{{ $styleAttr($styles) }}" class="my-12 rounded-lg bg-gradient-to-r from-slate-900 to-slate-800 p-12 text-white shadow-lg">
                                <h2 class="text-3xl font-bold">{{ $props['title'] ?? '' }}</h2>
                                <p class="mt-4 text-lg leading-8 text-slate-200">{{ $props['text'] ?? '' }}</p>
                                @if (! empty($props['buttonUrl']) || ! empty($props['button_url']))
                                    <a href="{{ $props['buttonUrl'] ?? $props['button_url'] }}" class="mt-8 inline-flex rounded-lg bg-white px-8 py-4 font-semibold text-slate-900 transition hover:bg-slate-100">{{ $props['buttonText'] ?? $props['button_label'] ?? 'Open' }}</a>
                                @endif
                            </section>
                        @elseif ($type === 'code')
                            <pre style="{{ $styleAttr($styles) }}" class="my-8 overflow-x-auto rounded-lg bg-slate-950 p-6 text-sm leading-6 text-slate-100"><code>{{ $props['code'] ?? '' }}</code></pre>
                        @endif
                    @endforeach
                @else
                    <p class="text-sm text-slate-500">No content available yet.</p>
                @endif
            </div>

            <!-- Article Footer -->
            <footer class="border-t border-slate-200 pt-8">
                <div class="flex flex-col gap-8 sm:flex-row sm:items-center sm:justify-between">
                    <!-- Author Info -->
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-slate-400 to-slate-600"></div>
                        <div>
                            <p class="font-semibold text-slate-900">Author Name</p>
                            <p class="text-sm text-slate-600">Content Creator</p>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-slate-600">Share:</span>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($canonicalUrl) }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition hover:bg-blue-100 hover:text-blue-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($canonicalUrl) }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition hover:bg-blue-100 hover:text-blue-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M20 10a10 10 0 11-20 0 10 10 0 0120 0zm-4.5-6.5h-1.5A3.5 3.5 0 0010 6.5v1.5H8.5v2h1.5v5h2v-5h1.5v-2H11V7a.5.5 0 01.5-.5h1.5v-2z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($canonicalUrl) }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition hover:bg-blue-100 hover:text-blue-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.338 16.338H13.67V12.16c0-.995-.017-2.292-1.194-2.292-1.195 0-1.38.932-1.38 1.893v4.038h-2.568V9.309h2.461v.956h.034c.343-.65 1.08-1.335 2.226-1.335 2.38 0 2.821 1.565 2.821 3.721v4.063zM5.337 8.855c-.794 0-1.427-.645-1.427-1.435 0-.79.633-1.435 1.427-1.435.794 0 1.426.645 1.426 1.435 0 .79-.632 1.435-1.426 1.435zm1.128 7.483H4.209V9.309h2.256v6.029zM17.541 3H2.458C1.3 3 .5 3.8.5 4.958v10.084C.5 16.2 1.3 17 2.458 17h15.083c1.157 0 2.042-.8 2.042-1.958V4.958C19.583 3.8 18.7 3 17.541 3z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </footer>
        </article>

        <!-- Related Articles Section -->
        <section class="mt-16 border-t border-slate-200 pt-12">
            <h2 class="text-3xl font-bold text-slate-950">More Articles</h2>
            <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @for ($i = 1; $i <= 3; $i++)
                    <article class="group overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:shadow-lg">
                        <div class="h-48 bg-gradient-to-br from-slate-200 to-slate-300"></div>
                        <div class="p-6">
                            <p class="text-sm font-semibold text-slate-500">Category</p>
                            <h3 class="mt-2 text-lg font-bold text-slate-950 group-hover:text-slate-700">Related Article Title</h3>
                            <p class="mt-2 text-sm text-slate-600">Brief description of the article goes here...</p>
                            <a href="#" class="mt-4 inline-flex text-sm font-semibold text-slate-900 hover:text-slate-600">Read More →</a>
                        </div>
                    </article>
                @endfor
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-slate-50 py-12 mt-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <h3 class="font-bold text-slate-900">Blog</h3>
                    <p class="mt-2 text-sm text-slate-600">Your source for quality content and insights.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-900">Quick Links</h4>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li><a href="{{ url('/') }}" class="hover:text-slate-900">Home</a></li>
                        <li><a href="#" class="hover:text-slate-900">Articles</a></li>
                        <li><a href="#" class="hover:text-slate-900">Categories</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-900">Follow Us</h4>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li><a href="#" class="hover:text-slate-900">Twitter</a></li>
                        <li><a href="#" class="hover:text-slate-900">Facebook</a></li>
                        <li><a href="#" class="hover:text-slate-900">LinkedIn</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-900">Legal</h4>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li><a href="#" class="hover:text-slate-900">Privacy</a></li>
                        <li><a href="#" class="hover:text-slate-900">Terms</a></li>
                        <li><a href="#" class="hover:text-slate-900">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-slate-200 pt-8 text-center text-sm text-slate-600">
                <p>&copy; {{ date('Y') }} Blog. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @vite('resources/js/app.js')
</body>
</html>
