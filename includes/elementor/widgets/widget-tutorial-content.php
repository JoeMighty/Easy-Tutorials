<?php
/**
 * Elementor Widget: Tutorial Content
 * Renders the main post content with configurable code block
 * styling and an optional Copy to Clipboard button.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class CT_Widget_Tutorial_Content extends \Elementor\Widget_Base {

    public function get_name()       { return 'ct_tutorial_content'; }
    public function get_title()      { return __( 'Tutorial – Content', 'easy-tutorials' ); }
    public function get_icon()       { return 'eicon-post-content'; }
    public function get_categories() { return [ 'easy-tutorials' ]; }
    public function get_keywords()   { return [ 'tutorial', 'content', 'body', 'post', 'code' ]; }

    protected function register_controls() {

        /* ── CONTENT TAB: Code Block Options ─────────────── */
        $this->start_controls_section( 'section_code_options', [
            'label' => __( 'Code Blocks', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'show_copy_button', [
            'label'        => __( 'Show Copy Button', 'easy-tutorials' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'easy-tutorials' ),
            'label_off'    => __( 'No', 'easy-tutorials' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->add_control( 'copy_button_label', [
            'label'     => __( 'Button Label', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => 'Copy',
            'condition' => [ 'show_copy_button' => 'yes' ],
        ] );

        $this->add_control( 'copy_success_label', [
            'label'     => __( '"Copied!" Label', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => '✓ Copied!',
            'condition' => [ 'show_copy_button' => 'yes' ],
        ] );

        $this->end_controls_section();

        /* ── STYLE TAB: Body Typography ───────────────────── */
        $this->start_controls_section( 'section_style', [
            'label' => __( 'Body Text', 'easy-tutorials' ),
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

        /* ── STYLE TAB: Code Block ────────────────────────── */
        $this->start_controls_section( 'section_code_style', [
            'label' => __( 'Code Blocks', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'code_bg_color', [
            'label'     => __( 'Background Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#1e2430',
            'selectors' => [ '{{WRAPPER}} .ct-body pre' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_control( 'code_text_color', [
            'label'     => __( 'Code Text Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#e8eaf0',
            'selectors' => [
                '{{WRAPPER}} .ct-body pre'      => 'color: {{VALUE}};',
                '{{WRAPPER}} .ct-body pre code' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'code_font_size', [
            'label'      => __( 'Font Size', 'easy-tutorials' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 24 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 14 ],
            'selectors'  => [ '{{WRAPPER}} .ct-body pre, {{WRAPPER}} .ct-body pre code' => 'font-size: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'code_border_radius', [
            'label'      => __( 'Corner Radius', 'easy-tutorials' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 24 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 8 ],
            'selectors'  => [ '{{WRAPPER}} .ct-body pre' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );

        /* Copy Button Style */
        $this->add_control( 'copy_btn_heading', [
            'label'     => __( 'Copy Button', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'show_copy_button' => 'yes' ],
        ] );

        $this->add_control( 'copy_btn_bg', [
            'label'     => __( 'Button Background', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#00a8e1',
            'selectors' => [ '{{WRAPPER}} .ct-copy-btn' => 'background-color: {{VALUE}};' ],
            'condition' => [ 'show_copy_button' => 'yes' ],
        ] );

        $this->add_control( 'copy_btn_text_color', [
            'label'     => __( 'Button Text Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .ct-copy-btn' => 'color: {{VALUE}};' ],
            'condition' => [ 'show_copy_button' => 'yes' ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id  = get_the_ID();

        if ( ! $post_id || get_post_type( $post_id ) !== 'tutorial' ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="ct-elementor-editor-placeholder">Tutorial Content — visible on Tutorial posts</div>';
            }
            return;
        }

        $show_copy    = ( $settings['show_copy_button'] === 'yes' );
        $copy_label   = ! empty( $settings['copy_button_label'] )  ? $settings['copy_button_label']  : 'Copy';
        $copied_label = ! empty( $settings['copy_success_label'] ) ? $settings['copy_success_label'] : '&#10003; Copied!';

        printf(
            '<div class="ct-body ct-elementor-widget" data-copy="%s" data-copy-label="%s" data-copied-label="%s">',
            esc_attr( $show_copy ? 'yes' : 'no' ),
            esc_attr( $copy_label ),
            esc_attr( $copied_label )
        );

        global $post;
        setup_postdata( $post );
        the_content();
        wp_reset_postdata();

        echo '</div>';
    }
}
