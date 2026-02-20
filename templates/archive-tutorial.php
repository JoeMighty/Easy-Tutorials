<?php
/**
 * Tutorial Archive — Creative Tech Tutorials
 * Filters: Tool, Difficulty, Category
 *
 * @package Creative_Tech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$all_tools        = get_terms( [ 'taxonomy' => 'tool',              'hide_empty' => false ] );
$all_difficulties = get_terms( [ 'taxonomy' => 'difficulty',        'hide_empty' => false ] );
$all_categories   = get_terms( [ 'taxonomy' => 'tutorial_category', 'hide_empty' => false ] );
$archive_url      = get_post_type_archive_link( 'tutorial' );
$queried          = get_queried_object();

?>

<!-- ── TOOL PILLS ─────────────────────────────────────────── -->
<div class="ct-tool-pills">
    <a href="<?php echo esc_url( $archive_url ); ?>"
       class="ct-tool-pill ct-tool-pill--all <?php echo ( ! is_tax() ) ? 'active' : ''; ?>">
        All Tools
    </a>

    <?php if ( ! is_wp_error( $all_tools ) ) : foreach ( $all_tools as $tool ) :
        $css  = ctt_tool_css_class( $tool->slug );
        $icon = ctt_tool_icon( $tool->slug );
        $active = is_tax( 'tool', $tool->slug ) ? 'active' : '';
    ?>
        <a href="<?php echo esc_url( get_term_link( $tool ) ); ?>"
           class="ct-tool-pill ct-tool-pill--<?php echo esc_attr( $css ); ?> <?php echo esc_attr( $active ); ?>">
            <?php echo esc_html( $icon ) . ' ' . esc_html( $tool->name ); ?>
        </a>
    <?php endforeach; endif; ?>
</div>

<!-- ── DIFFICULTY PILLS ───────────────────────────────────── -->
<div class="ct-difficulty-filters">
    <a href="<?php echo esc_url( $archive_url ); ?>"
       class="all <?php echo ( ! is_tax( 'difficulty' ) ) ? 'active' : ''; ?>">
        All Levels
    </a>
    <?php if ( ! is_wp_error( $all_difficulties ) ) : foreach ( $all_difficulties as $d ) : ?>
        <a href="<?php echo esc_url( get_term_link( $d ) ); ?>"
           class="<?php echo esc_attr( $d->slug ); ?> <?php echo is_tax( 'difficulty', $d->slug ) ? 'active' : ''; ?>">
            <?php echo esc_html( $d->name ); ?>
        </a>
    <?php endforeach; endif; ?>
</div>

<!-- ── FILTER BAR (Category dropdown) ────────────────────── -->
<div class="ct-filters">
    <div class="ct-filter-group">
        <label for="ct-filter-category">Category</label>
        <select id="ct-filter-category">
            <option value="<?php echo esc_url( $archive_url ); ?>">All Categories</option>
            <?php if ( ! is_wp_error( $all_categories ) ) : foreach ( $all_categories as $cat ) : ?>
                <option value="<?php echo esc_url( get_term_link( $cat ) ); ?>"
                    <?php selected( is_tax( 'tutorial_category', $cat->slug ) ); ?>>
                    <?php echo esc_html( $cat->name ); ?>
                </option>
            <?php endforeach; endif; ?>
        </select>
    </div>

    <div class="ct-filter-group">
        <label for="ct-filter-tool">Tool / Software</label>
        <select id="ct-filter-tool">
            <option value="<?php echo esc_url( $archive_url ); ?>">All Tools</option>
            <?php if ( ! is_wp_error( $all_tools ) ) : foreach ( $all_tools as $tool ) : ?>
                <option value="<?php echo esc_url( get_term_link( $tool ) ); ?>"
                    <?php selected( is_tax( 'tool', $tool->slug ) ); ?>>
                    <?php echo esc_html( ctt_tool_icon( $tool->slug ) ) . ' ' . esc_html( $tool->name ); ?>
                </option>
            <?php endforeach; endif; ?>
        </select>
    </div>

    <div class="ct-filter-group">
        <label for="ct-filter-difficulty">Difficulty</label>
        <select id="ct-filter-difficulty">
            <option value="<?php echo esc_url( $archive_url ); ?>">All Levels</option>
            <?php if ( ! is_wp_error( $all_difficulties ) ) : foreach ( $all_difficulties as $d ) : ?>
                <option value="<?php echo esc_url( get_term_link( $d ) ); ?>"
                    <?php selected( is_tax( 'difficulty', $d->slug ) ); ?>>
                    <?php echo esc_html( $d->name ); ?>
                </option>
            <?php endforeach; endif; ?>
        </select>
    </div>

    <!-- Result count -->
    <div class="ct-filter-group" style="justify-content:flex-end;">
        <label>&nbsp;</label>
        <span style="font-size:13px;color:var(--ct-muted);padding:9px 0;">
            <?php
            global $wp_query;
            $total = $wp_query->found_posts;
            printf( '%d tutorial%s', absint( $total ), absint( $total ) !== 1 ? 's' : '' );
            if ( is_tax() && $queried ) echo ' in <strong>' . esc_html( $queried->name ) . '</strong>';
            ?>
        </span>
    </div>
</div>

<!-- ── TUTORIAL GRID ──────────────────────────────────────── -->
<?php if ( have_posts() ) : ?>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;margin-bottom:48px;">
        <?php while ( have_posts() ) : the_post();

            $cats     = get_the_terms( get_the_ID(), 'tutorial_category' );
            $tags     = get_the_terms( get_the_ID(), 'tutorial_tag' );
            $duration = get_post_meta( get_the_ID(), '_ctt_duration', true );

        ?>
        <article class="ct-card">

            <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail( 'ct-card', [ 'class' => 'ct-card__image' ] ); ?>
                </a>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>">
                    <div class="ct-card__placeholder">
                        <?php
                        $tools = get_the_terms( get_the_ID(), 'tool' );
                        echo ( $tools && ! is_wp_error( $tools ) )
                            ? esc_html( ctt_tool_icon( $tools[0]->slug ) )
                            : '🛠';
                        ?>
                    </div>
                </a>
            <?php endif; ?>

            <div class="ct-card__body">

                <!-- Tool + Difficulty badges -->
                <div class="ct-card__badges">
                    <?php echo wp_kses_post( ctt_tool_badges( null, 2 ) ); ?>
                    <?php echo wp_kses_post( ctt_difficulty_badge() ); ?>
                </div>

                <!-- Title -->
                <h2 class="ct-card__title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>

                <!-- Excerpt -->
                <div class="ct-card__excerpt"><?php the_excerpt(); ?></div>

                <!-- Footer -->
                <div class="ct-card__footer">
                    <div class="ct-card__tags">
                        <?php
                        if ( $tags && ! is_wp_error( $tags ) ) :
                            $n = 0;
                            foreach ( $tags as $tag ) :
                                if ( $n++ >= 3 ) break;
                        ?>
                            <a href="<?php echo esc_url( get_term_link( $tag ) ); ?>" class="ct-tag">
                                <?php echo esc_html( $tag->name ); ?>
                            </a>
                        <?php endforeach; endif; ?>
                    </div>
                    <span><?php echo $duration ? esc_html( '⏱ ' . absint( $duration ) . ' min' ) : ''; ?></span>
                </div>

            </div>
        </article>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div style="text-align:center;">
        <?php the_posts_pagination( [
            'mid_size'  => 2,
            'prev_text' => '← Previous',
            'next_text' => 'Next →',
        ] ); ?>
    </div>

<?php else : ?>
    <div style="text-align:center;padding:60px 20px;">
        <p style="font-size:3rem;">🔍</p>
        <h2>No tutorials found</h2>
        <p style="color:var(--ct-muted);">Try a different filter or check back soon.</p>
        <a href="<?php echo esc_url( $archive_url ); ?>"
           style="display:inline-block;background:var(--ct-blue);color:white;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:700;margin-top:16px;">
            View All Tutorials
        </a>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
