=== Easy Tutorials ===
Contributors: jobinbennykutty
Tags: tutorials, elementor, custom post type, education, creative technology
Requires at least: 6.5
Tested up to: 6.9
Requires PHP: 8.1
Stable tag: 1.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A complete tutorial management system for creative technology educators. Built for Elementor.

== Description ==

Creative Tech Tutorials gives you everything you need to build a structured tutorial platform on WordPress. It adds a dedicated Tutorial post type with rich metadata, four custom taxonomies, built-in view tracking, and a full suite of Elementor widgets so you can design your tutorial pages visually using Elementor Theme Builder.

**Key Features**

* **Tutorial post type** with archive support and clean permalink structure
* **Four taxonomies** — Difficulty Level, Tool / Software, Category, and Tags — all pre-seeded with sensible defaults
* **Tutorial Details meta box** — duration, software version, requirements/components list, and GitHub/project files URL
* **View counter** — tracks page views per tutorial (logged-out visitors only)
* **8 Elementor widgets** — drag and drop every part of a tutorial onto your Theme Builder template:
  * Tutorial Header (breadcrumb, badges, title, excerpt, featured image, GitHub button)
  * Tutorial Meta Bar (tool, difficulty, duration, version, views, date chips)
  * Tutorial Requirements (components/requirements box)
  * Tutorial Content (post body)
  * Tutorial Tags
  * Tutorial Categories
  * Tutorial Navigation (previous / next)
  * Table of Contents (auto-generated from H2/H3 headings, collapsible, smooth scroll)
* **Elementor Theme Builder integration** — Theme Builder single templates apply automatically to all Tutorial posts without any per-post configuration
* **Reset Tool** — one-click admin utility to clear per-post Elementor data so your Theme Builder template applies to all tutorials
* **Archive template** — included fallback template for the tutorial listing and taxonomy pages
* Translation-ready with full i18n support

**Requirements**

* Elementor (free) — for the widget panel
* Elementor Pro — for Theme Builder single post templates

**Getting Started**

1. Install and activate the plugin
2. In Elementor, go to Templates → Theme Builder → Add New → Single Post
3. Build your tutorial layout using the Creative Tutorials widgets
4. Set the condition to Post Type → Tutorial and publish
5. Go to Tools → Reset Tutorial Templates and click the reset button
6. All your Tutorial posts will now use your Theme Builder template

== Installation ==

1. Upload the `easy-tutorials` folder to `/wp-content/plugins/`
2. Activate the plugin through the Plugins menu in WordPress
3. The Tutorials menu will appear in your WordPress admin sidebar
4. Follow the Getting Started steps above to set up your Elementor template

== Frequently Asked Questions ==

= Does this work without Elementor? =
The Tutorial post type, taxonomies, and meta fields work without Elementor. The Elementor widgets and Theme Builder integration require Elementor (free) and Elementor Pro respectively.

= Will activating this plugin affect my existing posts? =
No. The plugin only creates new post types and taxonomies. Your existing posts, pages, and content are untouched.

= What does the Reset Tool do? =
It clears per-post Elementor canvas data from all Tutorial posts so that your Theme Builder template is used instead of any individual post layout. It does not delete post content, meta fields, featured images, or taxonomy terms.

= Can I add my own tools and difficulty levels? =
Yes. Go to Tutorials → Tools & Software or Tutorials → Difficulty Levels in your WordPress admin to add, edit, or remove terms.

= Is it translation ready? =
Yes. All strings are wrapped in WordPress i18n functions with the `easy-tutorials` text domain.

== Screenshots ==

1. Tutorial post edit screen showing the Tutorial Details meta box
2. Elementor panel showing the Creative Tutorials widget category
3. Elementor editor with Tutorial widgets placed on a Theme Builder single template
4. Frontend tutorial page rendered using the Theme Builder template
5. Tools → Reset Tutorial Templates admin page

== Changelog ==

= 1.0.0 =
* Initial release
* Tutorial custom post type with archive support
* Four taxonomies: Difficulty, Tool, Category, Tag
* Tutorial Details meta box (duration, version, components, GitHub URL)
* View counter for logged-out visitors
* 8 Elementor widgets: Header, Meta Bar, Requirements, Content, Tags, Categories, Navigation, Table of Contents
* Elementor Theme Builder integration via elementor/theme/need_override_location filter
* Reset Tutorial Templates admin tool
* Archive/taxonomy fallback template
* Translation-ready

== Upgrade Notice ==

= 1.0.0 =
Initial release.
