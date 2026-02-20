<?php
/**
 * Elementor Widget: Tutorial Meta Bar
 * A standalone row of meta chips: tools, difficulty, duration, version, views, date.
 * Use this when you want the meta separate from the header.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class CT_Widget_Tutorial_Meta extends \Elementor\Widget_Base {

    public function get_name()  { return 'ct_tutorial_meta'; }
    public function get_title() { return __( 'Tutorial – Meta Bar', 'easy-tutorials' ); }
    public function get_icon()  { return 'eicon-post-excerpt'; }
    public function get_categories() { return [ 'easy-tutorials' ]; }
    public function get_keywords() { return [ 'tutorial', 'meta', 'tool', 'difficulty', 'duration', 'views' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Show / Hide', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        foreach ( [
            'show_tool'       => 'Tool Badges',
            'show_difficulty' => 'Difficulty',
            'show_duration'   => 'Duration',
            'show_version'    => 'Version',
            'show_views'      => 'Views',
            'show_date'       => 'Date',
        ] as $key => $label ) {
            $this->add_control( $key, [
                'label'        => $label,
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ] );
        }
        $this->end_controls_section();

        $this->start_controls_section( 'section_style', [
            'label' => __( 'Style', 'easy-tutorials' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'chip_bg', [
            'label'     => __( 'Chip Background', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-meta__chip' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'chip_color', [
            'label'     => __( 'Chip Color', 'easy-tutorials' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-meta__chip' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'meta_gap', [
            'label'      => __( 'Gap', 'easy-tutorials' ),
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
                echo '<div style="padding:20px;border:2px dashed #ccc;text-align:center;color:#999;">Tutorial Meta Bar — visible on Tutorial posts</div>';
            }
            return;
        }

        $views = (int) get_post_meta( $post_id, '_ctt_views', true );

        echo '<div class="ct-meta ct-elementor-widget">';
        if ( 'yes' === $settings['show_tool'] )       echo wp_kses_post( ctt_tool_badges( $post_id ) );
        if ( 'yes' === $settings['show_difficulty'] )  echo wp_kses_post( ctt_difficulty_badge( $post_id ) );
        if ( 'yes' === $settings['show_duration'] )    echo wp_kses_post( ctt_duration_chip( $post_id ) );
        if ( 'yes' === $settings['show_version'] )     echo wp_kses_post( ctt_version_chip( $post_id ) );
        if ( 'yes' === $settings['show_views'] && $views ) {
            echo '<span class="ct-meta__chip">👁 ' . esc_html( number_format( $views ) ) . ' views</span>';
        }
        if ( 'yes' === $settings['show_date'] ) {
            echo '<span class="ct-meta__chip">🗓 ' . esc_html( get_the_date() ) . '</span>';
        }
        echo '</div>';
    }
}
