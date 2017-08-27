<?php
/*
Plugin Name: Custom post plugin
Description: Displays a tabbed list with recent and popular posts.
Version: 1.0
Author: Jurgen Mone
*/
function wptuts_scripts_important()
{
  wp_register_script( 'custom-script', plugins_url( '/js/custom-script.js', __FILE__ ) );

  wp_enqueue_script( 'custom-script' );
}
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_important', 5 );

function wptuts_styles_with_the_lot()
{
  wp_register_style( 'custom-style', plugins_url( '/css/custom-style.css', __FILE__ ), array(), '20120208', 'all' );

  wp_enqueue_style( 'custom-style' );
}
add_action( 'wp_enqueue_scripts', 'wptuts_styles_with_the_lot' );


class CustomPostWidget extends WP_Widget {
  function CustomPostWidget() {
    parent::WP_Widget(false, $name = 'Custom Post Widget');
  }

  function form($instance) {
    $title = esc_attr($instance['title']);
    $dis_posts = esc_attr($instance['dis_posts']);
    ?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
    <p><label for="<?php echo $this->get_field_id('dis_posts'); ?>"><?php _e('Number of Posts Displayed:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('dis_posts'); ?>" name="<?php echo $this->get_field_name('dis_posts'); ?>" type="text" value="<?php echo $dis_posts; ?>" /></label></p>
    <?php
  }

  function widget($args, $instance) {
    extract( $args );
    $title = apply_filters('widget_title', $instance['title']);
    $dis_posts = $instance['dis_posts'];
    ?>
    <?php echo $before_widget; ?>
    <?php if ( $title )
    echo $before_title . $title . $after_title; ?>
    <div class="wdgTitle">Tabbed Content Widget</div>
    <div class="tab">
      <button class="tablinks active" onclick="openPostTab(event, 'Recent')">Recent</button>
      <button class="tablinks" onclick="openPostTab(event, 'Popular')">Popular</button>
    </div>
    <div id="Recent" class="tabcontent" style="display:block;">
      <ul>
        <?php
        global $post;
        $args = array( 'numberposts' => $dis_posts);
        $myposts = get_posts( $args );
        foreach( $myposts as $post ) : setup_postdata($post); ?>
        <div class="content">
          <li class="psWidget">
            <img src="<?php the_post_thumbnail_url(); ?>"/>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?>
              <p><?php the_time('F j, Y'); ?></p>
            </a>
          </li>
        </div>
      <?php endforeach; ?>
    </ul>
  </div>
  <div id="Popular" class="tabcontent">
    <ul>
      <?php
      //Specifies that we want to display category=>3 posts (popular)
      $args = array( 'numberposts' => $dis_posts, 'category' => 3);
      $myposts = get_posts( $args );
      foreach( $myposts as $post ) : setup_postdata($post); ?>
      <div class="content">
        <li class="psWidget">
          <img src="<?php the_post_thumbnail_url(); ?>"/>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?>
            <p><?php the_time('F j, Y'); ?></p>
          </a>
        </li>
      </div>
    <?php endforeach; ?>
  </ul>
</div>
<?php echo $after_widget; ?>
<?php
}
}?>
<?php add_action('widgets_init', create_function('', 'return register_widget("CustomPostWidget");')); ?>
