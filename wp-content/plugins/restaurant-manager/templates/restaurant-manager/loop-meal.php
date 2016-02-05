<?php

global $syn_restaurant_config;

$post_id = get_the_ID();

$currency_symbol = get_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', '£');
$full_price = get_post_meta($post_id, 'full_price', true);
$small_plate_price = get_post_meta($post_id, 'small_plate_price', true);
$display_pricing = get_post_meta($post_id, 'display_pricing', true);
$new_addition = get_post_meta($post_id, 'new_addition', true);
$calorie_count = get_post_meta($post_id, 'calorie_count', true);
$healthy_option = get_post_meta($post_id, 'healthy_option', true);
$gluten_free = get_post_meta($post_id, 'gluten_free', true);
$spice_rating = get_post_meta($post_id, 'spice_rating', true);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if (!is_page_template('page-templates/front-page.php')) {
            the_post_thumbnail();
        }
        ?>     
        <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" itemprop="headline"><?php the_title(); ?></a></h1>
    </header>

    <div class="syn-menu-properties">
        <span class="syn-small-price">
            <?php echo __('Small Plate', 'syn_restaurant_plugin') . ' ' . $currency_symbol . $small_plate_price ?>
        </span>
        <span class="syn-calorie-count">
            <?php echo $calorie_count . __('(kcal)', 'syn_restaurant_plugin') ?>
        </span>
        <span class="syn-healthy-option">
            <?php echo (($healthy_option === 'on') ? __('Healthy Option', 'syn_restaurant_plugin') : '') ?>
        </span>
        <span class="syn-gluten-free">
            <?php echo (($gluten_free === 'on') ? __('Gluten Free', 'syn_restaurant_plugin') : '') ?>
        </span>
        <span class="syn-spice-rating">
            <?php echo syn_restaurant_menus_get_spice_rating($spice_rating) ?>
        </span>
    </div>

    <div class="entry-content">
        <?php the_content(); ?>
        <?php wp_link_pages(array('before' => '<div class="page-links">' . __('Pages:', 'syn_restaurant_plugin'), 'after' => '</div>')); ?>
    </div><!-- .entry-content -->
    <footer class="entry-meta">
        <?php edit_post_link(__('Edit', 'syn_restaurant_plugin'), '<span class="edit-link">', '</span>'); ?>
    </footer><!-- .entry-meta -->
</article><!-- #post -->