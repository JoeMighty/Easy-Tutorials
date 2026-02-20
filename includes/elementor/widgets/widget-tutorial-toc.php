<?php
/**
 * Elementor Widget: Tutorial – Table of Contents
 * Scans the post content for H2/H3 headings, injects anchor IDs into the
 * rendered content, and outputs a linked table of contents.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class CT_Widget_Tutorial_TOC extends \Elementor\Widget_Base {

    public function get_name()  { return 'ct_tutorial_toc'; }
    public function get_title() { return __( 'Tutorial – Table of Contents', 'easy-tutorials' ); }
    public function get_icon()  { return 'eicon-table-of-contents'; }
    public function get_categories() { return [ 'easy-tutorials' ]; }
    public function get_keywords() { return [ 'tutorial', 'toc', 'table of contents', 'headings', 'navigation', 'anchor' ]; }

    protected function register_controls() {

        // ── Content ──────────────────────────────────────────
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'title', [
            'label'   => __( 'Box Title', 'easy-tutorials' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'In This Tutorial',
        ] );

        $this->add_control( 'show_title', [
            'label'        => __( 'Show Title', 'easy-tutorials' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ] );

        $this->add_control( 'include_h2', [
            'label'        => __( 'Include H2 headings', 'easy-tutorials' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ] );

        $this->add_control( 'include_h3', [
            'label'        => __( 'Include H3 headings (indented)', 'easy-tutorials' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ] );

        $this->add_control( 'show_numbers', [
            'label'        => __( 'Number the items', 'easy-tutorials' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ] );

        $this->add_control( 'show_toggle', [
            'label'        => __( 'Collapsible (show/hide toggle)', 'easy-tutorials' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => 'no',
            'return_value' => 'yes',
        ] );

        $this->end_controls_section();

        // ── Style: Box ────────────────────────────────────────
        $this->start_controls_section( 'section_style_box', [
            'label' => __( 'Box', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'box_bg', [
            'label'     => __( 'Background', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#f8f9ff',
            'selectors' => [ '{{WRAPPER}} .ct-toc' => 'background: {{VALUE}};' ],
        ] );

        $this->add_control( 'box_border_color', [
            'label'     => __( 'Border Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#c7d5ff',
            'selectors' => [ '{{WRAPPER}} .ct-toc' => 'border-left-color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'box_padding', [
            'label'      => __( 'Padding', 'easy-tutorials' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [ '{{WRAPPER}} .ct-toc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_control( 'box_border_radius', [
            'label'      => __( 'Border Radius', 'easy-tutorials' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 10 ],
            'selectors'  => [ '{{WRAPPER}} .ct-toc' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();

        // ── Style: Title ──────────────────────────────────────
        $this->start_controls_section( 'section_style_title', [
            'label'     => __( 'Title', 'easy-tutorials' ),
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_title' => 'yes' ],
        ] );

        $this->add_control( 'title_color', [
            'label'     => __( 'Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-toc__title' => 'color: {{VALUE}};' ],
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .ct-toc__title',
        ] );

        $this->end_controls_section();

        // ── Style: Links ──────────────────────────────────────
        $this->start_controls_section( 'section_style_links', [
            'label' => __( 'Links', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'link_color', [
            'label'     => __( 'Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-toc__list a' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'link_hover_color', [
            'label'     => __( 'Hover Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-toc__list a:hover' => 'color: {{VALUE}};' ],
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'link_typography',
            'selector' => '{{WRAPPER}} .ct-toc__list a',
        ] );

        $this->add_control( 'item_gap', [
            'label'      => __( 'Gap between items', 'easy-tutorials' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 8 ],
            'selectors'  => [ '{{WRAPPER}} .ct-toc__list li' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'h3_indent', [
            'label'      => __( 'H3 indent', 'easy-tutorials' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 20 ],
            'selectors'  => [ '{{WRAPPER}} .ct-toc__list li.ct-toc__h3' => 'padding-left: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();
    }

    /**
     * Extract headings from post content, returning array of
     * [ 'level' => 2|3, 'text' => '...', 'slug' => '...' ]
     */
    private function get_headings( $post_id, $include_h2, $include_h3 ) {
        $post    = get_post( $post_id );
        $content = apply_filters( 'the_content', $post->post_content );

        $headings = [];
        $pattern  = '/<h([23])(?:[^>]*)>(.*?)<\/h\1>/is';

        if ( preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER ) ) {
            $slug_counts = [];
            foreach ( $matches as $match ) {
                $level = (int) $match[1];
                if ( $level === 2 && $include_h2 !== 'yes' ) continue;
                if ( $level === 3 && $include_h3 !== 'yes' ) continue;

                $text = wp_strip_all_tags( $match[2] );
                $slug = sanitize_title( $text );

                // De-duplicate slugs
                if ( isset( $slug_counts[ $slug ] ) ) {
                    $slug_counts[ $slug ]++;
                    $slug = $slug . '-' . $slug_counts[ $slug ];
                } else {
                    $slug_counts[ $slug ] = 0;
                }

                $headings[] = [ 'level' => $level, 'text' => $text, 'slug' => $slug ];
            }
        }

        return $headings;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id  = get_the_ID();

        if ( ! $post_id || get_post_type( $post_id ) !== 'tutorial' ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="ct-elementor-editor-placeholder">Table of Contents — visible on Tutorial posts once headings exist in the content.</div>';
            }
            return;
        }

        $headings = $this->get_headings( $post_id, $settings['include_h2'], $settings['include_h3'] );

        if ( empty( $headings ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="ct-elementor-editor-placeholder">Table of Contents — no H2/H3 headings found in this post\'s content yet.</div>';
            }
            return;
        }

        $show_numbers = 'yes' === $settings['show_numbers'];
        $collapsible  = 'yes' === $settings['show_toggle'];
        $widget_id    = 'ct-toc-' . $this->get_id();

        echo '<div class="ct-toc ct-elementor-widget" id="' . esc_attr( $widget_id ) . '">';

        // Title row
        if ( 'yes' === $settings['show_title'] ) {
            $title = esc_html( $settings['title'] ?: 'In This Tutorial' );
            if ( $collapsible ) {
                echo '<div class="ct-toc__header" onclick="ctTocToggle(\'' . esc_js( $widget_id ) . '\')" style="cursor:pointer;user-select:none;">';
                echo '<span class="ct-toc__title">' . esc_html( $title ) . '</span>';
                echo '<span class="ct-toc__toggle-icon" id="' . esc_attr( $widget_id ) . '-icon">▾</span>';
                echo '</div>';
            } else {
                echo '<div class="ct-toc__header">';
                echo '<span class="ct-toc__title">' . esc_html( $title ) . '</span>';
                echo '</div>';
            }
        }

        // List
        $list_id = esc_attr( $widget_id ) . '-list';
        echo '<ol class="ct-toc__list" id="' . esc_attr( $list_id ) . '">';

        $h2_counter = 0;
        $h3_counter = 0;

        foreach ( $headings as $heading ) {
            $level     = $heading['level'];
            $text      = esc_html( $heading['text'] );
            $slug      = esc_attr( $heading['slug'] );
            $li_class  = $level === 3 ? ' ct-toc__h3' : ' ct-toc__h2';
            $number    = '';

            if ( $show_numbers ) {
                if ( $level === 2 ) {
                    $h2_counter++;
                    $h3_counter = 0;
                    $number = '<span class="ct-toc__num">' . $h2_counter . '.</span> ';
                } else {
                    $h3_counter++;
                    $number = '<span class="ct-toc__num ct-toc__num--sub">' . $h2_counter . '.' . $h3_counter . '</span> ';
                }
            }

            echo '<li class="' . esc_attr( trim( $li_class ) ) . '">';
            echo '<a href="#' . esc_attr( $slug ) . '">' . wp_kses_post( $number ) . esc_html( $text ) . '</a>';
            echo '</li>';
        }

        echo '</ol>';
        echo '</div>'; // .ct-toc

        // Inject JS for smooth scroll + anchors + optional collapse
        // Only output once per page
        static $ct_toc_js_output = false;
        if ( ! $ct_toc_js_output ) {
            $ct_toc_js_output = true;
            ?>
            <script>
            (function() {
                // Inject anchor IDs into matching headings in .ct-body
                document.addEventListener('DOMContentLoaded', function() {
                    var body = document.querySelector('.ct-body') || document.body;
                    var headings = body.querySelectorAll('h2, h3');
                    var slugCounts = {};

                    headings.forEach(function(el) {
                        var text = el.textContent.trim();
                        var slug = text.toLowerCase()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/[\s_]+/g, '-')
                            .replace(/^-+|-+$/g, '');

                        if (slugCounts[slug] !== undefined) {
                            slugCounts[slug]++;
                            slug = slug + '-' + slugCounts[slug];
                        } else {
                            slugCounts[slug] = 0;
                        }

                        if (!el.id) el.id = slug;
                    });

                    // Smooth scroll for all TOC links
                    document.querySelectorAll('.ct-toc__list a').forEach(function(link) {
                        link.addEventListener('click', function(e) {
                            var target = document.getElementById(this.getAttribute('href').replace('#', ''));
                            if (target) {
                                e.preventDefault();
                                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                history.pushState(null, null, this.getAttribute('href'));
                            }
                        });
                    });
                });
            })();

            function ctTocToggle(widgetId) {
                var list = document.getElementById(widgetId + '-list');
                var icon = document.getElementById(widgetId + '-icon');
                if (!list) return;
                var hidden = list.style.display === 'none';
                list.style.display = hidden ? '' : 'none';
                if (icon) icon.textContent = hidden ? '▾' : '▸';
            }
            </script>
            <?php
        }
    }
}
