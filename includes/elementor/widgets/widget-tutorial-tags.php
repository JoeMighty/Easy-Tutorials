<?php
/**
 * Elementor Widget: Tutorial Tags
 * Displays the tutorial_tag taxonomy links.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class CT_Widget_Tutorial_Tags extends \Elementor\Widget_Base {

    public function get_name()  { return 'ct_tutorial_tags'; }
    public function get_title() { return __( 'Tutorial – Tags', 'easy-tutorials' ); }
    public function get_icon()  { return 'eicon-tags'; }
    public function get_categories() { return [ 'easy-tutorials' ]; }
    public function get_keywords() { return [ 'tutorial', 'tags', 'taxonomy' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        $this->add_control( 'label_text', [
            'label'   => __( 'Label', 'easy-tutorials' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Tags',
        ] );
        $this->add_control( 'show_label', [
            'label'        => __( 'Show Label', 'easy-tutorials' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'section_style', [
            'label' => __( 'Tag Style', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'tag_bg', [
            'label'     => __( 'Background', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-tag' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'tag_color', [
            'label'     => __( 'Text Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-tag' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'tag_typography',
            'selector' => '{{WRAPPER}} .ct-tag',
        ] );
        $this->add_control( 'tag_border_radius', [
            'label'      => __( 'Border Radius', 'easy-tutorials' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'selectors'  => [ '{{WRAPPER}} .ct-tag' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id  = get_the_ID();

        if ( ! $post_id || get_post_type( $post_id ) !== 'tutorial' ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div style="padding:20px;border:2px dashed #ccc;text-align:center;color:#999;">Tutorial Tags — visible on Tutorial posts</div>';
            }
            return;
        }

        $tags_html = ct_tags_html( $post_id );
        if ( ! $tags_html ) return;

        echo '<div class="ct-elementor-widget">';
        if ( 'yes' === $settings['show_label'] && $settings['label_text'] ) {
            echo '<span class="ct-tags-label">' . esc_html( $settings['label_text'] ) . '</span>';
        }
        echo wp_kses_post( $tags_html );
        echo '</div>';
    }
}
