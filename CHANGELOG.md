# Changelog

All notable changes to Easy Tutorials are documented here.
This project follows [Semantic Versioning](https://semver.org/).

## [1.1.2] - 2026-02-21

### Fixed
- JavaScript namespace mismatch: PHP localization now correctly passes the `CT` object instead of `CTT`, resolving copy-to-clipboard failures in code blocks
- Code block contrast issue: replaced pale/transparent backgrounds with a dark theme so code text is always visible against the block background

### Changed
- Bumped `CTT_VERSION` constant to `1.1.2` for proper browser cache busting

---

## [1.1.1] - 2026-02-20

### Fixed
- Elementor widget styles being overridden by active theme stylesheets; widget CSS now loads with higher specificity

### Changed
- Backwards-compatible function aliases added for any external code referencing older function names

---

## [1.1.0] - 2026-02-20

### Added
- README.md with full feature overview, widget reference table, requirements, installation guide, and FAQ
- `.pot` language file (`languages/easy-tutorials.pot`) for translation workflows
- Dark-themed code block styling with syntax-aware contrast
- Functional copy-to-clipboard button on all `<pre>` code blocks (targets all pre tags for broad compatibility across Gutenberg, Classic Editor, and Elementor)
- Comprehensive styling controls for code blocks exposed in Elementor's panel

### Changed
- Plugin renamed from **Creative Tech Tutorials** to **Easy Tutorials**
- Text domain updated from `creative-tech-tutorials` to `easy-tutorials` throughout
- Main plugin file remains `creative-tech-tutorials.php` for backwards compatibility
- Elementor widget panel category updated to reflect new plugin name
- JavaScript targeting broadened from specific wrapper classes to all `<pre>` tags for universal code block support
- Static version numbers used for asset handles (replaces timestamp-based cache busting) in line with WordPress.org guidelines
- Code reviewed and updated for WordPress.org Plugin Check compliance (escaping, sanitization, nonce usage)

---

## [1.0.0] - 2026-02-20

### Added
- Tutorial custom post type with archive support and clean `/tutorials/` permalink
- Four taxonomies: Difficulty Level, Tool / Software, Tutorial Category, Tutorial Tag
- Default terms seeded on activation (12 tools, 4 difficulty levels, 9 categories, 23 tags)
- Tutorial Details meta box: duration, software version, requirements/components, GitHub URL
- View counter (logged-out visitors only)
- Admin list columns for tool, difficulty, category, duration, and views
- Sortable views and difficulty columns in admin
- 8 Elementor widgets under a "Creative Tutorials" panel category:
  - Tutorial Header
  - Tutorial Meta Bar
  - Tutorial Requirements
  - Tutorial Content
  - Tutorial Tags
  - Tutorial Categories
  - Tutorial Navigation
  - Tutorial Table of Contents
- Elementor Theme Builder integration for single Tutorial posts
- Reset Tutorial Templates admin tool (Tools menu)
- Archive / taxonomy fallback template
- Translation-ready (text domain: easy-tutorials)
