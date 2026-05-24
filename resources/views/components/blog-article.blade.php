e@php
    $content = is_array($blog->content_json) ? $blog->content_json : json_decode($blog->content_json ?? '[]', true);
    $styleAttr = function (array $styles = []) {
        return collect($styles)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value, $key) => \Illuminate\Support\Str::kebab($key) . ': ' . e($value))
            ->implode('; ');
    };
@endphp

<article class="mx-auto max-w-3xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    @if (! empty($blog->featured_image))
        <figure class="bg-slate-100">
            <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="max-h-[460px] w-full object-cover" />
        </figure>
    @endif

    <div class="px-6 py-8 sm:px-10">
        <header class="border-b border-slate-200 pb-8">
            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <span>{{ $blog->category?->name ?? 'Uncategorized' }}</span>
                @if (! empty($blog->published_at))
                    <span>{{ $blog->published_at->format('M d, Y') }}</span>
                @endif
            </div>
            <h1 class="mt-4 text-4xl font-semibold leading-tight text-slate-950">{{ $blog->title }}</h1>
            @if (! empty($blog->excerpt))
                <p class="mt-4 text-lg leading-8 text-slate-600">{{ $blog->excerpt }}</p>
            @endif
        </header>

        <div class="mt-8 space-y-6 text-slate-700">
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
                            <h1 style="{{ $styleAttr($styles) }}" class="pt-4 text-4xl font-semibold leading-tight text-slate-950">{{ $props['text'] ?? $props['content'] ?? '' }}</h1>
                        @elseif ($level === 2)
                            <h2 style="{{ $styleAttr($styles) }}" class="pt-4 text-2xl font-semibold leading-snug text-slate-950">{{ $props['text'] ?? $props['content'] ?? '' }}</h2>
                        @elseif ($level === 3)
                            <h3 style="{{ $styleAttr($styles) }}" class="pt-3 text-xl font-semibold leading-snug text-slate-950">{{ $props['text'] ?? $props['content'] ?? '' }}</h3>
                        @elseif ($level === 4)
                            <h4 style="{{ $styleAttr($styles) }}" class="pt-2 text-lg font-semibold leading-snug text-slate-950">{{ $props['text'] ?? $props['content'] ?? '' }}</h4>
                        @elseif ($level === 5)
                            <h5 style="{{ $styleAttr($styles) }}" class="pt-2 text-base font-semibold leading-snug text-slate-950">{{ $props['text'] ?? $props['content'] ?? '' }}</h5>
                        @else
                            <h6 style="{{ $styleAttr($styles) }}" class="pt-2 text-sm font-semibold leading-snug text-slate-950">{{ $props['text'] ?? $props['content'] ?? '' }}</h6>
                        @endif
                    @elseif ($type === 'paragraph')
                        <p style="{{ $styleAttr($styles) }}" class="text-base leading-8">{{ $props['text'] ?? $props['content'] ?? '' }}</p>
                    @elseif ($type === 'image')
                        @if (! empty($props['url']))
                            <figure class="space-y-2">
                                <img src="{{ $props['url'] }}" alt="{{ $props['alt'] ?? '' }}" style="{{ $styleAttr($styles) }}" class="w-full rounded-xl object-cover" />
                                @if (! empty($props['caption']))
                                    <figcaption class="text-sm text-slate-500">{{ $props['caption'] }}</figcaption>
                                @endif
                            </figure>
                        @endif
                    @elseif ($type === 'image_grid')
                        @php $images = collect(explode("\n", $props['images'] ?? ''))->map(fn ($item) => trim($item))->filter(); @endphp
                        @if ($images->isNotEmpty())
                            <div style="grid-template-columns: repeat({{ (int) ($props['columns'] ?? 2) }}, minmax(0, 1fr)); gap: {{ $styles['gap'] ?? '16px' }}; margin-top: {{ $styles['marginTop'] ?? '20px' }}; margin-bottom: {{ $styles['marginBottom'] ?? '20px' }};" class="grid">
                                @foreach ($images as $image)
                                    <img src="{{ $image }}" alt="" style="height: {{ $styles['imageHeight'] ?? '220px' }}; border-radius: {{ $styles['borderRadius'] ?? '12px' }};" class="w-full object-cover" />
                                @endforeach
                            </div>
                        @endif
                    @elseif ($type === 'quote')
                        <blockquote style="{{ $styleAttr($styles) }}" class="rounded-xl text-lg leading-8 text-slate-700">
                            <p>{{ $props['text'] ?? '' }}</p>
                            @if (! empty($props['author']))
                                <footer class="mt-3 text-sm font-semibold text-slate-500">{{ $props['author'] }}</footer>
                            @endif
                        </blockquote>
                    @elseif ($type === 'button')
                        <p><a href="{{ $props['url'] ?? '#' }}" style="{{ $styleAttr($styles) }}" class="inline-flex font-semibold">{{ $props['text'] ?? 'Click Here' }}</a></p>
                    @elseif ($type === 'list')
                        @php $items = collect(explode("\n", $props['items'] ?? ''))->map(fn ($item) => trim($item))->filter(); @endphp
                        @if (($props['ordered'] ?? 'no') === 'yes')
                            <ol style="{{ $styleAttr($styles) }}" class="list-decimal space-y-2 pl-6">
                                @foreach ($items as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        @else
                            <ul style="{{ $styleAttr($styles) }}" class="list-disc space-y-2 pl-6">
                                @foreach ($items as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @elseif ($type === 'section')
                        <section style="{{ $styleAttr($styles) }}" class="rounded-xl">
                            @if (! empty($props['title']))
                                <h2 class="text-2xl font-semibold text-slate-950">{{ $props['title'] }}</h2>
                            @endif
                            @if (! empty($props['content']))
                                <p class="mt-3 text-base leading-8 text-slate-700">{{ $props['content'] }}</p>
                            @endif
                        </section>
                    @elseif ($type === 'divider')
                        <hr style="{{ $styleAttr($styles) }}" class="border-slate-200">
                    @elseif ($type === 'grid')
                        @php $items = collect(explode("\n", $props['items'] ?? ''))->map(fn ($item) => trim($item))->filter(); @endphp
                        <div style="grid-template-columns: repeat({{ (int) ($props['columns'] ?? 2) }}, minmax(0, 1fr)); gap: {{ $props['gap'] ?? '20px' }}; {{ $styleAttr($styles) }}" class="grid">
                            @foreach ($items as $item)
                                <div class="rounded-xl border border-slate-200 bg-white p-4">{{ $item }}</div>
                            @endforeach
                        </div>
                    @elseif ($type === 'row')
                        @php $items = collect(explode("\n", $props['items'] ?? ''))->map(fn ($item) => trim($item))->filter(); @endphp
                        <div style="grid-template-columns: repeat({{ (int) ($props['columns'] ?? 3) }}, minmax(0, 1fr)); gap: {{ $styles['gap'] ?? '16px' }}; align-items: {{ $styles['alignItems'] ?? 'stretch' }}; margin-top: {{ $styles['marginTop'] ?? '20px' }}; margin-bottom: {{ $styles['marginBottom'] ?? '20px' }};" class="grid">
                            @foreach ($items as $item)
                                <div class="rounded-xl border border-slate-200 bg-white p-4">{{ $item }}</div>
                            @endforeach
                        </div>
                    @elseif ($type === 'link')
                        @if (! empty($props['url']))
                            <p><a href="{{ $props['url'] }}" target="{{ $props['target'] ?? '_self' }}" rel="{{ ($props['target'] ?? '_self') === '_blank' ? 'noopener noreferrer' : '' }}" class="font-semibold text-slate-950 underline decoration-slate-300 underline-offset-4 hover:decoration-slate-950">{{ $props['text'] ?? $props['label'] ?? $props['url'] }}</a></p>
                        @endif
                    @elseif ($type === 'cta')
                        <section style="{{ $styleAttr($styles) }}" class="rounded-xl bg-slate-950 p-6 text-white">
                            <h2 class="text-2xl font-semibold">{{ $props['title'] ?? '' }}</h2>
                            <p class="mt-2 leading-7 text-slate-200">{{ $props['text'] ?? '' }}</p>
                            @if (! empty($props['buttonUrl']) || ! empty($props['button_url']))
                                <a href="{{ $props['buttonUrl'] ?? $props['button_url'] }}" class="mt-5 inline-flex rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-slate-100">{{ $props['buttonText'] ?? $props['button_label'] ?? 'Open' }}</a>
                            @endif
                        </section>
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
                            <div style="display: flex; flex-direction: {{ $props['layout'] === 'vertical' ? 'column' : 'row' }}; gap: {{ $styles['gap'] ?? '12px' }}; justify-content: {{ $styles['justifyContent'] ?? 'flex-start' }}; margin-top: {{ $styles['marginTop'] ?? '16px' }}; margin-bottom: {{ $styles['marginBottom'] ?? '16px' }};" class="flex">
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
                    @elseif ($type === 'code')
                        <pre style="{{ $styleAttr($styles) }}" class="overflow-x-auto rounded-xl bg-slate-950 p-4 text-sm leading-6 text-slate-100"><code>{{ $props['code'] ?? '' }}</code></pre>
                    @endif
                @endforeach
            @else
                <p class="text-sm text-slate-500">No content available yet.</p>
            @endif
        </div>
    </div>
</article>
