<?php
/**
 * Elementor Widget: Tutorial Categories
 * Displays tutorial_category taxonomy links.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class CT_Widget_Tutorial_Categories extends \Elementor\Widget_Base {

    public function get_name()  { return 'ct_tutorial_categories'; }
    public function get_title() { return __( 'Tutorial – Categories', 'easy-tutorials' ); }
    public function get_icon()  { return 'eicon-folder'; }
    public function get_categories() { return [ 'easy-tutorials' ]; }
    public function get_keywords() { return [ 'tutorial', 'categories', 'taxonomy', 'category' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Options', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        $this->add_control( 'show_label', [
            'label'        => __( 'Show Label', 'easy-tutorials' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ] );
        $this->add_control( 'label_text', [
            'label'     => __( 'Label', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => '📁 Categories',
            'condition' => [ 'show_label' => 'yes' ],
        ] );
        $this->add_control( 'display_style', [
            'label'   => __( 'Display Style', 'easy-tutorials' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'badges',
            'options' => [
                'badges' => 'Badges',
                'list'   => 'List',
                'inline' => 'Inline Text',
            ],
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'section_style', [
            'label' => __( 'Style', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'badge_bg', [
            'label'     => __( 'Badge Background', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-cat-badge' => 'background: {{VALUE}};' ],
            'condition' => [ 'display_style' => 'badges' ],
        ] );
        $this->add_control( 'badge_color', [
            'label'     => __( 'Badge Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-cat-badge' => 'color: {{VALUE}};' ],
            'condition' => [ 'display_style' => 'badges' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id  = get_the_ID();

        if ( ! $post_id || get_post_type( $post_id ) !== 'tutorial' ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div style="padding:20px;border:2px dashed #ccc;text-align:center;color:#999;">Tutorial Categories — visible on Tutorial posts</div>';
            }
            return;
        }

        $cats = get_the_terms( $post_id, 'tutorial_category' );
        if ( ! $cats || is_wp_error( $cats ) ) return;

        echo '<div class="ct-tutorial-categories ct-elementor-widget">';

        if ( 'yes' === $settings['show_label'] && $settings['label_text'] ) {
            echo '<span class="ct-cats-label">' . esc_html( $settings['label_text'] ) . '</span>';
        }

        $style = $settings['display_style'];

        if ( $style === 'list' ) {
            echo '<ul class="ct-cat-list">';
            foreach ( $cats as $cat ) {
                echo '<li><a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a></li>';
            }
            echo '</ul>';
        } elseif ( $style === 'inline' ) {
            $links = array_map( function( $cat ) {
                return '<a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
            }, $cats );
            echo '<span class="ct-cats-inline">' . implode( ', ', array_map( 'wp_kses_post', $links ) ) . '</span>';
        } else {
            echo '<div class="ct-cat-badges">';
            foreach ( $cats as $cat ) {
                echo '<a href="' . esc_url( get_term_link( $cat ) ) . '" class="ct-cat-badge">' . esc_html( $cat->name ) . '</a>';
            }
            echo '</div>';
        }

        echo '</div>';
    }
}
