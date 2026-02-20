<?php
/**
 * Plugin Name:       Easy Tutorials
 * Plugin URI:        https://github.com/JoeMighty/creative-tech-tutorials
 * Description:       A complete tutorial management system for creative technology educators. Adds a Tutorial post type with difficulty levels, tools, categories and tags, custom meta fields, view tracking, Elementor widgets, and Elementor Theme Builder integration.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      8.1
 * Author:            Jobin Bennykutty
 * Author URI:        https://github.com/JoeMighty
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       easy-tutorials
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CTT_VERSION', '1.0.0' );
define( 'CTT_DIR',     plugin_dir_path( __FILE__ ) );
define( 'CTT_URL',     plugin_dir_url( __FILE__ ) );


/* ============================================================
   1.  ENQUEUE STYLES & SCRIPTS
   ============================================================ */
add_action( 'wp_enqueue_scripts', 'ctt_enqueue_assets' );

function ctt_enqueue_assets() {
    wp_enqueue_style( 'ctt-style', CTT_URL . 'assets/css/style.css', [], CTT_VERSION );
    wp_enqueue_style( 'ctt-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=DM+Mono:wght@400;500&display=swap',
        [], CTT_VERSION
    );
    wp_enqueue_script( 'ctt-scripts', CTT_URL . 'assets/js/main.js', [ 'jquery' ], CTT_VERSION, true );
    wp_localize_script( 'ctt-scripts', 'CTT', [
        'archive_url' => get_post_type_archive_link( 'tutorial' ),
        'is_single'   => is_singular( 'tutorial' ),
    ] );
}


/* ============================================================
   2.  THEME SUPPORT
   ============================================================ */
add_action( 'after_setup_theme', 'ctt_theme_setup' );

function ctt_theme_setup() {
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'ctt-card',     640,  400, true );
    add_image_size( 'ctt-featured', 1200, 600, true );
}


/* ============================================================
   3.  CUSTOM POST TYPE — Tutorial
   ============================================================ */
add_action( 'init', 'ctt_register_tutorial_cpt' );

function ctt_register_tutorial_cpt() {
    register_post_type( 'tutorial', [
        'labels' => [
            'name'               => __( 'Tutorials',                     'easy-tutorials' ),
            'singular_name'      => __( 'Tutorial',                      'easy-tutorials' ),
            'add_new'            => __( 'Add New',                       'easy-tutorials' ),
            'add_new_item'       => __( 'Add New Tutorial',              'easy-tutorials' ),
            'edit_item'          => __( 'Edit Tutorial',                 'easy-tutorials' ),
            'view_item'          => __( 'View Tutorial',                 'easy-tutorials' ),
            'all_items'          => __( 'All Tutorials',                 'easy-tutorials' ),
            'search_items'       => __( 'Search Tutorials',              'easy-tutorials' ),
            'not_found'          => __( 'No tutorials found.',           'easy-tutorials' ),
            'not_found_in_trash' => __( 'No tutorials found in Trash.',  'easy-tutorials' ),
            'menu_name'          => __( 'Tutorials',                     'easy-tutorials' ),
        ],
        'public'          => true,
        'has_archive'     => true,
        'rewrite'         => [ 'slug' => 'tutorials' ],
        'menu_icon'       => 'dashicons-book-alt',
        'menu_position'   => 5,
        'supports'        => [ 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'author' ],
        'show_in_rest'    => true,
        'capability_type' => 'post',
    ] );
}


/* ============================================================
   4.  TAXONOMIES
   ============================================================ */
add_action( 'init', 'ctt_register_taxonomies' );

function ctt_register_taxonomies() {

    register_taxonomy( 'tutorial_category', [ 'tutorial' ], [
        'labels' => [
            'name'          => __( 'Categories',     'easy-tutorials' ),
            'singular_name' => __( 'Category',       'easy-tutorials' ),
            'all_items'     => __( 'All Categories', 'easy-tutorials' ),
            'menu_name'     => __( 'Categories',     'easy-tutorials' ),
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'tutorial-category' ],
        'show_in_rest'      => true,
    ] );

    register_taxonomy( 'tutorial_tag', [ 'tutorial' ], [
        'labels' => [
            'name'          => __( 'Tags',     'easy-tutorials' ),
            'singular_name' => __( 'Tag',      'easy-tutorials' ),
            'all_items'     => __( 'All Tags', 'easy-tutorials' ),
            'menu_name'     => __( 'Tags',     'easy-tutorials' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'tutorial-tag' ],
        'show_in_rest'      => true,
    ] );

    register_taxonomy( 'difficulty', [ 'tutorial' ], [
        'labels' => [
            'name'          => __( 'Difficulty Levels', 'easy-tutorials' ),
            'singular_name' => __( 'Difficulty Level',  'easy-tutorials' ),
            'all_items'     => __( 'All Levels',         'easy-tutorials' ),
            'menu_name'     => __( 'Difficulty Levels',  'easy-tutorials' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'difficulty' ],
        'show_in_rest'      => true,
    ] );

    register_taxonomy( 'tool', [ 'tutorial' ], [
        'labels' => [
            'name'          => __( 'Tools & Software',  'easy-tutorials' ),
            'singular_name' => __( 'Tool / Software',   'easy-tutorials' ),
            'all_items'     => __( 'All Tools',          'easy-tutorials' ),
            'menu_name'     => __( 'Tools & Software',   'easy-tutorials' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'tool' ],
        'show_in_rest'      => true,
    ] );
}


/* ============================================================
   5.  SEED DEFAULT TERMS ON ACTIVATION
   ============================================================ */
function ctt_seed_default_terms() {

    $difficulties = [
        [ 'Getting Started', 'getting-started', 'No prior experience needed. Perfect first stop.' ],
        [ 'Beginner',        'beginner',        'Some basic knowledge of the tool or hardware.' ],
        [ 'Intermediate',    'intermediate',    'Comfortable working with the tool independently.' ],
        [ 'Advanced',        'advanced',        'For experienced practitioners and developers.' ],
    ];
    foreach ( $difficulties as [ $name, $slug, $desc ] ) {
        if ( ! term_exists( $slug, 'difficulty' ) )
            wp_insert_term( $name, 'difficulty', [ 'slug' => $slug, 'description' => $desc ] );
    }

    $tools = [
        [ 'Bare Conductive', 'bare-conductive', 'Touch Board, Electric Paint, MPR121' ],
        [ 'After Effects',   'after-effects',   'Motion graphics and visual effects' ],
        [ 'TouchDesigner',   'touchdesigner',   'Node-based visual programming' ],
        [ 'Photoshop',       'photoshop',       'Image editing and compositing' ],
        [ 'Blender',         'blender',         '3D modelling, rendering, and animation' ],
        [ 'Python',          'python',          'Scripting, automation, and creative code' ],
        [ 'Max/MSP',         'max-msp',         'Visual programming for music and multimedia' ],
        [ 'Unity',           'unity',           'Real-time 3D and interactive experiences' ],
        [ 'Arduino',         'arduino',         'Microcontroller programming' ],
        [ 'p5.js',           'p5js',            'Creative coding in the browser' ],
        [ 'Processing',      'processing',      'Visual arts programming' ],
        [ 'Resolume',        'resolume',        'VJ software and video mapping' ],
    ];
    foreach ( $tools as [ $name, $slug, $desc ] ) {
        if ( ! term_exists( $slug, 'tool' ) )
            wp_insert_term( $name, 'tool', [ 'slug' => $slug, 'description' => $desc ] );
    }

    $categories = [
        [ 'Getting Started',    'getting-started-cat', 'First steps and installation guides' ],
        [ 'Motion Graphics',    'motion-graphics',     'After Effects, animation, and visual effects' ],
        [ 'Interactive Design', 'interactive-design',  'TouchDesigner, sensors, and interactive systems' ],
        [ 'Physical Computing', 'physical-computing',  'Arduino, Bare Conductive, and hardware' ],
        [ 'Creative Coding',    'creative-coding',     'p5.js, Processing, Python, and generative art' ],
        [ 'Image & Video',      'image-video',         'Photoshop, Premiere, DaVinci Resolve' ],
        [ '3D & Realtime',      '3d-realtime',         'Blender, Unity, Unreal Engine' ],
        [ 'Audio & Music',      'audio-music',         'Max/MSP, Ableton, SuperCollider' ],
        [ 'Project Showcase',   'project-showcase',    'Full project walkthroughs and case studies' ],
    ];
    foreach ( $categories as [ $name, $slug, $desc ] ) {
        if ( ! term_exists( $slug, 'tutorial_category' ) )
            wp_insert_term( $name, 'tutorial_category', [ 'slug' => $slug, 'description' => $desc ] );
    }

    $tags = [
        'Beginner Friendly', 'Project-Based', 'Code Included', 'Free Assets',
        'Motion Blur', 'Expressions', 'Particles', 'Shaders', 'Nodes',
        'Capacitive Touch', 'MP3 Playback', 'MIDI', 'Sensors', 'Electric Paint',
        'Automation', 'Scripting', 'Generative', 'Real-time', 'Installation',
        'Wearables', 'Projection Mapping', 'Compositing', 'Retouching',
    ];
    foreach ( $tags as $tag ) {
        if ( ! term_exists( sanitize_title( $tag ), 'tutorial_tag' ) )
            wp_insert_term( $tag, 'tutorial_tag' );
    }
}


/* ============================================================
   6.  META BOX — Tutorial Details
   ============================================================ */
add_action( 'add_meta_boxes', 'ctt_add_meta_boxes' );

function ctt_add_meta_boxes() {
    add_meta_box(
        'ctt_tutorial_details',
        __( '📋 Tutorial Details', 'easy-tutorials' ),
        'ctt_meta_box_html',
        'tutorial',
        'side',
        'default'
    );
}

function ctt_meta_box_html( $post ) {
    wp_nonce_field( 'ctt_save_meta', 'ctt_meta_nonce' );
    $duration   = get_post_meta( $post->ID, '_ctt_duration',   true );
    $components = get_post_meta( $post->ID, '_ctt_components', true );
    $github_url = get_post_meta( $post->ID, '_ctt_github_url', true );
    $version    = get_post_meta( $post->ID, '_ctt_version',    true );
    ?>
    <p>
        <label for="ctt_duration"><strong><?php esc_html_e( '⏱ Duration (minutes)', 'easy-tutorials' ); ?></strong></label><br>
        <input type="number" id="ctt_duration" name="ctt_duration"
               value="<?php echo esc_attr( $duration ); ?>"
               style="width:100%;margin-top:4px;" min="1" placeholder="e.g. 30">
    </p>
    <p>
        <label for="ctt_version"><strong><?php esc_html_e( '🔖 Software Version', 'easy-tutorials' ); ?></strong></label><br>
        <input type="text" id="ctt_version" name="ctt_version"
               value="<?php echo esc_attr( $version ); ?>"
               style="width:100%;margin-top:4px;" placeholder="e.g. After Effects 2024">
        <span style="font-size:11px;color:#666;"><?php esc_html_e( 'Version this tutorial was created with', 'easy-tutorials' ); ?></span>
    </p>
    <p>
        <label for="ctt_components"><strong><?php esc_html_e( '📦 Requirements / Components', 'easy-tutorials' ); ?></strong></label><br>
        <textarea id="ctt_components" name="ctt_components" rows="5"
                  style="width:100%;margin-top:4px;"
                  placeholder="<?php esc_attr_e( "One per line, e.g.\nAfter Effects 2024\nMotion Bro plugin (free)", 'easy-tutorials' ); ?>"><?php echo esc_textarea( $components ); ?></textarea>
        <span style="font-size:11px;color:#666;"><?php esc_html_e( 'One item per line', 'easy-tutorials' ); ?></span>
    </p>
    <p>
        <label for="ctt_github_url"><strong><?php esc_html_e( '🔗 Project Files / GitHub URL', 'easy-tutorials' ); ?></strong></label><br>
        <input type="url" id="ctt_github_url" name="ctt_github_url"
               value="<?php echo esc_attr( $github_url ); ?>"
               style="width:100%;margin-top:4px;" placeholder="https://github.com/...">
    </p>
    <?php
}

add_action( 'save_post_tutorial', 'ctt_save_meta' );

function ctt_save_meta( $post_id ) {
    if ( ! isset( $_POST['ctt_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ctt_meta_nonce'] ) ), 'ctt_save_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [
        '_ctt_duration'   => 'absint',
        '_ctt_version'    => 'sanitize_text_field',
        '_ctt_components' => 'sanitize_textarea_field',
        '_ctt_github_url' => 'esc_url_raw',
    ];
    foreach ( $fields as $key => $sanitizer ) {
        $form_key = ltrim( str_replace( '_ctt_', 'ctt_', $key ), '_' );
        if ( isset( $_POST[ $form_key ] ) ) {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- $sanitizer callback handles sanitization.
            update_post_meta( $post_id, $key, $sanitizer( wp_unslash( $_POST[ $form_key ] ) ) );
        }
    }
}


/* ============================================================
   7.  ADMIN LIST COLUMNS
   ============================================================ */
add_filter( 'manage_tutorial_posts_columns',         'ctt_tutorial_columns' );
add_action( 'manage_tutorial_posts_custom_column',   'ctt_tutorial_column_content', 10, 2 );
add_filter( 'manage_edit-tutorial_sortable_columns', 'ctt_sortable_columns' );

function ctt_tutorial_columns( $cols ) {
    $new = [];
    foreach ( $cols as $k => $v ) {
        $new[ $k ] = $v;
        if ( $k === 'title' ) {
            $new['tool']              = '🔧 ' . __( 'Tool',       'easy-tutorials' );
            $new['difficulty']        = '📊 ' . __( 'Difficulty', 'easy-tutorials' );
            $new['tutorial_category'] = '📁 ' . __( 'Category',   'easy-tutorials' );
            $new['ctt_duration']      = '⏱ '  . __( 'Min',        'easy-tutorials' );
            $new['ctt_views']         = '👁 '  . __( 'Views',      'easy-tutorials' );
        }
    }
    return $new;
}

function ctt_tutorial_column_content( $col, $post_id ) {
    switch ( $col ) {
        case 'tool':
            $terms = get_the_terms( $post_id, 'tool' );
            echo ( $terms && ! is_wp_error( $terms ) ) ? esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) : '—';
            break;
        case 'difficulty':
            $terms = get_the_terms( $post_id, 'difficulty' );
            echo ( $terms && ! is_wp_error( $terms ) ) ? esc_html( $terms[0]->name ) : '—';
            break;
        case 'tutorial_category':
            $terms = get_the_terms( $post_id, 'tutorial_category' );
            echo ( $terms && ! is_wp_error( $terms ) ) ? esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) : '—';
            break;
        case 'ctt_duration':
            $d = get_post_meta( $post_id, '_ctt_duration', true );
            echo $d ? absint( $d ) : '—';
            break;
        case 'ctt_views':
            $v = get_post_meta( $post_id, '_ctt_views', true );
            echo $v ? number_format( (int) $v ) : '0';
            break;
    }
}

function ctt_sortable_columns( $cols ) {
    $cols['ctt_views']  = 'ctt_views';
    $cols['difficulty'] = 'difficulty';
    return $cols;
}


/* ============================================================
   8.  VIEW COUNTER
   ============================================================ */
add_action( 'wp_head', 'ctt_track_views' );

function ctt_track_views() {
    if ( ! is_singular( 'tutorial' ) || is_user_logged_in() ) return;
    $id = get_the_ID();
    update_post_meta( $id, '_ctt_views', (int) get_post_meta( $id, '_ctt_views', true ) + 1 );
}


/* ============================================================
   9.  HELPER FUNCTIONS
   ============================================================ */
function ctt_tool_css_class( $slug ) {
    $map = [
        'after-effects'   => 'after-effects',
        'touchdesigner'   => 'touchdesigner',
        'photoshop'       => 'photoshop',
        'bare-conductive' => 'bare-conductive',
        'arduino'         => 'arduino',
        'blender'         => 'blender',
        'python'          => 'python',
        'max-msp'         => 'maxmsp',
        'unity'           => 'unity',
    ];
    return $map[ $slug ] ?? 'default';
}

function ctt_tool_icon( $slug ) {
    $icons = [
        'after-effects'   => '🎬',
        'touchdesigner'   => '🔷',
        'photoshop'       => '🖼',
        'bare-conductive' => '⚡',
        'arduino'         => '🔌',
        'blender'         => '🟠',
        'python'          => '🐍',
        'max-msp'         => '🎛',
        'unity'           => '🎮',
        'p5js'            => '💻',
        'processing'      => '⭕',
        'resolume'        => '📽',
    ];
    return $icons[ $slug ] ?? '🛠';
}

function ctt_tool_badges( $post_id = null, $limit = 3 ) {
    $id    = $post_id ?: get_the_ID();
    $tools = get_the_terms( $id, 'tool' );
    if ( ! $tools || is_wp_error( $tools ) ) return '';
    $html = ''; $shown = 0;
    foreach ( $tools as $tool ) {
        if ( $shown >= $limit ) break;
        $html .= sprintf(
            '<a href="%s" class="ct-tool-badge ct-tool-badge--%s">%s %s</a>',
            esc_url( get_term_link( $tool ) ),
            esc_attr( ctt_tool_css_class( $tool->slug ) ),
            ctt_tool_icon( $tool->slug ),
            esc_html( $tool->name )
        );
        $shown++;
    }
    return $html;
}

function ctt_difficulty_badge( $post_id = null ) {
    $terms = get_the_terms( $post_id ?: get_the_ID(), 'difficulty' );
    if ( ! $terms || is_wp_error( $terms ) ) return '';
    return sprintf(
        '<span class="ct-badge ct-badge--%s">%s</span>',
        esc_attr( $terms[0]->slug ),
        esc_html( $terms[0]->name )
    );
}

function ctt_duration_chip( $post_id = null ) {
    $d = get_post_meta( $post_id ?: get_the_ID(), '_ctt_duration', true );
    return $d ? '<span class="ct-meta__chip">⏱ ' . absint( $d ) . ' min</span>' : '';
}

function ctt_version_chip( $post_id = null ) {
    $v = get_post_meta( $post_id ?: get_the_ID(), '_ctt_version', true );
    return $v ? '<span class="ct-meta__chip">🔖 ' . esc_html( $v ) . '</span>' : '';
}

function ctt_requirements_box( $post_id = null ) {
    $raw = get_post_meta( $post_id ?: get_the_ID(), '_ctt_components', true );
    if ( ! $raw ) return '';
    $items = array_filter( array_map( 'trim', explode( "\n", $raw ) ) );
    $html  = '<div class="ct-box"><h3>' . esc_html__( '📦 Requirements', 'easy-tutorials' ) . '</h3><ul>';
    foreach ( $items as $item ) $html .= '<li>' . esc_html( $item ) . '</li>';
    return $html . '</ul></div>';
}

function ctt_tags_html( $post_id = null ) {
    $tags = get_the_terms( $post_id ?: get_the_ID(), 'tutorial_tag' );
    if ( ! $tags || is_wp_error( $tags ) ) return '';
    $html = '<div class="ct-card__tags">';
    foreach ( $tags as $tag )
        $html .= sprintf( '<a href="%s" class="ct-tag">%s</a>', esc_url( get_term_link( $tag ) ), esc_html( $tag->name ) );
    return $html . '</div>';
}

// Backwards-compatible aliases so existing Elementor widget code still works
function ct_tool_badges( $post_id = null, $limit = 3 ) { return ctt_tool_badges( $post_id, $limit ); }
function ct_difficulty_badge( $post_id = null )         { return ctt_difficulty_badge( $post_id ); }
function ct_duration_chip( $post_id = null )            { return ctt_duration_chip( $post_id ); }
function ct_version_chip( $post_id = null )             { return ctt_version_chip( $post_id ); }
function ct_requirements_box( $post_id = null )         { return ctt_requirements_box( $post_id ); }
function ct_tags_html( $post_id = null )                { return ctt_tags_html( $post_id ); }


/* ============================================================
   10. WIDGET AREAS
   ============================================================ */
add_action( 'widgets_init', 'ctt_register_sidebars' );

function ctt_register_sidebars() {
    register_sidebar( [
        'name'          => __( 'Tutorial Sidebar', 'easy-tutorials' ),
        'id'            => 'ctt-sidebar',
        'before_widget' => '<div class="ctt-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="ctt-widget__title">',
        'after_title'   => '</h4>',
    ] );
}


/* ============================================================
   11. ARCHIVE TEMPLATE LOADING
       Singles are handled by Elementor Theme Builder.
       Archive / taxonomy pages use the plugin template.
   ============================================================ */
add_filter( 'template_include', 'ctt_load_templates' );

function ctt_load_templates( $template ) {
    if ( is_post_type_archive( 'tutorial' ) || is_tax( [ 'difficulty', 'tool', 'tutorial_category', 'tutorial_tag' ] ) ) {
        $plugin_template = CTT_DIR . 'templates/archive-tutorial.php';
        if ( file_exists( $plugin_template ) ) return $plugin_template;
    }
    return $template;
}


/* ============================================================
   12. ELEMENTOR INTEGRATION
   ============================================================ */

// Register Tutorial post type with Elementor
add_filter( 'elementor/utils/get_public_post_types', function( $types ) {
    $types['tutorial'] = __( 'Tutorial', 'easy-tutorials' );
    return $types;
} );

// Force Elementor Theme Builder to override single tutorial templates.
// Elementor Pro's "single" location does not set overwrite=true by default,
// so without this filter Theme Builder templates won't apply to custom post types.
add_filter( 'elementor/theme/need_override_location', 'ctt_force_theme_builder', 10, 2 );

function ctt_force_theme_builder( $need_override, $location ) {
    if ( 'single' === $location && is_singular( 'tutorial' ) ) {
        return true;
    }
    return $need_override;
}

// Register "Creative Tutorials" panel category in Elementor
add_action( 'elementor/elements/categories_registered', 'ctt_register_elementor_category' );

function ctt_register_elementor_category( $elements_manager ) {
    $elements_manager->add_category( 'creative-tutorials', [
        'title' => __( 'Creative Tutorials', 'easy-tutorials' ),
        'icon'  => 'fa fa-book',
    ] );
}

// Load and register Elementor widgets
add_action( 'elementor/widgets/register', 'ctt_register_elementor_widgets' );

function ctt_register_elementor_widgets( $widgets_manager ) {
    $widget_files = [
        'widget-tutorial-header.php',
        'widget-tutorial-meta.php',
        'widget-tutorial-requirements.php',
        'widget-tutorial-content.php',
        'widget-tutorial-tags.php',
        'widget-tutorial-categories.php',
        'widget-tutorial-navigation.php',
        'widget-tutorial-toc.php',
    ];
    foreach ( $widget_files as $file ) {
        $path = CTT_DIR . 'includes/elementor/widgets/' . $file;
        if ( file_exists( $path ) ) require_once $path;
    }

    $widget_classes = [
        'CT_Widget_Tutorial_Header',
        'CT_Widget_Tutorial_Meta',
        'CT_Widget_Tutorial_Requirements',
        'CT_Widget_Tutorial_Content',
        'CT_Widget_Tutorial_Tags',
        'CT_Widget_Tutorial_Categories',
        'CT_Widget_Tutorial_Navigation',
        'CT_Widget_Tutorial_TOC',
    ];
    foreach ( $widget_classes as $class ) {
        if ( class_exists( $class ) ) $widgets_manager->register( new $class() );
    }
}

// Enqueue Elementor widget styles
add_action( 'wp_enqueue_scripts', 'ctt_enqueue_elementor_styles' );

function ctt_enqueue_elementor_styles() {
    if ( ! is_singular( 'tutorial' ) ) return;
    wp_enqueue_style( 'ctt-elementor-widgets', CTT_URL . 'assets/css/elementor-widgets.css', [], CTT_VERSION );
}

add_action( 'elementor/preview/enqueue_styles', function() {
    wp_enqueue_style( 'ctt-elementor-widgets', CTT_URL . 'assets/css/elementor-widgets.css', [], CTT_VERSION );
} );


/* ============================================================
   13. MISC
   ============================================================ */
add_filter( 'excerpt_length', fn() => 22 );
add_filter( 'excerpt_more',   fn() => '…' );


/* ============================================================
   14. ACTIVATION / DEACTIVATION
   ============================================================ */
register_activation_hook( __FILE__, 'ctt_activate' );

function ctt_activate() {
    ctt_register_tutorial_cpt();
    ctt_register_taxonomies();
    ctt_seed_default_terms();
    flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, function() {
    flush_rewrite_rules();
} );


/* ============================================================
   15. ADMIN TOOL — Reset Tutorials to Theme Builder Template
       Tools → Reset Tutorial Templates
   ============================================================ */
add_action( 'admin_menu', 'ctt_register_reset_tool' );

function ctt_register_reset_tool() {
    add_management_page(
        __( 'Reset Tutorial Templates', 'easy-tutorials' ),
        __( 'Reset Tutorial Templates', 'easy-tutorials' ),
        'manage_options',
        'ctt-reset-tutorials',
        'ctt_reset_tool_page'
    );
}

function ctt_reset_tool_page() {
    $results = null;

    if (
        isset( $_POST['ctt_reset_nonce'] ) &&
        wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ctt_reset_nonce'] ) ), 'ctt_reset_tutorials' ) &&
        current_user_can( 'manage_options' )
    ) {
        $results = ctt_do_reset();
    }

    $tutorials = get_posts( [ 'post_type' => 'tutorial', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids' ] );
    $total     = count( $tutorials );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Reset Tutorial Templates', 'easy-tutorials' ); ?></h1>
        <p><?php esc_html_e( 'Clears per-post Elementor data so your Theme Builder → Single Post → Tutorial template applies to all tutorials.', 'easy-tutorials' ); ?></p>
        <p><strong><?php esc_html_e( 'Does not delete post content, meta fields, tags, categories, or media.', 'easy-tutorials' ); ?></strong></p>

        <?php if ( $results !== null ) : ?>
            <div class="notice notice-success" style="padding:12px 16px;">
                <p><strong><?php esc_html_e( 'Done!', 'easy-tutorials' ); ?></strong>
                <?php
                // translators: %1$d: number of posts reset, %2$d: total number of posts.
                printf( esc_html__( 'Reset %1$d of %2$d Tutorial posts.', 'easy-tutorials' ), (int) $results['reset'], (int) $results['total'] );
                ?></p>
                <p><a href="<?php echo esc_url( get_post_type_archive_link( 'tutorial' ) ); ?>" target="_blank"><?php esc_html_e( 'View tutorials →', 'easy-tutorials' ); ?></a></p>
            </div>
        <?php endif; ?>

        <div style="background:#fff;border:1px solid #ccd0d4;border-radius:6px;padding:24px;max-width:600px;margin-top:20px;">
            <h3 style="margin-top:0;">
                <?php
                // translators: %d: number of tutorial posts found.
                printf( esc_html__( 'Found %d Tutorial post(s)', 'easy-tutorials' ), absint( $total ) );
                ?>
            </h3>
            <?php if ( $total === 0 ) : ?>
                <p style="color:#888;"><?php esc_html_e( 'No Tutorial posts found.', 'easy-tutorials' ); ?></p>
            <?php else : ?>
                <p><strong><?php esc_html_e( 'Make sure your Theme Builder template is published with the Tutorial condition set before proceeding.', 'easy-tutorials' ); ?></strong></p>
                <form method="post">
                    <?php wp_nonce_field( 'ctt_reset_tutorials', 'ctt_reset_nonce' ); ?>
                    <button type="submit" class="button button-primary button-large"
                            onclick="return confirm('<?php
                            // translators: %d: number of tutorial posts.
                            printf( esc_js( __( 'Reset all %d Tutorial posts? This cannot be undone.', 'easy-tutorials' ) ), absint( $total ) ); ?>');">
                        <?php
                        // translators: %d: number of tutorial posts.
                        printf( esc_html__( 'Reset All %d Tutorials to Theme Builder Template', 'easy-tutorials' ), absint( $total ) );
                        ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

function ctt_do_reset() {
    $tutorials = get_posts( [ 'post_type' => 'tutorial', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids' ] );
    $reset = 0; $skipped = 0;

    $elementor_keys = [
        '_elementor_data', '_elementor_edit_mode', '_elementor_template_type',
        '_elementor_version', '_elementor_pro_version', '_elementor_page_settings',
        '_elementor_controls_usage', '_elementor_css', '_wp_page_template',
    ];

    foreach ( $tutorials as $post_id ) {
        $had_data = false;
        foreach ( $elementor_keys as $key ) {
            if ( get_post_meta( $post_id, $key, true ) !== '' ) $had_data = true;
            delete_post_meta( $post_id, $key );
        }
        if ( $had_data ) { $reset++; } else { $skipped++; }
    }

    if ( class_exists( '\Elementor\Plugin' ) )
        \Elementor\Plugin::$instance->files_manager->clear_cache();

    return [ 'total' => count( $tutorials ), 'reset' => $reset, 'skipped' => $skipped ];
}
