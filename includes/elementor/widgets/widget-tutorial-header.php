<?php
/**
 * Elementor Widget: Tutorial Header
 * Displays breadcrumb, meta badges (tool, difficulty, duration, version, views), title, excerpt, featured image, and GitHub button.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class CT_Widget_Tutorial_Header extends \Elementor\Widget_Base {

    public function get_name()  { return 'ct_tutorial_header'; }
    public function get_title() { return __( 'Tutorial – Header', 'easy-tutorials' ); }
    public function get_icon()  { return 'eicon-post-info'; }
    public function get_categories() { return [ 'easy-tutorials' ]; }
    public function get_keywords() { return [ 'tutorial', 'header', 'title', 'meta', 'breadcrumb' ]; }

    protected function register_controls() {

        $this->start_controls_section( 'section_visibility', [
            'label' => __( 'Show / Hide Elements', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        foreach ( [
            'show_breadcrumb'      => 'Breadcrumb',
            'show_tool_badges'     => 'Tool Badges',
            'show_difficulty'      => 'Difficulty Badge',
            'show_duration'        => 'Duration Chip',
            'show_version'         => 'Version Chip',
            'show_views'           => 'View Count',
            'show_date'            => 'Date',
            'show_title'           => 'Title',
            'show_excerpt'         => 'Excerpt',
            'show_featured_image'  => 'Featured Image',
            'show_github_button'   => 'GitHub / Files Button',
        ] as $key => $label ) {
            $this->add_control( $key, [
                'label'        => $label,
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ] );
        }

        $this->end_controls_section();

        // Style: Title
        $this->start_controls_section( 'section_title_style', [
            'label' => __( 'Title', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'title_color', [
            'label'     => __( 'Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-widget-title' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .ct-widget-title',
        ] );
        $this->end_controls_section();

        // Style: Meta Row
        $this->start_controls_section( 'section_meta_style', [
            'label' => __( 'Meta / Badges', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'meta_gap', [
            'label'      => __( 'Gap between items', 'easy-tutorials' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 8 ],
            'selectors'  => [ '{{WRAPPER}} .ct-meta' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id  = get_the_ID();

        if ( ! $post_id || get_post_type( $post_id ) !== 'tutorial' ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div style="padding:20px;border:2px dashed #ccc;text-align:center;color:#999;">Tutorial Header — visible on Tutorial posts</div>';
            }
            return;
        }

        $cats       = get_the_terms( $post_id, 'tutorial_category' );
        $views      = (int) get_post_meta( $post_id, '_ctt_views', true );
        $github_url = get_post_meta( $post_id, '_ctt_github_url', true );

        echo '<div class="ct-single-header ct-elementor-widget">';

        // Breadcrumb
        if ( 'yes' === $settings['show_breadcrumb'] ) {
            echo '<nav class="ct-breadcrumb">';
            echo '<a href="' . esc_url( home_url() ) . '">Home</a> &rsaquo; ';
            echo '<a href="' . esc_url( get_post_type_archive_link( 'tutorial' ) ) . '">Tutorials</a>';
            if ( $cats && ! is_wp_error( $cats ) ) {
                echo ' &rsaquo; <a href="' . esc_url( get_term_link( $cats[0] ) ) . '">' . esc_html( $cats[0]->name ) . '</a>';
            }
            echo ' &rsaquo; ' . esc_html( get_the_title() );
            echo '</nav>';
        }

        // Meta row
        $has_meta = array_intersect( array_keys( array_filter( $settings, fn($v) => $v === 'yes' ) ),
            [ 'show_tool_badges', 'show_difficulty', 'show_duration', 'show_version', 'show_views', 'show_date' ] );
        if ( $has_meta ) {
            echo '<div class="ct-meta">';
            if ( 'yes' === $settings['show_tool_badges'] )  echo wp_kses_post( ctt_tool_badges( $post_id ) );
            if ( 'yes' === $settings['show_difficulty'] )   echo wp_kses_post( ctt_difficulty_badge( $post_id ) );
            if ( 'yes' === $settings['show_duration'] )     echo wp_kses_post( ctt_duration_chip( $post_id ) );
            if ( 'yes' === $settings['show_version'] )      echo wp_kses_post( ctt_version_chip( $post_id ) );
            if ( 'yes' === $settings['show_views'] && $views ) {
                echo '<span class="ct-meta__chip">👁 ' . esc_html( number_format( $views ) ) . ' views</span>';
            }
            if ( 'yes' === $settings['show_date'] ) {
                echo '<span class="ct-meta__chip">🗓 ' . esc_html( get_the_date() ) . '</span>';
            }
            echo '</div>';
        }

        // Title
        if ( 'yes' === $settings['show_title'] ) {
            echo '<h1 class="ct-widget-title">' . esc_html( get_the_title() ) . '</h1>';
        }

        // Excerpt
        if ( 'yes' === $settings['show_excerpt'] && has_excerpt() ) {
            echo '<p class="ct-widget-excerpt">' . wp_kses_post( get_the_excerpt() ) . '</p>';
        }

        // Featured image
        if ( 'yes' === $settings['show_featured_image'] && has_post_thumbnail() ) {
            echo get_the_post_thumbnail( $post_id, 'ct-featured', [ 'class' => 'ct-featured-image' ] );
        }

        // GitHub button
        if ( 'yes' === $settings['show_github_button'] && $github_url ) {
            echo '<a href="' . esc_url( $github_url ) . '" target="_blank" rel="noopener" class="ct-github-btn">';
            echo '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C8.422 19.57 7.635 19.2 7.635 19.2c-1.09-.744.083-.729.083-.729 1.205.084 1.84 1.237 1.84 1.237 1.07 1.834 2.807 1.304 3.492.997.108-.775.418-1.305.762-1.605-2.665-.3-5.467-1.332-5.467-5.93 0-1.31.468-2.382 1.236-3.222-.124-.303-.536-1.524.117-3.176 0 0 1.008-.322 3.3 1.23A11.51 11.51 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.29-1.552 3.297-1.23 3.297-1.23.653 1.652.241 2.873.118 3.176.77.84 1.235 1.91 1.235 3.222 0 4.61-2.807 5.625-5.479 5.922.43.372.823 1.102.823 2.222 0 1.606-.015 2.898-.015 3.293 0 .322.216.694.825.576C20.565 21.796 24 17.298 24 12c0-6.63-5.37-12-12-12z"/></svg>';
            echo ' View Project Files</a>';
        }

        echo '</div>'; // .ct-single-header
    }
}
