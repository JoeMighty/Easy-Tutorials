<?php
/**
 * Elementor Widget: Tutorial Navigation
 * Displays prev / next tutorial links.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class CT_Widget_Tutorial_Navigation extends \Elementor\Widget_Base {

    public function get_name()  { return 'ct_tutorial_navigation'; }
    public function get_title() { return __( 'Tutorial – Navigation', 'easy-tutorials' ); }
    public function get_icon()  { return 'eicon-post-navigation'; }
    public function get_categories() { return [ 'easy-tutorials' ]; }
    public function get_keywords() { return [ 'tutorial', 'navigation', 'prev', 'next' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Options', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        $this->add_control( 'show_divider', [
            'label'        => __( 'Show Divider', 'easy-tutorials' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ] );
        $this->add_control( 'prev_label', [
            'label'   => __( 'Prev Label', 'easy-tutorials' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '← ',
        ] );
        $this->add_control( 'next_label', [
            'label'   => __( 'Next Label', 'easy-tutorials' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => ' →',
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'section_style', [
            'label' => __( 'Button Style', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'btn_bg', [
            'label'     => __( 'Background', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-nav-btn' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'btn_color', [
            'label'     => __( 'Text Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-nav-btn' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'btn_typography',
            'selector' => '{{WRAPPER}} .ct-nav-btn',
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id  = get_the_ID();

        if ( ! $post_id || get_post_type( $post_id ) !== 'tutorial' ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div style="padding:20px;border:2px dashed #ccc;text-align:center;color:#999;">Tutorial Navigation — visible on Tutorial posts</div>';
            }
            return;
        }

        $prev = get_previous_post();
        $next = get_next_post();

        if ( ! $prev && ! $next ) return;

        $divider_style = 'yes' === $settings['show_divider']
            ? 'border-top: 2px solid var(--ct-border, #e5e7eb); padding-top: 28px; margin-top: 48px;'
            : '';

        echo '<div class="ct-tutorial-nav ct-elementor-widget" style="' . esc_attr( $divider_style ) . '">';
        echo '<div class="ct-nav-row">';
        echo '<div class="ct-nav-prev">';
        if ( $prev ) {
            echo '<a href="' . esc_url( get_permalink( $prev->ID ) ) . '" class="ct-nav-btn">';
            echo esc_html( $settings['prev_label'] ) . esc_html( $prev->post_title );
            echo '</a>';
        }
        echo '</div>';
        echo '<div class="ct-nav-next">';
        if ( $next ) {
            echo '<a href="' . esc_url( get_permalink( $next->ID ) ) . '" class="ct-nav-btn">';
            echo esc_html( $next->post_title ) . esc_html( $settings['next_label'] );
            echo '</a>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
