# Component Usage Guide - Seeder Reference

## Quick Reference: All Components in Seeded Posts

### 📝 Text Components

#### Heading
```php
$this->block('heading', 'heading', 'Main Title', 
    ['level' => 1, 'text' => 'Your Heading'],
    ['fontSize' => '48px', 'marginBottom' => '24px']
)
```
**Used in**: All 5 posts
**Levels**: H1 (48px), H2 (32px), H3 (24px), H4, H5, H6
**Customizable**: Color, font size, font weight, line height, text alignment, letter spacing, margins

---

#### Paragraph
```php
$this->block('paragraph', 'paragraph', 'Body Text',
    ['text' => 'Your paragraph content...', 'maxWords' => 90]
)
```
**Used in**: All 5 posts
**Features**: Word count limit, color control, line height, text alignment
**Best for**: Body copy, introductions, explanations

---

#### Quote
```php
$this->block('quote', 'quote', 'Quote',
    ['text' => 'Quote text...', 'author' => 'Author Name']
)
```
**Used in**: Posts 1, 2, 3, 4, 5
**Features**: Author attribution, background color, border styling
**Styling**: Background (#f3f4f6), left border (4px solid #2563eb), padding (22px)

---

#### List
```php
// Unordered
$this->block('list', 'list', 'Bullet Points',
    ['items' => "Item 1\nItem 2\nItem 3", 'ordered' => 'no']
)

// Ordered
$this->block('list', 'list', 'Steps',
    ['items' => "Step 1\nStep 2\nStep 3", 'ordered' => 'yes']
)
```
**Used in**: All 5 posts
**Features**: Bullet points or numbered lists, color control, font sizing
**Best for**: Checklists, steps, key points

---

### 🖼️ Media Components

#### Image
```php
$this->block('image', 'image', 'Single Image',
    ['url' => 'https://...', 'alt' => 'Description', 'caption' => 'Caption text']
)
```
**Used in**: Post 5 (showcase)
**Features**: Alt text for accessibility, captions, responsive sizing
**Customizable**: Width, height, border radius, object fit, margins

---

#### Image Grid
```php
$this->block('image_grid', 'image_grid', 'Gallery',
    ['columns' => 3, 'images' => "url1\nurl2\nurl3"],
    ['imageHeight' => '200px', 'borderRadius' => '12px']
)
```
**Used in**: Posts 1, 2, 5
**Configurations**:
- Post 1: 3 columns, 200px height
- Post 2: 2 columns, 250px height
- Post 5: 3 columns, 200px height
**Features**: Responsive columns, gap control, image height, border radius

---

### 📐 Layout Components

#### Section
```php
$this->block('section', 'section', 'Info Section',
    ['title' => 'Section Title', 'content' => 'Section content...'],
    ['backgroundColor' => '#ffffff', 'padding' => '32px', 'borderRadius' => '16px']
)
```
**Used in**: Posts 1, 2, 3, 4, 5
**Features**: Title, content, background color, padding, border, border radius
**Best for**: Grouping related information, highlighting key points

---

#### Grid Section
```php
$this->block('grid_section', 'grid', 'Features Grid',
    ['columns' => 2, 'gap' => '20px', 'items' => "Item 1\nItem 2\nItem 3\nItem 4"]
)
```
**Used in**: Posts 1, 2, 3, 4, 5
**Configurations**:
- Post 1: 2 columns (features)
- Post 2: 2 columns (tips)
- Post 3: 2 columns (cache types)
- Post 4: 4 columns (test types)
- Post 5: 2 columns (showcase)
**Features**: Flexible columns, gap control, responsive design

---

#### Row Layout
```php
$this->block('row', 'row', 'Horizontal Layout',
    ['columns' => 3, 'items' => "Column 1\nColumn 2\nColumn 3"],
    ['gap' => '16px', 'alignItems' => 'stretch']
)
```
**Used in**: Posts 1, 2, 4, 5
**Configurations**:
- Post 1: 4 columns (component types)
- Post 2: 3 columns (layout types)
- Post 4: 4 columns (test types)
- Post 5: 3 columns (showcase)
**Features**: Flexible columns, gap control, alignment options

---

#### Divider
```php
$this->block('divider', 'divider', 'Divider', [])
```
**Used in**: Posts 1, 5
**Features**: Visual separator, customizable color and margins
**Best for**: Breaking up sections, visual hierarchy

---

### 🎯 Interactive Components

#### Button
```php
$this->block('button', 'button', 'Action Button',
    ['text' => 'Click Me', 'url' => '#', 'target' => '_self']
)
```
**Used in**: Posts 2, 5
**Features**: Custom text, URL, target (self or blank), styling
**Customizable**: Background color, text color, padding, border radius, font size

---

#### Social Links
```php
$this->block('social_links', 'social_links', 'Social Media',
    [
        'links' => "facebook|https://facebook.com\ntwitter|https://twitter.com\nlinkedin|https://linkedin.com",
        'layout' => 'horizontal',
        'size' => 'medium',
        'animation' => 'hover-scale',
        'backgroundColor' => '#f3f4f6',
        'hoverBackgroundColor' => '#2563eb',
        'iconColor' => '#374151',
        'hoverIconColor' => '#ffffff'
    ]
)
```
**Used in**: Posts 1, 5
**Configurations**:
- Post 1: hover-scale animation, blue hover color
- Post 5: hover-rotate animation, large size
**Platforms Supported**: Facebook, Twitter, LinkedIn, Instagram, GitHub, YouTube, WhatsApp
**Animations**: hover-scale, hover-rotate, hover-lift, pulse, bounce
**Features**: SVG icons, color customization, animation options, label display

---

#### CTA Section
```php
$this->block('cta', 'cta', 'Call to Action',
    [
        'title' => 'Ready to Start?',
        'text' => 'Description text...',
        'buttonText' => 'Get Started',
        'buttonUrl' => '/blogs/create'
    ],
    ['backgroundColor' => '#1e40af', 'padding' => '40px']
)
```
**Used in**: All 5 posts
**Features**: Title, description, button text, button URL, background color
**Best for**: Encouraging action, end of article, promotions

---

### 💻 Technical Components

#### Code Block
```php
$this->block('code', 'code', 'Code Example',
    ['language' => 'php', 'code' => "// Your code here\n\$variable = 'value';"]
)
```
**Used in**: Posts 1, 3, 4, 5
**Languages**: PHP, JavaScript, and others
**Features**: Syntax highlighting, language specification, scrollable
**Customizable**: Background color, text color, padding, border radius, font size

---

## Component Statistics

### By Post
| Post | Components | Unique Types |
|------|-----------|--------------|
| Post 1 | 12 | 11 |
| Post 2 | 8 | 7 |
| Post 3 | 7 | 6 |
| Post 4 | 8 | 7 |
| Post 5 | 14 | 13 |
| **Total** | **49** | **13** |

### By Type
| Component | Count | Posts |
|-----------|-------|-------|
| Heading | 15 | All |
| Paragraph | 10 | All |
| Section | 5 | All |
| Grid Section | 5 | All |
| Row Layout | 4 | 1,2,4,5 |
| List | 8 | All |
| Image Grid | 3 | 1,2,5 |
| Quote | 5 | All |
| CTA | 5 | All |
| Code | 4 | 1,3,4,5 |
| Social Links | 2 | 1,5 |
| Button | 2 | 2,5 |
| Divider | 2 | 1,5 |
| Image | 1 | 5 |

---

## Styling Patterns Used

### Colors
- **Primary**: #2563eb (blue)
- **Dark**: #1e40af (dark blue)
- **Text**: #374151 (gray)
- **Background**: #f3f4f6 (light gray)
- **Dark Background**: #111827 (near black)

### Spacing
- **Small**: 12px, 16px
- **Medium**: 20px, 24px
- **Large**: 28px, 32px, 40px

### Typography
- **H1**: 48px, 700 weight
- **H2**: 32px, 700 weight
- **H3**: 24px, 700 weight
- **Body**: 18px (1.125rem), 400 weight
- **Line Height**: 1.7-1.8

### Animations
- **hover-scale**: Scale to 1.1x
- **hover-rotate**: Rotate 12 degrees
- **hover-lift**: Translate up 4px
- **pulse**: Opacity animation
- **bounce**: Vertical bounce

---

## Best Practices Demonstrated

✅ **Semantic Structure**: Proper heading hierarchy (H1 → H2 → H3)
✅ **Content Organization**: Logical flow with sections and dividers
✅ **Visual Hierarchy**: Varied component types for visual interest
✅ **Accessibility**: Alt text on images, semantic HTML
✅ **SEO Optimization**: Keywords, descriptions, structured data
✅ **Responsive Design**: Flexible grids and layouts
✅ **User Engagement**: CTAs, social links, interactive elements
✅ **Code Examples**: Real-world code snippets
✅ **Professional Styling**: Consistent colors and spacing

---

## How to Use This Guide

1. **Find a component** you want to use
2. **Copy the code block** from this guide
3. **Customize** the content and styling
4. **Add to your post** in the blog builder
5. **Preview** to see how it looks

---

## Component Combinations

### Effective Patterns
- **Heading + Paragraph + Image**: Classic article structure
- **Section + Grid**: Organized information display
- **Quote + Divider**: Emphasis and visual break
- **List + Code Block**: Instructions with examples
- **CTA + Social Links**: Engagement and sharing

### Avoid
- Too many different colors in one post
- Excessive animations (use sparingly)
- Unrelated component combinations
- Missing alt text on images
- Inconsistent spacing

---

**Last Updated**: May 24, 2026
**Version**: 1.0
