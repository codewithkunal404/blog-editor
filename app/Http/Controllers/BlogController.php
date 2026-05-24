<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(protected BlogService $service)
    {
    }

    public function index(): View
    {
        $blogs = $this->service->paginate(12);

        return view('blog.index', compact('blogs'));
    }

    public function create(): View
    {
        $categories = \App\Models\BlogCategory::all();
        return view('blog.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateBlog($request);

        $this->service->create($data);

        return Redirect::route('blogs.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog): View
    {
        $categories = \App\Models\BlogCategory::all();
        return view('blog.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $data = $this->validateBlog($request, $blog->id);

        $this->service->update($blog, $data);

        return Redirect::route('blogs.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $this->service->delete($blog);

        return Redirect::route('blogs.index')->with('success', 'Blog post deleted successfully.');
    }

    public function show(Blog $blog): View
    {
        return view('blog.show', compact('blog'));
    }

    public function publicShow($slug): View
    {
        $blog = Blog::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return view('blog.public-show', compact('blog'));
    }

    public function preview(Request $request): View
    {
        $blogId = $request->input('blog_id') ? (int) $request->input('blog_id') : null;
        $data = $this->validateBlog($request, $blogId, true);

        $blog = new Blog($data);
        $blog->published_at = isset($data['published_at']) ? Carbon::parse($data['published_at']) : null;

        return view('blog.preview', ['blog' => $blog, 'previewMode' => true]);
    }

    public function livePreview(Blog $blog): View
    {
        return view('blog.preview', ['blog' => $blog, 'previewMode' => false]);
    }

    protected function validateBlog(Request $request, ?int $blogId = null, bool $preview = false): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug' . ($blogId ? ",$blogId" : '')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'featured_image' => ['nullable', 'string', 'max:1000'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'seo_image' => ['nullable', 'string', 'max:1000'],
            'content_json' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
        ];

        if ($preview) {
            return $request->validate($rules);
        }

        return $request->validate($rules);
    }

    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => ['required', 'image', 'max:5120'], // 5MB max
            ]);

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('blog-images', $filename, 'public');

            // Construct the URL properly
            $relativeUrl = '/storage/' . $path;

            return response()->json([
                'url' => $relativeUrl,
                'relative_url' => $relativeUrl,
                'path' => $path,
                'filename' => $filename,
                'success' => true,
            ]);
        } catch (\Exception $e) {
            \Log::error('Image upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getFile() . ':' . $e->getLine(),
            ], 422);
        }
    }
}
