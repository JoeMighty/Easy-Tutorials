<?php
/**
 * Elementor Widget: Tutorial Content
 * Renders the main post content (the_content).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class CT_Widget_Tutorial_Content extends \Elementor\Widget_Base {

    public function get_name()  { return 'ct_tutorial_content'; }
    public function get_title() { return __( 'Tutorial – Content', 'easy-tutorials' ); }
    public function get_icon()  { return 'eicon-post-content'; }
    public function get_categories() { return [ 'easy-tutorials' ]; }
    public function get_keywords() { return [ 'tutorial', 'content', 'body', 'post' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'section_style', [
            'label' => __( 'Typography', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'content_typography',
            'selector' => '{{WRAPPER}} .ct-body',
        ] );
        $this->add_control( 'content_color', [
            'label'     => __( 'Text Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-body' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $post_id = get_the_ID();

        if ( ! $post_id || get_post_type( $post_id ) !== 'tutorial' ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div style="padding:20px;border:2px dashed #ccc;text-align:center;color:#999;">Tutorial Content — visible on Tutorial posts</div>';
            }
            return;
        }

        echo '<div class="ct-body ct-elementor-widget">';
        // Must set up post data if called outside the loop
        global $post;
        setup_postdata( $post );
        the_content();
        wp_reset_postdata();
        echo '</div>';
    }
}
