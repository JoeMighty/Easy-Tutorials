<h1 align="center">Easy Tutorials</h1>

<p align="center">
  <img src="https://img.shields.io/badge/version-1.0.0-blue?style=flat-square" alt="Version">
  <img src="https://img.shields.io/badge/WordPress-6.5%2B-21759b?style=flat-square&logo=wordpress&logoColor=white" alt="WordPress">
  <img src="https://img.shields.io/badge/PHP-8.1%2B-777bb4?style=flat-square&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Elementor-Pro%20required-e2003b?style=flat-square" alt="Elementor Pro">
  <img src="https://img.shields.io/badge/license-GPL%20v2%2B-green?style=flat-square" alt="License">
  <img src="https://img.shields.io/badge/wp.org-pending%20review-orange?style=flat-square&logo=wordpress" alt="WordPress.org">
</p>

<p align="center">
  A complete tutorial management system for WordPress, built for Elementor. Add a structured tutorial platform to any site with custom post types, rich metadata, and 8 drag-and-drop Elementor widgets.
</p>

---

## Features

- **Tutorial post type** — dedicated CPT with archive support and clean `/tutorials/` permalink structure
- **4 taxonomies** — Difficulty Level, Tool / Software, Category, and Tags, all pre-seeded with defaults
- **Tutorial Details meta box** — duration, software version, requirements/components list, and GitHub URL
- **View counter** — tracks page views per tutorial (logged-out visitors only)
- **8 Elementor widgets** for full Theme Builder control:

| Widget | Description |
|--------|-------------|
| Tutorial Header | Breadcrumb, badges, title, excerpt, featured image, GitHub button |
| Tutorial Meta Bar | Tool, difficulty, duration, version, views, date chips |
| Tutorial Requirements | Components / requirements box |
| Tutorial Content | Post body |
| Tutorial Tags | Tag links |
| Tutorial Categories | Category links or badges |
| Tutorial Navigation | Previous / next post links |
| Table of Contents | Auto-generated from H2/H3 headings, collapsible, smooth scroll |

- **Elementor Theme Builder integration** — single templates apply automatically to all Tutorial posts
- **Reset Tool** — one-click admin utility under Tools → Reset Tutorial Templates
- **Archive template** — fallback template for the tutorial listing and taxonomy pages
- **Translation-ready** — full i18n with `easy-tutorials` text domain

---

## Requirements

| Requirement | Version |
|-------------|---------|
| WordPress | 6.5 or higher |
| PHP | 8.1 or higher |
| Elementor (free) | For the widget panel |
| Elementor Pro | For Theme Builder single post templates |

---

## Installation

1. Download the latest release zip from the [Releases](https://github.com/JoeMighty/creative-tech-tutorials/releases) page
2. In WordPress admin go to **Plugins → Add New → Upload Plugin**
3. Upload the zip and click **Activate**
4. The **Tutorials** menu will appear in your admin sidebar

> **Or install manually:**
> Upload the `creative-tech-tutorials` folder to `/wp-content/plugins/` and activate via the Plugins screen.

---

## Getting Started

Once activated, connect it to Elementor Theme Builder:

1. Go to **Templates → Theme Builder → Add New → Single Post**
2. Build your tutorial layout using the **Easy Tutorials** widgets in the Elementor panel
3. Set the display condition to **Post Type → Tutorial** and publish
4. Go to **Tools → Reset Tutorial Templates** and click the reset button
5. All Tutorial posts will now use your Theme Builder template

---

## Pre-seeded Content

The plugin seeds these defaults on activation so you can start publishing immediately.

<details>
<summary><strong>Difficulty Levels</strong></summary>

- Getting Started
- Beginner
- Intermediate
- Advanced

</details>

<details>
<summary><strong>Tools & Software</strong></summary>

Bare Conductive · After Effects · TouchDesigner · Photoshop · Blender · Python · Max/MSP · Unity · Arduino · p5.js · Processing · Resolume

</details>

<details>
<summary><strong>Categories</strong></summary>

Getting Started · Motion Graphics · Interactive Design · Physical Computing · Creative Coding · Image & Video · 3D & Realtime · Audio & Music · Project Showcase

</details>

---

## FAQ

**Does this work without Elementor?**
The Tutorial post type, taxonomies, and meta fields all work without Elementor. The widgets and Theme Builder integration require Elementor (free) and Elementor Pro respectively.

**Will activating this affect my existing posts?**
No. The plugin only creates new post types and taxonomies. Existing posts, pages, and content are untouched.

**What does the Reset Tool do?**
It clears per-post Elementor canvas data from all Tutorial posts so the Theme Builder template is used instead of any individually-edited layout. It does not touch post content, meta fields, featured images, or taxonomy terms.

**Can I add my own tools and difficulty levels?**
Yes — go to **Tutorials → Tools & Software** or **Tutorials → Difficulty Levels** in your WordPress admin to add, edit, or remove terms at any time.

---

## Changelog

### 1.0.0
- Initial release
- Tutorial custom post type with archive support
- Four taxonomies: Difficulty, Tool, Category, Tag
- Tutorial Details meta box (duration, version, components, GitHub URL)
- View counter for logged-out visitors
- 8 Elementor widgets: Header, Meta Bar, Requirements, Content, Tags, Categories, Navigation, Table of Contents
- Elementor Theme Builder integration via `elementor/theme/need_override_location` filter
- Reset Tutorial Templates admin tool
- Archive / taxonomy fallback template
- Translation-ready

---

## License

[GPL v2 or later](https://www.gnu.org/licenses/gpl-2.0.html) — see the [LICENSE](LICENSE) file for details.

---

<p align="center">
  Made by <a href="https://github.com/JoeMighty">Jobin Bennykutty</a>
</p>
