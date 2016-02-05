<?php
/**
 * The Template for displaying all single posts meals.
 *
 * @package WordPress
 * @subpackage Restaurant Menus Plugin
 * @since Restaurant Menus 1.0
 */
get_header();

$template_locator = new \syntaxthemes\restaurant\template_locator();
?>

<!--#client container-->
<div id="container" class="site-content diet-template">

    <!--#content-->
    <div id="content" role="main">
        <?php
        $args = array(
            'post_type' => 'syn_rest_meal',
            'syn_rest_diet' => get_query_var('syn_rest_diet')
        );

        $query = new WP_Query($args);

        while ($query->have_posts()) {
            $query->the_post();

            $template_locator->get_template_part('loop', 'meal');        
        }
        ?>
    </div><!--/#content-->

</div><!--/#client container-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>