<?php
/**
 * Elementor Widget: Tutorial Requirements
 * Displays the requirements / components box.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class CT_Widget_Tutorial_Requirements extends \Elementor\Widget_Base {

    public function get_name()  { return 'ct_tutorial_requirements'; }
    public function get_title() { return __( 'Tutorial – Requirements', 'easy-tutorials' ); }
    public function get_icon()  { return 'eicon-bullet-list'; }
    public function get_categories() { return [ 'easy-tutorials' ]; }
    public function get_keywords() { return [ 'tutorial', 'requirements', 'components', 'materials' ]; }

    protected function register_controls() {

        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'box_title', [
            'label'   => __( 'Box Title', 'easy-tutorials' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '📦 Requirements',
        ] );

        $this->end_controls_section();

        // Style
        $this->start_controls_section( 'section_style', [
            'label' => __( 'Box Style', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'box_bg', [
            'label'     => __( 'Background', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#f0f4ff',
            'selectors' => [ '{{WRAPPER}} .ct-box' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'box_border_color', [
            'label'     => __( 'Border Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#c7d5ff',
            'selectors' => [ '{{WRAPPER}} .ct-box' => 'border-left-color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'item_typography',
            'label'    => __( 'Item Typography', 'easy-tutorials' ),
            'selector' => '{{WRAPPER}} .ct-box li',
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id  = get_the_ID();

        if ( ! $post_id || get_post_type( $post_id ) !== 'tutorial' ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div style="padding:20px;border:2px dashed #ccc;text-align:center;color:#999;">Tutorial Requirements — visible on Tutorial posts</div>';
            }
            return;
        }

        $raw = get_post_meta( $post_id, '_ctt_components', true );
        if ( ! $raw ) return;

        $items = array_filter( array_map( 'trim', explode( "\n", $raw ) ) );
        $title = ! empty( $settings['box_title'] ) ? esc_html( $settings['box_title'] ) : '📦 Requirements';

        echo '<div class="ct-box ct-elementor-widget">';
        echo '<h3>' . esc_html( $title ) . '</h3>';
        echo '<ul>';
        foreach ( $items as $item ) {
            echo '<li>' . esc_html( $item ) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
}
