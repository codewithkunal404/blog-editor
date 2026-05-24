@php
    $contentValue = old('content_json');
    if ($contentValue === null) {
        $storedContent = $blog->content_json ?? [];
        $contentValue = is_string($storedContent) ? $storedContent : json_encode($storedContent, JSON_PRETTY_PRINT);
    }

    $initialBlocks = json_decode($contentValue ?: '[]', true);
    if (! is_array($initialBlocks)) {
        $initialBlocks = [];
    }

    $componentLibrary = json_decode(file_get_contents(resource_path('data/blog-components.json')), true) ?: [];
    $componentCategories = collect($componentLibrary)->pluck('category')->unique()->values();
@endphp

<input type="hidden" name="blog_id" value="{{ $blog->id ?? '' }}" />

<section id="blog-builder" class="grid min-h-screen gap-6 lg:grid-cols-[280px_minmax(0,1fr)_320px]" data-initial='@json($initialBlocks)' data-components='@json($componentLibrary)' data-upload-url="{{ route('blogs.upload-image') }}">
    <input id="content-json" type="hidden" name="content_json" value="{{ e(json_encode($initialBlocks)) }}" />

    <!-- Left Sidebar - Components -->
    <aside class="max-h-screen overflow-y-auto rounded-xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm lg:sticky lg:top-0">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-900">Components Library</label>
                <p class="mt-1 text-xs text-slate-500">Drag or click to add</p>
            </div>
            <input id="component-search" type="search" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm placeholder-slate-400 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100" placeholder="Search components..." />
            <select id="component-category" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100">
                <option value="all">All categories</option>
                @foreach ($componentCategories as $componentCategory)
                    <option value="{{ $componentCategory }}">{{ ucfirst($componentCategory) }}</option>
                @endforeach
            </select>
        </div>
        <div id="component-list" class="mt-5 space-y-2 pr-2"></div>
    </aside>

    <!-- Main Content Area -->
    <main class="min-w-0 space-y-6">
        <!-- Blog Metadata Section -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 flex items-center gap-2 text-lg font-bold text-slate-900">
                <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Blog Information
            </h3>
            <div class="space-y-5">
                <div class="grid gap-5 lg:grid-cols-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Post Title</label>
                        <input name="title" value="{{ old('title', $blog->title ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="Enter an engaging title" required />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">URL Slug</label>
                        <input name="slug" value="{{ old('slug', $blog->slug ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="url-slug-example" required />
                    </div>
                </div>
                <div class="grid gap-5 lg:grid-cols-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Category</label>
                        <select name="category_id" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                            <option value="">Select a category</option>
                            @foreach ($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ (string) old('category_id', $blog->category_id ?? '') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Excerpt</label>
                        <input name="excerpt" value="{{ old('excerpt', $blog->excerpt ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="Brief summary of the post" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog Content Blocks Section -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col h-[600px]">
            <div class="mb-5 flex items-center justify-between gap-3">
                <h3 class="flex items-center gap-2 text-lg font-bold text-slate-900">
                    <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path>
                    </svg>
                    Page Content
                </h3>
                <button type="button" id="clear-blocks" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Clear All
                </button>
            </div>
            <div id="block-list" class="flex-1 min-h-0 space-y-3 rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 overflow-y-auto"></div>
        </div>
    </main>

    <!-- Right Sidebar - Publishing & SEO -->
    <aside class="max-h-screen overflow-y-auto lg:sticky lg:top-0">
        <div class="space-y-5 rounded-xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-6 shadow-sm">
            <!-- Publishing Section -->
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                    <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Publish
                </h3>
                <div class="space-y-3">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Status</label>
                        <select name="status" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100">
                            <option value="draft" {{ old('status', $blog->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $blog->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Publish Date</label>
                        <input name="published_at" type="datetime-local" value="{{ old('published_at', optional($blog->published_at)->format('Y-m-d\TH:i') ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100" />
                    </div>
                </div>
            </div>

            <hr class="border-slate-200" />

            <!-- Featured Image Section -->
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                    <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Featured Image
                </h3>
                <div class="space-y-2">
                    <input name="featured_image" value="{{ old('featured_image', $blog->featured_image ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition placeholder-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100" placeholder="https://example.com/image.jpg" />
                    <input type="file" accept="image/*" data-target-name="featured_image" class="local-image-upload w-full text-xs text-slate-500" />
                </div>
            </div>

            <hr class="border-slate-200" />

            <!-- SEO Section -->
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                    <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    SEO Settings
                </h3>
                <div class="space-y-3">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">SEO Title</label>
                        <input name="seo_title" value="{{ old('seo_title', $blog->seo_title ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition placeholder-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100" placeholder="SEO optimized title" />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Meta Description</label>
                        <textarea name="seo_description" rows="2" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition placeholder-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100" placeholder="Brief description for search engines">{{ old('seo_description', $blog->seo_description ?? '') }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Keywords</label>
                        <input name="seo_keywords" value="{{ old('seo_keywords', $blog->seo_keywords ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition placeholder-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100" placeholder="keyword1, keyword2, keyword3" />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">SEO Image</label>
                        <input name="seo_image" value="{{ old('seo_image', $blog->seo_image ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition placeholder-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100" placeholder="https://example.com/seo.jpg" />
                        <input type="file" accept="image/*" data-target-name="seo_image" class="local-image-upload w-full text-xs text-slate-500" />
                    </div>
                </div>
            </div>
        </div>
    </aside>
</section>

<!-- Block Settings Modal -->
<div id="block-settings-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4 py-6 backdrop-blur-sm">
    <div class="max-h-[90vh] w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Block Settings</h2>
                <p id="block-settings-title" class="mt-1 text-sm text-slate-500"></p>
            </div>
            <button type="button" id="close-block-settings" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 active:scale-95" aria-label="Close settings">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="block-settings-body" class="max-h-[70vh] overflow-y-auto px-6 py-5 text-sm text-slate-600"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('blog-builder');
    if (!root || root.dataset.ready) return;
    root.dataset.ready = 'true';

    const input = document.getElementById('content-json');
    const componentList = document.getElementById('component-list');
    const blockList = document.getElementById('block-list');
    const settingsModal = document.getElementById('block-settings-modal');
    const settingsBody = document.getElementById('block-settings-body');
    const settingsTitle = document.getElementById('block-settings-title');
    const closeSettingsButton = document.getElementById('close-block-settings');
    const search = document.getElementById('component-search');
    const category = document.getElementById('component-category');
    const componentLibrary = JSON.parse(root.dataset.components || '[]');
    let blocks = Array.isArray(JSON.parse(root.dataset.initial || '[]')) ? JSON.parse(root.dataset.initial || '[]') : [];
    let selected = blocks.length ? 0 : null;
    let settingsIndex = null;
    let dragIndex = null;

    const templates = componentLibrary.map((item) => ({
        id: item.id,
        type: item.type,
        name: item.label,
        icon: item.icon,
        category: item.category,
        preview: item.preview,
        propertyControls: item.propertyControls || [],
        styleControls: item.styleControls || [],
        defaults: {
            componentId: item.id,
            type: item.type,
            label: item.label,
            props: item.defaultProps || {},
            styles: item.defaultStyles || {},
        },
    }));

    const clone = (value) => JSON.parse(JSON.stringify(value));
    const save = () => { input.value = JSON.stringify(blocks); };
    const escapeHtml = (value) => String(value || '').replace(/[&<>"']/g, (c) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
    const shortText = (text) => String(text || '').replace(/\s+/g, ' ').trim().slice(0, 90);
    const fieldClass = 'w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none focus:border-slate-400';
    blocks = blocks.map(normalizeBlock);

    function normalizeBlock(block) {
        if (block.props && block.styles) return block;
        const props = { ...block };
        delete props.type;
        delete props.styles;
        delete props.componentId;
        if (props.content && !props.text) props.text = props.content;
        if (props.label && block.type === 'link' && !props.text) props.text = props.label;
        if (props.button_label && !props.buttonText) props.buttonText = props.button_label;
        if (props.button_url && !props.buttonUrl) props.buttonUrl = props.button_url;
        return {
            componentId: block.componentId || block.type,
            type: block.type,
            label: block.label || block.type,
            props,
            styles: block.styles || {},
        };
    }

    function styleString(styles) {
        return Object.entries(styles || {})
            .filter(([, value]) => value !== '')
            .map(([key, value]) => `${key.replace(/[A-Z]/g, (match) => '-' + match.toLowerCase())}:${value}`)
            .join(';');
    }

    function renderComponents() {
        const query = search.value.toLowerCase();
        const activeCategory = category.value;
        componentList.innerHTML = '';
        templates
            .filter((item) => (activeCategory === 'all' || item.category === activeCategory) && item.name.toLowerCase().includes(query))
            .forEach((item) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.draggable = true;
                button.className = 'flex w-full items-center justify-between rounded-lg border border-slate-200 bg-white px-3 py-3 text-left text-sm font-semibold text-slate-800 hover:border-slate-400';
                button.innerHTML = `<span class="flex items-center gap-2"><span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px]">${escapeHtml(item.icon || '')}</span>${escapeHtml(item.name)}</span><span class="text-xs font-medium uppercase text-slate-400">${escapeHtml(item.category)}</span>`;
                button.addEventListener('click', () => addBlock(item.id));
                button.addEventListener('dragstart', (event) => event.dataTransfer.setData('component-id', item.id));
                componentList.appendChild(button);
            });
    }

    function addBlock(componentId, index = blocks.length) {
        const template = templates.find((item) => item.id === componentId);
        if (!template) return;
        blocks.splice(index, 0, clone(template.defaults));
        selected = index;
        renderAll();
    }

    function moveBlock(from, to) {
        if (from === null || to === null || from === to) return;
        const [block] = blocks.splice(from, 1);
        blocks.splice(to, 0, block);
        selected = to;
        settingsIndex = settingsIndex === from ? to : settingsIndex;
        renderAll();
    }

    function blockPreview(block) {
        const props = block.props || {};
        const styles = styleString(block.styles || {});
        if (block.type === 'heading') return `<h${props.level || 2} style="${styles}" class="font-semibold text-slate-900">${escapeHtml(props.text || 'Heading')}</h${props.level || 2}>`;
        if (block.type === 'paragraph') return `<p style="${styles}" class="leading-7 text-slate-700">${escapeHtml(shortText(props.text) || 'Paragraph text')}</p>`;
        if (block.type === 'image') return props.url ? `<img src="${escapeHtml(props.url)}" alt="${escapeHtml(props.alt)}" style="${styles}" class="h-36 w-full rounded-lg object-cover">` : '<div class="rounded-lg bg-slate-100 p-8 text-center text-sm text-slate-500">Image URL needed</div>';
        if (block.type === 'image_grid') return `<div class="grid" style="grid-template-columns:repeat(${Number(props.columns || 2)},minmax(0,1fr));gap:${escapeHtml(block.styles?.gap || '16px')};${styles}">${(props.images || '').split('\n').filter(Boolean).slice(0, 4).map((url) => `<img src="${escapeHtml(url)}" class="h-24 w-full rounded-lg object-cover">`).join('') || '<div class="rounded-lg bg-slate-100 p-6 text-center text-sm text-slate-500">Add image URLs, one per line</div>'}</div>`;
        if (block.type === 'quote') return `<blockquote style="${styles}" class="text-slate-700">${escapeHtml(shortText(props.text) || 'Quote')}${props.author ? `<footer class="mt-2 text-sm text-slate-500">${escapeHtml(props.author)}</footer>` : ''}</blockquote>`;
        if (block.type === 'button') return `<a style="${styles}" class="inline-flex font-semibold">${escapeHtml(props.text || 'Button')}</a>`;
        if (block.type === 'social_links') {
            const links = (props.links || '').split('\n').filter(Boolean);
            const sizeMap = { small: '28px', medium: '36px', large: '44px', 'extra-large': '52px' };
            const size = sizeMap[props.size || 'medium'];
            const animationClass = {
                'hover-scale': 'hover:scale-110',
                'hover-rotate': 'hover:rotate-12',
                'hover-lift': 'hover:-translate-y-1',
                'pulse': 'animate-pulse',
                'bounce': 'animate-bounce',
                'none': ''
            }[props.animation || 'hover-scale'];
            
            const svgIcons = {
                facebook: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
                twitter: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 002.856-3.915 10 10 0 01-2.856.973 5 5 0 00-8.66 4.59 14.23 14.23 0 01-10.337-5.196 5 5 0 001.551 6.759 5 5 0 01-2.265-.616v.06a5 5 0 004.009 4.905 5 5 0 01-2.26.086 5 5 0 004.666 3.472 10.029 10.029 0 01-6.2 2.14A14.233 14.233 0 0023 5.892a10.009 10.009 0 001.953-5.322z"/></svg>',
                linkedin: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"/></svg>',
                instagram: '<svg fill="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/></svg>',
                github: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v 3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>',
                youtube: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
                whatsapp: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.47-.148-.67.15-.23.297-.921 1.165-.949 1.404-.029.239-.097.36-.609.878-.513.519-1.921.196-2.465-.086-.545-.282-2.236-1.122-3.476-2.229-1.241-1.106-1.977-2.469-2.205-2.766-.228-.297-.024-.458.171-.606.175-.149.389-.39.584-.585.195-.195.259-.334.388-.557.129-.223.065-.417-.033-.586-.099-.17-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a6.963 6.963 0 00-6.961 6.961 6.968 6.968 0 006.961 6.961 6.968 6.968 0 006.961-6.961 6.963 6.963 0 00-6.957-6.961m0-1.261a8.23 8.23 0 018.23 8.23 8.23 8.23 0 01-8.23 8.23 8.23 8.23 0 01-8.23-8.23 8.23 8.23 0 018.23-8.23"/></svg>'
            };
            
            return `<div style="display:flex;flex-direction:${props.layout === 'vertical' ? 'column' : 'row'};gap:${styles.gap || '12px'};justify-content:${styles.justifyContent || 'flex-start'};${styles}" class="flex">${links.map((link) => {
                const [platform, url] = link.split('|').map(s => s.trim());
                const platformLower = platform.toLowerCase();
                const svg = svgIcons[platformLower] || '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>';
                const label = props.showLabel === 'yes' ? `<span style="font-size:12px;margin-top:4px;">${escapeHtml(platform)}</span>` : '';
                return `<a href="${escapeHtml(url)}" target="_blank" rel="noopener noreferrer" title="${escapeHtml(platform)}" style="width:${size};height:${size};display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:${props.borderRadius || '50%'};background:${props.backgroundColor || '#f3f4f6'};color:${props.iconColor || '#374151'};text-decoration:none;transition:all 0.3s ease;cursor:pointer;" class="group ${animationClass}" onmouseover="this.style.background='${props.hoverBackgroundColor || '#111827'}';this.style.color='${props.hoverIconColor || '#ffffff'}'" onmouseout="this.style.background='${props.backgroundColor || '#f3f4f6'}';this.style.color='${props.iconColor || '#374151'}'"><div style="width:60%;height:60%;display:flex;align-items:center;justify-content:center;">${svg}</div>${label}</a>`;
            }).join('')}</div>`;
        }
        if (block.type === 'list') {
            const tag = props.ordered === 'yes' ? 'ol' : 'ul';
            return `<${tag} style="${styles}" class="${props.ordered === 'yes' ? 'list-decimal' : 'list-disc'} space-y-1 pl-5">${(props.items || '').split('\n').filter(Boolean).slice(0, 4).map((item) => `<li>${escapeHtml(item)}</li>`).join('')}</${tag}>`;
        }
        if (block.type === 'section') return `<section style="${styles}" class="rounded-lg"><h3 class="font-semibold text-slate-900">${escapeHtml(props.title || 'Section')}</h3><p class="mt-2 text-sm leading-6 text-slate-600">${escapeHtml(shortText(props.content || ''))}</p></section>`;
        if (block.type === 'divider') return `<hr style="${styles}" class="border-slate-200">`;
        if (block.type === 'grid') return `<div style="grid-template-columns:repeat(${Number(props.columns || 2)},minmax(0,1fr));gap:${escapeHtml(props.gap || '20px')};${styles}" class="grid">${(props.items || '').split('\n').filter(Boolean).slice(0, 6).map((item) => `<div class="rounded-lg border border-slate-200 bg-white p-3 text-sm">${escapeHtml(item)}</div>`).join('')}</div>`;
        if (block.type === 'row') return `<div style="grid-template-columns:repeat(${Number(props.columns || 3)},minmax(0,1fr));${styles}" class="grid">${(props.items || '').split('\n').filter(Boolean).slice(0, 6).map((item) => `<div class="rounded-lg border border-slate-200 bg-white p-3 text-sm">${escapeHtml(item)}</div>`).join('')}</div>`;
        if (block.type === 'link') return `<a class="font-semibold text-slate-900 underline">${escapeHtml(props.text || props.label || 'Link label')}</a>`;
        if (block.type === 'cta') return `<div style="${styles}" class="rounded-lg bg-slate-900 p-4 text-white"><p class="font-semibold">${escapeHtml(props.title || 'CTA title')}</p><p class="mt-1 text-sm text-slate-200">${escapeHtml(shortText(props.text))}</p></div>`;
        if (block.type === 'code') return `<pre style="${styles}" class="overflow-hidden rounded-lg bg-slate-950 p-3 text-xs text-slate-100">${escapeHtml(props.code).slice(0, 220)}</pre>`;
        return `<pre class="rounded-lg bg-slate-100 p-3 text-xs">${escapeHtml(JSON.stringify(block, null, 2))}</pre>`;
    }

    function renderBlocks() {
        blockList.innerHTML = '';
        if (!blocks.length) {
            blockList.innerHTML = '<div class="flex h-full items-center justify-center rounded-lg bg-white text-sm text-slate-500">Drag components here or click a component to add it.</div>';
            return;
        }
        blocks.forEach((rawBlock, index) => {
            const block = normalizeBlock(rawBlock);
            blocks[index] = block;
            const title = block.label || templates.find((item) => item.id === block.componentId)?.name || block.type || 'Block';
            const item = document.createElement('article');
            item.draggable = true;
            item.className = `rounded-lg border bg-white p-4 shadow-sm ${selected === index ? 'border-slate-900 ring-2 ring-slate-200' : 'border-slate-200'}`;
            item.innerHTML = `<div class="mb-3 flex items-center justify-between gap-3">
                <div class="text-xs font-semibold uppercase text-slate-500">${index + 1}. ${escapeHtml(title)}</div>
                <div class="flex gap-2">
                    <button type="button" data-action="up" data-index="${index}" class="rounded-md border border-slate-200 px-2 py-1 text-xs">Up</button>
                    <button type="button" data-action="down" data-index="${index}" class="rounded-md border border-slate-200 px-2 py-1 text-xs">Down</button>
                    <button type="button" data-action="settings" data-index="${index}" class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50" aria-label="Open block settings" title="Settings">
                        <svg aria-hidden="true" viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.3 4.3c.4-1.7 2.9-1.7 3.4 0 .2.9 1.3 1.3 2.1.8 1.5-.9 3.2.8 2.3 2.3-.5.8-.1 1.9.8 2.1 1.7.4 1.7 2.9 0 3.4-.9.2-1.3 1.3-.8 2.1.9 1.5-.8 3.2-2.3 2.3-.8-.5-1.9-.1-2.1.8-.4 1.7-2.9 1.7-3.4 0-.2-.9-1.3-1.3-2.1-.8-1.5.9-3.2-.8-2.3-2.3.5-.8.1-1.9-.8-2.1-1.7-.4-1.7-2.9 0-3.4.9-.2 1.3-1.3.8-2.1-.9-1.5.8-3.2 2.3-2.3.8.5 1.9.1 2.1-.8Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                    <button type="button" data-action="delete" data-index="${index}" class="rounded-md border border-rose-200 px-2 py-1 text-xs text-rose-700">Delete</button>
                </div>
            </div>${blockPreview(block)}`;
            item.addEventListener('click', (event) => {
                if (event.target.closest('button,input,textarea,select,label')) return;
                selected = index;
                renderAll();
            });
            item.addEventListener('dragstart', () => { dragIndex = index; });
            item.addEventListener('dragover', (event) => event.preventDefault());
            item.addEventListener('drop', (event) => {
                event.preventDefault();
                const componentId = event.dataTransfer.getData('component-id');
                if (componentId) addBlock(componentId, index);
                else moveBlock(dragIndex, index);
                dragIndex = null;
            });
            blockList.appendChild(item);
        });
    }

    function fieldsFor(block) {
        const template = templates.find((item) => item.id === block.componentId) || templates.find((item) => item.type === block.type);
        if (template) return [...template.propertyControls, ...template.styleControls];
        return [];
    }

    function createField(field, block) {
        const wrap = document.createElement('label');
        wrap.className = 'mb-4 block space-y-2 text-sm font-semibold text-slate-700';
        wrap.append(field.label);
        let control;
        if (field.type === 'textarea') {
            control = document.createElement('textarea');
            control.rows = field.rows || 5;
        } else if (field.type === 'select') {
            control = document.createElement('select');
            (field.options || []).forEach((option) => {
                const opt = document.createElement('option');
                opt.value = option;
                opt.textContent = field.key === 'level' ? `H${option}` : option;
                control.appendChild(opt);
            });
        } else {
            control = document.createElement('input');
            control.type = field.type || 'text';
        }
        control.className = fieldClass;
        
        // Ensure the group exists on the block
        if (!block[field.group]) {
            block[field.group] = {};
        }
        
        control.value = block[field.group][field.key] ?? '';
        
        const updateField = () => {
            // Ensure the group exists before updating
            if (!block[field.group]) {
                block[field.group] = {};
            }
            
            let value = control.value;
            if (field.type === 'number') {
                value = Number(value);
            }
            
            block[field.group][field.key] = value;
            
            // Auto-set font size based on heading level
            if (field.key === 'level' && block.type === 'heading') {
                const fontSizes = {
                    '1': '2.25rem',  // 36px - H1
                    '2': '1.875rem', // 30px - H2
                    '3': '1.5rem',   // 24px - H3
                    '4': '1.25rem',  // 20px - H4
                    '5': '1.125rem', // 18px - H5
                    '6': '1rem',     // 16px - H6
                };
                block.styles.fontSize = fontSizes[value] || '1.875rem';
            }
            
            // Update the blocks array with the modified block
            if (settingsIndex !== null && blocks[settingsIndex]) {
                blocks[settingsIndex] = block;
            }
            
            save();
            renderBlocks();
            
            // Re-open settings to show updated font size
            if (field.key === 'level' && block.type === 'heading') {
                openSettings(settingsIndex);
            }
        };
        control.addEventListener('input', updateField);
        control.addEventListener('change', updateField);
        wrap.appendChild(control);
        
        // Special handling for image_grid images field
        if (block.type === 'image_grid' && field.key === 'images') {
            const imageContainer = document.createElement('div');
            imageContainer.className = 'mt-3 space-y-3';
            
            // Display current images
            const displayImages = () => {
                const imageList = document.createElement('div');
                imageList.className = 'space-y-2';
                
                const images = (block.props.images || '').split('\n').filter(Boolean);
                const imageCount = images.length;
                
                console.log('Displaying images:', images);
                
                images.slice(0, 5).forEach((url, idx) => {
                    const imageItem = document.createElement('div');
                    imageItem.className = 'flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 p-2';
                    imageItem.innerHTML = `
                        <img src="${escapeHtml(url)}" alt="Grid image ${idx + 1}" class="h-12 w-12 rounded object-cover" onerror="console.error('Image failed to load:', '${escapeHtml(url)}')">
                        <div class="flex-1 truncate text-xs text-slate-600">${escapeHtml(url.split('/').pop())}</div>
                        <button type="button" class="rounded px-2 py-1 text-xs text-rose-600 hover:bg-rose-50" data-remove-image="${idx}">Remove</button>
                    `;
                    imageList.appendChild(imageItem);
                });
                
                const counter = document.createElement('p');
                counter.className = 'text-xs text-slate-500';
                counter.textContent = `${imageCount}/5 images added`;
                imageList.appendChild(counter);
                
                return imageList;
            };
            
            imageContainer.appendChild(displayImages());
            wrap.appendChild(imageContainer);
            
            // File upload input
            const uploadInput = document.createElement('input');
            uploadInput.type = 'file';
            uploadInput.accept = 'image/*';
            uploadInput.multiple = true;
            uploadInput.className = 'w-full text-xs text-slate-500';
            uploadInput.addEventListener('change', async () => {
                if (!uploadInput.files.length) return;
                
                const images = (block.props.images || '').split('\n').filter(Boolean);
                const remainingSlots = 5 - images.length;
                
                if (remainingSlots <= 0) {
                    alert('Maximum 5 images allowed per grid');
                    uploadInput.value = '';
                    return;
                }
                
                const filesToUpload = Array.from(uploadInput.files).slice(0, remainingSlots);
                
                try {
                    for (const file of filesToUpload) {
                        const url = await uploadImage(file);
                        images.push(url);
                    }
                    
                    block.props.images = images.join('\n');
                    control.value = block.props.images;
                    
                    if (settingsIndex !== null && blocks[settingsIndex]) {
                        blocks[settingsIndex] = block;
                    }
                    
                    save();
                    
                    // Update the display
                    const oldContainer = wrap.querySelector('div:last-of-type');
                    if (oldContainer) oldContainer.remove();
                    
                    const newContainer = document.createElement('div');
                    newContainer.className = 'mt-3 space-y-3';
                    newContainer.appendChild(displayImages());
                    wrap.appendChild(newContainer);
                    
                    renderBlocks();
                } catch (error) {
                    console.error('Upload error:', error);
                    alert('Failed to upload image: ' + (error.message || 'Unknown error'));
                }
                
                uploadInput.value = '';
            });
            wrap.appendChild(uploadInput);
            
            // Handle image removal
            wrap.addEventListener('click', (e) => {
                const removeBtn = e.target.closest('[data-remove-image]');
                if (!removeBtn) return;
                
                const idx = Number(removeBtn.dataset.removeImage);
                const images = (block.props.images || '').split('\n').filter(Boolean);
                images.splice(idx, 1);
                
                block.props.images = images.join('\n');
                control.value = block.props.images;
                
                if (settingsIndex !== null && blocks[settingsIndex]) {
                    blocks[settingsIndex] = block;
                }
                
                save();
                
                // Update the display
                const oldContainer = wrap.querySelector('div:last-of-type');
                if (oldContainer) oldContainer.remove();
                
                const newContainer = document.createElement('div');
                newContainer.className = 'mt-3 space-y-3';
                newContainer.appendChild(displayImages());
                wrap.appendChild(newContainer);
                
                renderBlocks();
            });
        } else if (field.upload) {
            const upload = document.createElement('input');
            upload.type = 'file';
            upload.accept = 'image/*';
            upload.className = 'mt-2 w-full text-xs text-slate-500';
            upload.addEventListener('change', async () => {
                if (!upload.files.length) return;
                control.value = await uploadImage(upload.files[0]);
                updateField();
            });
            wrap.appendChild(upload);
        }
        return wrap;
    }

    function openSettings(index) {
        if (!blocks[index]) return;
        const block = normalizeBlock(blocks[index]);
        blocks[index] = block;
        settingsIndex = index;
        settingsTitle.textContent = `${index + 1}. ${block.label || block.type || 'Block'}`;
        settingsBody.innerHTML = '';
        fieldsFor(block).forEach((field) => settingsBody.appendChild(createField(field, block)));
        if (block.type === 'paragraph') {
            const words = (block.props.text || '').trim().split(/\s+/).filter(Boolean).length;
            const notice = document.createElement('p');
            notice.className = `mt-3 rounded-lg px-3 py-2 text-xs ${words > (block.props.maxWords || 90) ? 'bg-amber-50 text-amber-800' : 'bg-emerald-50 text-emerald-800'}`;
            notice.textContent = `${words}/${block.props.maxWords || 90} words. Keep paragraphs focused, short, and easy to scan.`;
            settingsBody.appendChild(notice);
        }
        settingsModal.classList.remove('hidden');
        settingsModal.classList.add('flex');
    }

    function closeSettings() {
        settingsIndex = null;
        settingsModal.classList.add('hidden');
        settingsModal.classList.remove('flex');
        settingsBody.innerHTML = '';
    }

    async function uploadImage(file) {
        const form = new FormData();
        form.append('image', file);
        const token = document.querySelector('input[name="_token"]')?.value || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        try {
            const response = await fetch(root.dataset.uploadUrl, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json' 
                },
                body: form,
            });
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error('Upload response error:', errorData);
                throw new Error(errorData.message || `Upload failed with status ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Upload response:', data);
            
            // Use relative URL for better compatibility
            const imageUrl = data.relative_url || data.url;
            console.log('Image URL to use:', imageUrl);
            return imageUrl;
        } catch (error) {
            console.error('Upload error:', error);
            throw error;
        }
    }

    function renderAll() {
        save();
        renderComponents();
        renderBlocks();
    }

    blockList.addEventListener('dragover', (event) => event.preventDefault());
    blockList.addEventListener('drop', (event) => {
        event.preventDefault();
        const componentId = event.dataTransfer.getData('component-id');
        if (componentId) addBlock(componentId);
    });
    blockList.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-action]');
        if (!button) return;
        const index = Number(button.dataset.index);
        if (button.dataset.action === 'delete') {
            blocks.splice(index, 1);
            selected = blocks.length ? Math.max(0, index - 1) : null;
            closeSettings();
        }
        if (button.dataset.action === 'settings') {
            selected = index;
            openSettings(index);
        }
        if (button.dataset.action === 'up' && index > 0) moveBlock(index, index - 1);
        if (button.dataset.action === 'down' && index < blocks.length - 1) moveBlock(index, index + 1);
        renderAll();
    });
    document.getElementById('clear-blocks').addEventListener('click', () => { blocks = []; selected = null; closeSettings(); renderAll(); });
    closeSettingsButton.addEventListener('click', closeSettings);
    settingsModal.addEventListener('click', (event) => {
        if (event.target === settingsModal) closeSettings();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !settingsModal.classList.contains('hidden')) closeSettings();
    });
    document.querySelectorAll('.local-image-upload').forEach((upload) => {
        upload.addEventListener('change', async () => {
            if (!upload.files.length) return;
            const target = document.querySelector(`[name="${upload.dataset.targetName}"]`);
            if (!target) return;
            target.value = await uploadImage(upload.files[0]);
        });
    });
    search.addEventListener('input', renderComponents);
    category.addEventListener('change', renderComponents);
    renderAll();
});
</script>
