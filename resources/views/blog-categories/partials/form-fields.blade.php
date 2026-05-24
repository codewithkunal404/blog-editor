<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-2">
        <label class="block text-sm font-semibold text-slate-700">Name</label>
        <input name="name" value="{{ old('name', $category->name ?? '') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200" placeholder="Technology" required />
    </div>
    <div class="space-y-2">
        <label class="block text-sm font-semibold text-slate-700">Slug</label>
        <input name="slug" value="{{ old('slug', $category->slug ?? '') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200" placeholder="technology" required />
    </div>
</div>

<div class="space-y-2">
    <label class="block text-sm font-semibold text-slate-700">Description</label>
    <textarea name="description" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200" placeholder="What kind of posts belong in this category?">{{ old('description', $category->description ?? '') }}</textarea>
</div>
