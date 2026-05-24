<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $categories = collect([
            ['name' => 'Laravel', 'slug' => 'laravel', 'description' => 'Laravel framework guides and implementation notes.'],
            ['name' => 'Design', 'slug' => 'design', 'description' => 'Interface, layout, and content design ideas.'],
            ['name' => 'Performance', 'slug' => 'performance', 'description' => 'Web performance optimization and best practices.'],
            ['name' => 'Development', 'slug' => 'development', 'description' => 'Development workflows and tools.'],
        ])->mapWithKeys(function (array $category) {
            $model = BlogCategory::firstOrCreate(['slug' => $category['slug']], $category);
            return [$category['slug'] => $model->id];
        });

        $items = [
            // Blog 1: Complete Component Showcase
            [
                'title' => 'Complete Guide to Building a Modern Blog Platform',
                'slug' => 'complete-guide-modern-blog-platform',
                'category_id' => $categories['laravel'],
                'excerpt' => 'A comprehensive guide showcasing all available components for building a professional blog platform with Laravel and Tailwind CSS.',
                'featured_image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1200&q=80',
                'seo_title' => 'Modern Blog Platform with Laravel - Complete Guide',
                'seo_description' => 'Learn how to build a professional blog platform using Laravel, structured content blocks, and reusable components with full SEO support.',
                'seo_keywords' => 'laravel,blog platform,content management,structured content,page builder',
                'seo_image' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=1200&q=80',
                'content_json' => [
                    $this->block('heading', 'heading', 'Main Title', ['level' => 1, 'text' => 'Building a Professional Blog Platform'], ['fontSize' => '48px', 'marginBottom' => '24px']),
                    $this->block('paragraph', 'paragraph', 'Introduction', ['text' => 'Modern content management requires flexibility, scalability, and ease of use. This guide walks you through building a blog platform that separates content from presentation, allowing editors to focus on writing while developers maintain clean, maintainable code. We\'ll explore structured content blocks, SEO optimization, image management, and publishing workflows.']),
                    
                    // Section 1: Architecture Overview
                    $this->block('heading', 'heading', 'Section Heading', ['level' => 2, 'text' => 'Architecture Overview'], ['fontSize' => '32px', 'marginTop' => '32px']),
                    $this->block('section', 'section', 'Info Section', ['title' => 'Core Principles', 'content' => 'The foundation of a scalable blog platform rests on three principles: separation of concerns (content vs. presentation), reusable components (DRY principle), and structured metadata (SEO and publishing). By storing content as JSON blocks, you gain flexibility without sacrificing performance or maintainability.']),
                    
                    // Grid of key features
                    $this->block('grid_section', 'grid', 'Features Grid', ['columns' => 2, 'gap' => '20px', 'items' => "Structured JSON Content Blocks\nFlexible Component Library\nBuilt-in SEO Metadata\nDraft and Publishing Workflow\nImage Upload and Management\nCategory Organization\nResponsive Design System\nPerformance Optimized"]),
                    
                    // Section 2: Content Components
                    $this->block('heading', 'heading', 'Section Heading', ['level' => 2, 'text' => 'Content Components'], ['fontSize' => '32px', 'marginTop' => '32px']),
                    $this->block('paragraph', 'paragraph', 'Paragraph', ['text' => 'The component library provides editors with a rich set of tools to create engaging content. Each component is self-contained, with its own properties and styling options. This modular approach ensures consistency while allowing creative flexibility.']),
                    
                    // Row layout for component types
                    $this->block('row', 'row', 'Component Types', ['columns' => 4, 'items' => "Text Components\nMedia Components\nLayout Components\nInteractive Components"]),
                    
                    // Section 3: Text Components
                    $this->block('heading', 'heading', 'Subsection', ['level' => 3, 'text' => 'Text Components'], ['fontSize' => '24px', 'marginTop' => '24px']),
                    $this->block('list', 'list', 'Text Types', ['items' => "Headings (H1-H6) with customizable styling\nParagraphs with line height and color control\nQuotes with author attribution\nLists (ordered and unordered)\nCode blocks with syntax highlighting"]),
                    
                    // Section 4: Media Components
                    $this->block('heading', 'heading', 'Subsection', ['level' => 3, 'text' => 'Media Components'], ['fontSize' => '24px', 'marginTop' => '24px']),
                    $this->block('paragraph', 'paragraph', 'Paragraph', ['text' => 'Media components handle images and galleries with responsive design. Upload images directly from the editor, add captions, and arrange them in flexible grid layouts.']),
                    
                    $this->block('image_grid', 'image_grid', 'Image Gallery', ['columns' => 3, 'images' => "https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80\nhttps://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=600&q=80\nhttps://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=600&q=80"], ['imageHeight' => '200px', 'borderRadius' => '12px']),
                    
                    // Section 5: Layout Components
                    $this->block('heading', 'heading', 'Subsection', ['level' => 3, 'text' => 'Layout Components'], ['fontSize' => '24px', 'marginTop' => '24px']),
                    $this->block('paragraph', 'paragraph', 'Paragraph', ['text' => 'Layout components provide structure and organization. Use sections for grouped content, grids for multi-column layouts, and rows for horizontal arrangements.']),
                    
                    // Section 6: Interactive Components
                    $this->block('heading', 'heading', 'Subsection', ['level' => 3, 'text' => 'Interactive Components'], ['fontSize' => '24px', 'marginTop' => '24px']),
                    $this->block('list', 'list', 'Interactive Types', ['items' => "Buttons with customizable styling and links\nSocial media links with animations\nCalls-to-action with background colors\nDividers for visual separation"]),
                    
                    // Social Links Example
                    $this->block('social_links', 'social_links', 'Social Links', ['links' => "facebook|https://facebook.com/yourpage\ntwitter|https://twitter.com/yourhandle\nlinkedin|https://linkedin.com/company/yourcompany\ngithub|https://github.com/yourprofile\nyoutube|https://youtube.com/@yourchannel", 'layout' => 'horizontal', 'size' => 'medium', 'animation' => 'hover-scale', 'backgroundColor' => '#f3f4f6', 'hoverBackgroundColor' => '#2563eb', 'iconColor' => '#374151', 'hoverIconColor' => '#ffffff']),
                    
                    // Section 7: Best Practices
                    $this->block('heading', 'heading', 'Section Heading', ['level' => 2, 'text' => 'Best Practices'], ['fontSize' => '32px', 'marginTop' => '32px']),
                    $this->block('section', 'section', 'Best Practices', ['title' => 'Editor Guidelines', 'content' => 'Follow these guidelines to create consistent, professional content: Use H1 only once per article, keep paragraphs between 50-150 words, add descriptive alt text to images, and use lists to break up dense information.']),
                    
                    // Ordered list of best practices
                    $this->block('list', 'list', 'Best Practices List', ['ordered' => 'yes', 'items' => "Start with a clear H1 title\nWrite a compelling excerpt (50-100 words)\nUse H2 for major sections\nKeep paragraphs focused and scannable\nAdd images to break up text\nUse lists for multiple points\nInclude a call-to-action\nOptimize SEO metadata before publishing"]),
                    
                    // Code example
                    $this->block('code', 'code', 'Code Example', ['language' => 'php', 'code' => "// Store structured content as JSON\n\$blog->content_json = [\n    [\n        'type' => 'heading',\n        'props' => ['level' => 1, 'text' => 'Title'],\n        'styles' => ['fontSize' => '48px']\n    ],\n    [\n        'type' => 'paragraph',\n        'props' => ['text' => 'Body content...'],\n        'styles' => ['color' => '#374151']\n    ]\n];\n\n\$blog->save();"]),
                    
                    // Quote
                    $this->block('quote', 'quote', 'Quote', ['text' => 'Content is king, but structure is the kingdom. A well-organized blog platform makes great writing even better.', 'author' => 'Editorial Excellence Team']),
                    
                    // Divider
                    $this->block('divider', 'divider', 'Divider', []),
                    
                    // Section 8: Performance Tips
                    $this->block('heading', 'heading', 'Section Heading', ['level' => 2, 'text' => 'Performance Optimization'], ['fontSize' => '32px', 'marginTop' => '32px']),
                    $this->block('paragraph', 'paragraph', 'Paragraph', ['text' => 'A fast blog platform improves user experience and SEO rankings. Optimize images, cache rendered content, and use lazy loading for media.']),
                    
                    $this->block('grid_section', 'grid', 'Performance Tips', ['columns' => 2, 'items' => "Compress images before upload\nUse responsive image sizes\nImplement lazy loading\nCache rendered articles\nMinify CSS and JavaScript\nUse a CDN for static assets"]),
                    
                    // Final CTA
                    $this->block('cta', 'cta', 'Call to Action', ['title' => 'Ready to Build Your Blog?', 'text' => 'Start creating professional, SEO-optimized blog posts with our powerful component library. Combine text, media, and interactive elements to engage your audience.', 'buttonText' => 'Create Your First Post', 'buttonUrl' => '/blogs/create'], ['backgroundColor' => '#1e40af', 'padding' => '40px']),
                ],
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],

            // Blog 2: Design and UX Focus
            [
                'title' => 'Designing User-Friendly Content Editors',
                'slug' => 'designing-user-friendly-content-editors',
                'category_id' => $categories['design'],
                'excerpt' => 'Principles and practices for creating intuitive, powerful content editing interfaces that writers love to use.',
                'featured_image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=1200&q=80',
                'seo_title' => 'UX Design for Content Editors - Best Practices',
                'seo_description' => 'Learn how to design content editing interfaces that are intuitive, powerful, and delightful to use. Includes component organization, layout patterns, and user workflows.',
                'seo_keywords' => 'ux design,content editor,interface design,user experience,usability',
                'seo_image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=1200&q=80',
                'content_json' => [
                    $this->block('heading', 'heading', 'Main Title', ['level' => 1, 'text' => 'The Art of Designing Content Editors'], ['fontSize' => '46px']),
                    $this->block('paragraph', 'paragraph', 'Intro', ['text' => 'A great content editor is invisible. Writers focus on their ideas, not the interface. This guide explores the principles of designing editors that feel natural, responsive, and empowering.']),
                    
                    $this->block('heading', 'heading', 'Section', ['level' => 2, 'text' => 'Key Design Principles']),
                    $this->block('list', 'list', 'Principles', ['items' => "Clarity: Make every action obvious\nConsistency: Predictable patterns throughout\nFeedback: Immediate response to user actions\nEfficiency: Minimize clicks and keystrokes\nFlexibility: Support different workflows\nAccessibility: Usable by everyone"]),
                    
                    $this->block('heading', 'heading', 'Section', ['level' => 2, 'text' => 'Component Organization']),
                    $this->block('section', 'section', 'Organization', ['title' => 'Sidebar Strategy', 'content' => 'Organize components into logical categories: Text (headings, paragraphs, quotes), Media (images, galleries), Layout (sections, grids, rows), and Interactive (buttons, CTAs, social links). Use icons and clear labels for quick recognition.']),
                    
                    $this->block('image_grid', 'image_grid', 'UI Examples', ['columns' => 2, 'images' => "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=600&q=80\nhttps://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=600&q=80"], ['imageHeight' => '250px']),
                    
                    $this->block('heading', 'heading', 'Section', ['level' => 2, 'text' => 'Layout Patterns']),
                    $this->block('row', 'row', 'Layouts', ['columns' => 3, 'items' => "Three-Column\nTwo-Column\nFull Width"]),
                    
                    $this->block('quote', 'quote', 'Design Quote', ['text' => 'The best interface is the one that gets out of the way and lets the user focus on their work.', 'author' => 'Interface Design Principles']),
                    
                    $this->block('cta', 'cta', 'CTA', ['title' => 'Explore Our Editor', 'text' => 'Experience a thoughtfully designed content editor built for writers and developers.', 'buttonText' => 'Try the Editor', 'buttonUrl' => '/blogs/create']),
                ],
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],

            // Blog 3: Performance Deep Dive
            [
                'title' => 'Web Performance Optimization for Blog Platforms',
                'slug' => 'web-performance-optimization-blog-platforms',
                'category_id' => $categories['performance'],
                'excerpt' => 'Comprehensive strategies for optimizing blog platform performance, from image compression to caching strategies.',
                'featured_image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=80',
                'seo_title' => 'Blog Performance Optimization - Complete Guide',
                'seo_description' => 'Learn proven techniques to optimize blog platform performance including image optimization, caching, lazy loading, and CDN strategies.',
                'seo_keywords' => 'performance,optimization,web speed,caching,images,cdn',
                'seo_image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=80',
                'content_json' => [
                    $this->block('heading', 'heading', 'Title', ['level' => 1, 'text' => 'Optimizing Blog Platform Performance'], ['fontSize' => '46px']),
                    $this->block('paragraph', 'paragraph', 'Intro', ['text' => 'Performance directly impacts user experience and SEO rankings. A slow blog loses readers and search visibility. This guide covers practical optimization techniques that deliver measurable improvements.']),
                    
                    $this->block('heading', 'heading', 'Section', ['level' => 2, 'text' => 'Image Optimization']),
                    $this->block('list', 'list', 'Image Tips', ['ordered' => 'yes', 'items' => "Use modern formats (WebP with fallbacks)\nCompress before upload\nResponsive image sizes\nLazy load below the fold\nAdd descriptive alt text"]),
                    
                    $this->block('heading', 'heading', 'Section', ['level' => 2, 'text' => 'Caching Strategies']),
                    $this->block('grid_section', 'grid', 'Cache Types', ['columns' => 2, 'items' => "Browser Caching\nServer-Side Caching\nCDN Caching\nDatabase Query Caching"]),
                    
                    $this->block('code', 'code', 'Cache Example', ['language' => 'php', 'code' => "// Cache rendered blog articles\nCache::remember('blog.' . \$blog->id, 3600, function () use (\$blog) {\n    return view('blog.article', ['blog' => \$blog])->render();\n});"]),
                    
                    $this->block('heading', 'heading', 'Section', ['level' => 2, 'text' => 'Monitoring and Metrics']),
                    $this->block('section', 'section', 'Metrics', ['title' => 'Key Performance Indicators', 'content' => 'Track Core Web Vitals: Largest Contentful Paint (LCP), First Input Delay (FID), and Cumulative Layout Shift (CLS). Use tools like Google PageSpeed Insights and WebPageTest to identify bottlenecks.']),
                    
                    $this->block('cta', 'cta', 'CTA', ['title' => 'Measure Your Performance', 'text' => 'Use our built-in performance monitoring to track and optimize your blog platform.', 'buttonText' => 'View Analytics', 'buttonUrl' => '#']),
                ],
                'status' => 'published',
                'published_at' => now()->subDays(1),
            ],

            // Blog 4: Development Workflow
            [
                'title' => 'Development Workflow and Best Practices',
                'slug' => 'development-workflow-best-practices',
                'category_id' => $categories['development'],
                'excerpt' => 'Streamline your development process with proven workflows, testing strategies, and deployment practices.',
                'featured_image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=80',
                'seo_title' => 'Development Workflow Best Practices',
                'seo_description' => 'Optimize your development workflow with version control, testing, code review, and deployment strategies for blog platforms.',
                'seo_keywords' => 'development,workflow,testing,deployment,best practices',
                'seo_image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=80',
                'content_json' => [
                    $this->block('heading', 'heading', 'Title', ['level' => 1, 'text' => 'Mastering Development Workflows'], ['fontSize' => '46px']),
                    $this->block('paragraph', 'paragraph', 'Intro', ['text' => 'A well-organized development workflow ensures code quality, team collaboration, and reliable deployments. Learn the practices that keep projects on track.']),
                    
                    $this->block('heading', 'heading', 'Section', ['level' => 2, 'text' => 'Version Control']),
                    $this->block('list', 'list', 'Git Practices', ['items' => "Use feature branches for new work\nWrite clear commit messages\nRequire code reviews before merge\nMaintain a clean commit history\nTag releases for easy reference"]),
                    
                    $this->block('heading', 'heading', 'Section', ['level' => 2, 'text' => 'Testing Strategy']),
                    $this->block('row', 'row', 'Test Types', ['columns' => 4, 'items' => "Unit Tests\nIntegration Tests\nFeature Tests\nE2E Tests"]),
                    
                    $this->block('code', 'code', 'Test Example', ['language' => 'php', 'code' => "// Test blog creation\ntest('user can create blog post', function () {\n    \$user = User::factory()->create();\n    \$response = \$this->actingAs(\$user)\n        ->post('/blogs', [\n            'title' => 'Test Post',\n            'slug' => 'test-post',\n            'content_json' => []\n        ]);\n    \n    \$response->assertRedirect();\n    \$this->assertDatabaseHas('blogs', ['slug' => 'test-post']);\n});"]),
                    
                    $this->block('heading', 'heading', 'Section', ['level' => 2, 'text' => 'Deployment']),
                    $this->block('section', 'section', 'Deployment', ['title' => 'Continuous Deployment', 'content' => 'Automate deployments with CI/CD pipelines. Run tests automatically, build assets, and deploy to staging before production. Use feature flags for gradual rollouts.']),
                    
                    $this->block('quote', 'quote', 'Quote', ['text' => 'Good development practices are not overhead—they are the foundation of sustainable, scalable software.', 'author' => 'Software Engineering Principles']),
                    
                    $this->block('cta', 'cta', 'CTA', ['title' => 'Improve Your Workflow', 'text' => 'Implement these practices to build better software faster.', 'buttonText' => 'Get Started', 'buttonUrl' => '/blogs/create']),
                ],
                'status' => 'published',
                'published_at' => now(),
            ],

            // Blog 5: Draft with All Components
            [
                'title' => 'Draft: Complete Component Showcase',
                'slug' => 'draft-complete-component-showcase',
                'category_id' => $categories['development'],
                'excerpt' => 'A comprehensive draft demonstrating every available component with various styling options and configurations.',
                'featured_image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1200&q=80',
                'seo_title' => 'Component Showcase Draft',
                'seo_description' => 'Complete demonstration of all available blog components with styling options and best practices.',
                'seo_keywords' => 'components,showcase,draft,examples',
                'seo_image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1200&q=80',
                'content_json' => [
                    $this->block('heading', 'heading', 'Title', ['level' => 1, 'text' => 'All Components Showcase'], ['fontSize' => '48px', 'color' => '#1e40af']),
                    $this->block('paragraph', 'paragraph', 'Intro', ['text' => 'This draft showcases every component available in the blog builder with various configurations and styling options.']),
                    
                    $this->block('divider', 'divider', 'Divider', []),
                    
                    $this->block('heading', 'heading', 'H2', ['level' => 2, 'text' => 'Heading Levels']),
                    $this->block('heading', 'heading', 'H3', ['level' => 3, 'text' => 'This is an H3 heading']),
                    $this->block('heading', 'heading', 'H4', ['level' => 4, 'text' => 'This is an H4 heading']),
                    $this->block('heading', 'heading', 'H5', ['level' => 5, 'text' => 'This is an H5 heading']),
                    
                    $this->block('paragraph', 'paragraph', 'Body', ['text' => 'This is a paragraph with standard styling. It demonstrates how body text appears in the blog. Paragraphs are the foundation of readable content.']),
                    
                    $this->block('quote', 'quote', 'Quote', ['text' => 'Every component is designed with both form and function in mind.', 'author' => 'Design Philosophy']),
                    
                    $this->block('list', 'list', 'Unordered', ['items' => "First item\nSecond item\nThird item\nFourth item"]),
                    
                    $this->block('list', 'list', 'Ordered', ['ordered' => 'yes', 'items' => "First step\nSecond step\nThird step\nFinal step"]),
                    
                    $this->block('image', 'image', 'Single Image', ['url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1200&q=80', 'alt' => 'Sample image', 'caption' => 'This is an image with a caption']),
                    
                    $this->block('image_grid', 'image_grid', 'Grid', ['columns' => 3, 'images' => "https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80\nhttps://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=600&q=80\nhttps://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=600&q=80"], ['imageHeight' => '200px']),
                    
                    $this->block('section', 'section', 'Section', ['title' => 'Section Component', 'content' => 'Sections group related content together. Use them to organize your thoughts and create visual breaks in your content.']),
                    
                    $this->block('grid_section', 'grid', 'Grid', ['columns' => 2, 'items' => "Grid Item 1\nGrid Item 2\nGrid Item 3\nGrid Item 4"]),
                    
                    $this->block('row', 'row', 'Row', ['columns' => 3, 'items' => "Column 1\nColumn 2\nColumn 3"]),
                    
                    $this->block('button', 'button', 'Button', ['text' => 'Click Me', 'url' => '#']),
                    
                    $this->block('social_links', 'social_links', 'Social', ['links' => "facebook|https://facebook.com\ntwitter|https://twitter.com\nlinkedin|https://linkedin.com\ngithub|https://github.com", 'layout' => 'horizontal', 'size' => 'large', 'animation' => 'hover-rotate']),
                    
                    $this->block('code', 'code', 'Code', ['language' => 'javascript', 'code' => "// Example code block\nconst greeting = 'Hello, World!';\nconsole.log(greeting);"]),
                    
                    $this->block('cta', 'cta', 'CTA', ['title' => 'Ready to Create?', 'text' => 'Start building amazing content with our component library.', 'buttonText' => 'Create Post', 'buttonUrl' => '/blogs/create']),
                ],
                'status' => 'draft',
                'published_at' => null,
            ],
        ];

        foreach ($items as $item) {
            Blog::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }

    private function block(string $componentId, string $type, string $label, array $props = [], array $styles = []): array
    {
        return [
            'componentId' => $componentId,
            'type' => $type,
            'label' => $label,
            'props' => $props,
            'styles' => $styles,
        ];
    }
}
