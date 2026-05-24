<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    @if (! empty($blog->featured_image))
        <div class="h-64 overflow-hidden bg-slate-100">
            <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="h-full w-full object-cover" />
        </div>
    @endif
    <div class="space-y-6 p-6">
        <div class="space-y-2">
            <p class="text-sm uppercase tracking-[0.25em] text-slate-500">{{ ucfirst($blog->status) }}</p>
            <h1 class="text-3xl font-semibold text-slate-900">{{ $blog->title }}</h1>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $blog->category?->name ?? 'Uncategorized' }}</p>
            <p class="text-sm text-slate-500">{{ $blog->excerpt }}</p>
        </div>

        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase text-slate-500">Slug</p>
                <p class="mt-2 text-sm text-slate-900">{{ $blog->slug }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase text-slate-500">Published</p>
                <p class="mt-2 text-sm text-slate-900">{{ optional($blog->published_at)->format('M d, Y H:i') ?? 'Not scheduled' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase text-slate-500">Meta title</p>
                <p class="mt-2 text-sm text-slate-900">{{ $blog->seo_title ?: '-' }}</p>
            </div>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase text-slate-500">SEO description</p>
                <p class="mt-2 text-sm text-slate-900">{{ $blog->seo_description ?: 'Not provided' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase text-slate-500">SEO keywords</p>
                <p class="mt-2 text-sm text-slate-900">{{ $blog->seo_keywords ?: 'Not provided' }}</p>
            </div>
        </div>

        <div class="space-y-4">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">Content</p>
            @php $content = is_array($blog->content_json) ? $blog->content_json : json_decode($blog->content_json ?? '[]', true); @endphp
            @if (! empty($content) && is_array($content))
                <div class="space-y-4 text-slate-700">
                    @foreach ($content as $block)
                        @php $type = $block['type'] ?? ''; @endphp
                        @if ($type === 'heading')
                            @php $level = in_array((int) ($block['level'] ?? 2), [2, 3, 4], true) ? (int) $block['level'] : 2; @endphp
                            @if ($level === 2)
                                <h2 class="text-2xl font-semibold text-slate-900">{{ $block['content'] ?? '' }}</h2>
                            @elseif ($level === 3)
                                <h3 class="text-xl font-semibold text-slate-900">{{ $block['content'] ?? '' }}</h3>
                            @else
                                <h4 class="text-lg font-semibold text-slate-900">{{ $block['content'] ?? '' }}</h4>
                            @endif
                        @elseif ($type === 'paragraph')
                            @php
                                $paragraph = $block['content'] ?? '';
                                $wordCount = str_word_count(strip_tags($paragraph));
                                $maxWords = (int) ($block['max_words'] ?? 90);
                            @endphp
                            <p class="leading-8 {{ $maxWords > 0 && $wordCount > $maxWords ? 'border-l-4 border-amber-300 pl-4' : '' }}">{{ $paragraph }}</p>
                        @elseif ($type === 'image')
                            @if (! empty($block['url']))
                                <figure class="space-y-2">
                                    <img src="{{ $block['url'] }}" alt="{{ $block['alt'] ?? '' }}" class="w-full rounded-2xl object-cover" />
                                    @if (! empty($block['caption']))
                                        <figcaption class="text-sm text-slate-500">{{ $block['caption'] }}</figcaption>
                                    @endif
                                </figure>
                            @endif
                        @elseif ($type === 'link')
                            @if (! empty($block['url']))
                                <p><a href="{{ $block['url'] }}" target="{{ $block['target'] ?? '_self' }}" class="font-semibold text-slate-900 underline">{{ $block['label'] ?? $block['url'] }}</a></p>
                            @endif
                        @elseif ($type === 'cta')
                            <div class="rounded-2xl bg-slate-900 p-6 text-white">
                                <h2 class="text-xl font-semibold">{{ $block['title'] ?? '' }}</h2>
                                <p class="mt-2 text-sm leading-6 text-slate-200">{{ $block['text'] ?? '' }}</p>
                                @if (! empty($block['button_url']))
                                    <a href="{{ $block['button_url'] }}" class="mt-4 inline-flex rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900">{{ $block['button_label'] ?? 'Open' }}</a>
                                @endif
                            </div>
                        @elseif ($type === 'code')
                            <pre class="overflow-x-auto rounded-2xl bg-slate-950 p-4 text-sm text-slate-100"><code>{{ $block['code'] ?? '' }}</code></pre>
                        @else
                            <pre class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700">{{ json_encode($block, JSON_PRETTY_PRINT) }}</pre>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500">No content available yet.</p>
            @endif
        </div>
    </div>
</div>
