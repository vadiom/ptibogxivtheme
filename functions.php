<?php
/*
All the functions are in the PHP pages in the `functions/` folder.
*/
  
  require get_template_directory() . '/update/plugin-update-checker.php';
	require get_template_directory() . '/functions/cleanup.php';
	require get_template_directory() . '/functions/setup.php';
	require get_template_directory() . '/functions/enqueues.php';
	require get_template_directory() . '/functions/navbar.php';
	require get_template_directory() . '/functions/widgets.php';
	require get_template_directory() . '/functions/search-widget.php';
	require get_template_directory() . '/functions/index-pagination.php';
	require get_template_directory() . '/functions/split-post-pagination.php';
	require get_template_directory() . '/functions/feedback.php';
	require get_template_directory() . '/functions/remove-query-string.php';

$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/ptibogxiv/ptibogxivtheme/',
	__FILE__,
	'ptibogxivtheme'
);
$myUpdateChecker->getVcsApi()->enableReleaseAssets();

//deactivate theme-color for superpwa
add_filter( 'superpwa_add_theme_color', '__return_false' );

function ptibogxivtheme_load_theme_textdomain() {
load_theme_textdomain( 'ptibogxivtheme', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'ptibogxivtheme_load_theme_textdomain' );

function ptibogxivtheme_slug_setup() {
    add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'ptibogxivtheme_slug_setup' );

add_theme_support( 'custom-background',array(
	'default-color'          => '',
	'default-image'          => '',
	'default-repeat'         => '',
	'default-position-x'     => '',
	'default-attachment'     => '',
	'wp-head-callback'       => '_custom_background_cb',
	'admin-head-callback'    => '',
	'admin-preview-callback' => ''
) );

add_theme_support( 'custom-header', array(
    'default-image' => '',
    'random-default' => false,
    'width' => 0,
    'height' => 0,
    'flex-height' => false,
    'flex-width' => false,
    'default-text-color' => '',
    'header-text' => true,
    'uploads' => true,
    'wp-head-callback' => '',
    'admin-head-callback' => '',
    'admin-preview-callback' => '',
    'video' => true,
    'video-active-callback' => 'is_front_page',
) );

add_theme_support( 'custom-logo', array(
	'height'      => 30,
	'width'       => 200,
	'flex-height' => false,
	'flex-width'  => true,
	'header-text' => array( 'site-title', 'site-description' ),
) );

function theme_prefix_setup() {
	
	add_theme_support( 'custom-logo', array(
		'height'      => 30,
		'width'       => 200,
		'flex-width' => true,
	) );

}
add_action( 'after_setup_theme', 'theme_prefix_setup' );

function custom_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
 
function wpc_show_admin_bar() { 
global $current_user;
if (current_user_can( 'edit_posts' ) && !get_theme_mod( 'ptibogxivtheme_adminbar') ) {  
return false;
} elseif (current_user_can( 'edit_posts' ) && is_user_logged_in() && $current_user->show_admin_bar_front=='true') {
return true;
}
else {
return false;
}
 }
add_filter('show_admin_bar' , 'wpc_show_admin_bar');

function ptibogxiv_social() {
$return = "<div class='btn-group d-flex' role='group' aria-label='First group'>
<a href='#' class='btn btn-outline-dark disabled w-100' role='button' aria-disabled='true'><i class='fas fa-share-alt fa-fw'></i></a>
<a href='mailto:?subject=[".get_bloginfo('name')."] Informations intéressante&body=Bonjour, ".get_permalink($post->ID)."' role='button' class='btn btn-dark w-100' target='_blank'><i class='fas fa-envelope fa-fw'></i></a>"; 
//<script>//if (navigator.userAgent.match(/iPhone|Android/i)) {
//document.write('<a href='whatsapp://send?text=<?php echo get_permalink($post->ID);' data-action='share/whatsapp/share' role='button' class='btn btn-whatsapp' target='_blank'><i class='fab fa-whatsapp fa-fw'></i></a>');
//}</script>
$return .= "<a href='https://www.facebook.com/sharer/sharer.php?u=".get_permalink($post->ID)."&t=".get_the_title()."' role='button' class='btn btn-facebook w-100' target='_blank'><i class='fab fa-facebook-f fa-fw'></i></a>
<a href='https://twitter.com/intent/tweet?text=".get_the_title()."&url=".get_permalink($post->ID)."&via=".get_option('doliconnect_social_twitter')."' role='button' class='btn btn-twitter w-100' target='_blank'><i class='fab fa-twitter fa-fw'></i></a>
<a href='https://www.linkedin.com/shareArticle?mini=true&url=url=".get_permalink($post->ID)."&title=".get_the_title()."&source=".get_option('doliconnect_social_linkedin')."' role='button' class='btn btn-linkedin w-100' target='_blank'><i class='fab fa-linkedin-in fa-fw'></i></a>
<a href='https://pinterest.com/pin/create/button/?url=".get_permalink($post->ID)."&media=&description=".get_the_title()."' role='button' class='btn btn-pinterest w-100' target='_blank'><i class='fab fa-pinterest fa-fw'></i></a>
</div>";
return $return;
}

function ptibogxiv_alert() {
if ( is_user_logged_in() && function_exists('callAPI') ){ 
$time = current_time( 'timestamp',1);
if ( get_site_option('doliconnect_mode')=='one' && function_exists('switch_to_blog') ) {
switch_to_blog(1);
}
if ( constant("DOLIBARR_MEMBER") > 0 ) {
$adherent = CallAPI("GET", "/adherentsplus/".constant("DOLIBARR_MEMBER"), null, dolidelay( DAY_IN_SECONDS, esc_attr($_GET["refresh"])));
if ( $time>$adherent->datefin && $adherent->statut == '1' && !empty($adherent->datefin) ) {
$alert = "<br><div class='card text-white bg-info'><div class='card-body'>Il semble que votre adhésion a expiré le ".date_i18n('d/m/Y', $adherent->datefin).". Afin de ne pas perdre vos avantages, renouvelez <a href='".esc_url( add_query_arg( 'module', 'membership', doliconnecturl('doliaccount')) )."' class='alert-link'>en cliquant -ici-</a>.</div></div>";
} elseif ( $time>$adherent->datefin && $adherent->statut == '1' && empty($adherent->datefin!=null) ) {
$alert = "<br><div class='card text-white bg-info'><div class='card-body'>Il semble que vous n'avez pas encore réglé votre adhésion. Afin de bénéficier de vos avantages, finalisez <a href='".esc_url( add_query_arg( 'module', 'membership', doliconnecturl('doliaccount')) )."' class='alert-link'>en cliquant -ici-</a>.</div></div>";
} elseif ( $time>$adherent->next_subscription_renew && $time<$adherent->datefin && $adherent->statut == '1' ) {
$alert = "<BR><div class='card text-white bg-info'><div class='card-body'>Il semble que votre adhésion expire le ".date_i18n('d/m/Y', $adherent->datefin).". Afin de ne pas perdre vos avantages, renouvelez <a href='".esc_url( add_query_arg( 'module', 'membership', doliconnecturl('doliaccount')) )."' class='alert-link'>en cliquant -ici-</a>.</div></div>";
}

}
if ( get_site_option('doliconnect_mode')=='one'  && function_exists('switch_to_blog') ) {
restore_current_blog();
}
} 
return $alert;
}

class My_Caroussel extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'my_caroussel',                               
			'description' => 'Articles à la une',
      'customize_selective_refresh' => true,
		);
		parent::__construct( 'my_caroussel', 'Caroussel', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
global $post,$wpdb;
		// outputs the content of the widget
    
  		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

$args = array( 'posts_per_page' => 5, 'meta_query' => array(

    ));
$myposts = get_posts( $args );

echo '<div><div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-interval="4000" data-ride="carousel"><ol class="carousel-indicators">';
$count=-1;
foreach ( $myposts as $post ) {
setup_postdata( $post );
$count = $count+1;
echo '<li data-target="#carouselExampleIndicators" data-slide-to="'.$count.'"';
if ($count=='0') {echo 'class="active"';}
echo '></li>'; 
}
echo '</ol>';
echo '<div class="carousel-inner">';
$count=0;
foreach ( $myposts as $post ) {
setup_postdata( $post );
$count = $count+1;
echo '<div class="carousel-item ';
if ($count =='1') {echo 'active'; }
echo '" ><a href="'.get_permalink($post->ID).'" ><img  class="d-block w-100 img-fluid" src="'.wp_get_attachment_image_url(get_post_thumbnail_id( $post ), 'ptibogxiv_large' ).'" alt="'.$post->post_title.'"></a>
  <div class="carousel-caption"  style="background-color: rgba(0, 0, 0, 0.5)">
    <h4><a href="'.get_permalink($post->ID).'" class="text-white">'.$post->post_title.'</a></h4>
    <small class="text-white"><i class="fas fa-calendar fa-fw"></i> '.__('Post on', 'ptibogxivtheme').' '.get_the_date( '', $post->ID).'</small>
  </div></div>'; 
}
wp_reset_postdata();    
echo '</div>

</div>';

echo '</div></div>';

echo $args['after_widget'];  
    
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Caroussel', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}

add_action( 'widgets_init', function(){
	register_widget( 'My_Caroussel' );
});

add_action('add_meta_boxes','caroussel_metabox');
function caroussel_metabox(){
  add_meta_box('url_crea', 'Caroussel', 'url_crea', 'post', 'side');
}

function url_crea($post){
  $url = get_post_meta($post->ID,'_displaycaroussel',true);
  echo '<label for="url_meta">Afficher dans le carrousel</label>';
  echo '<input id="url_meta" type="text" name="url_site" value="'.$url.'" />';
}

add_action('save_post','save_metabox');
function save_metabox($post_id){
if(isset($_POST['url_site']))
update_post_meta($post_id, '_url_crea', esc_url($_POST['url_site']));
}

function ptibogxivtheme_time_ago() {
global $post;
	
	$date = get_post_time('G', true, $post);
	
	/**
	 * Where you see 'ptibogxivtheme' below, you'd
	 * want to replace those with whatever term
	 * you're using in your theme to provide
	 * support for localization.
	 */ 
	
	// Array of time period chunks
	$chunks = array(
		array( 60 * 60 * 24 * 365 , __( 'year', 'ptibogxivtheme' ), __( 'years', 'ptibogxivtheme' ) ),
		array( 60 * 60 * 24 * 30 , __( 'month', 'ptibogxivtheme' ), __( 'months', 'ptibogxivtheme' ) ),
		array( 60 * 60 * 24 * 7, __( 'week', 'ptibogxivtheme' ), __( 'weeks', 'ptibogxivtheme' ) ),
		array( 60 * 60 * 24 , __( 'day', 'ptibogxivtheme' ), __( 'days', 'ptibogxivtheme' ) ),
		array( 60 * 60 , __( 'hour', 'ptibogxivtheme' ), __( 'hours', 'ptibogxivtheme' ) ),
		array( 60 , __( 'minute', 'ptibogxivtheme' ), __( 'minutes', 'ptibogxivtheme' ) ),
		array( 1, __( 'second', 'ptibogxivtheme' ), __( 'seconds', 'ptibogxivtheme' ) )
	);

	if ( !is_numeric( $date ) ) {
		$time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
		$date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
		$date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
	}
	
	$current_time = current_time( 'mysql', $gmt = 0 );
	$newer_date = strtotime( $current_time );

	// Difference in seconds
	$since = $newer_date - $date;

	// Something went wrong with date calculation and we ended up with a negative date.
	if ( 0 > $since )
		return __( 'sometime', 'ptibogxivtheme' );

	/**
	 * We only want to output one chunks of time here, eg:
	 * x years
	 * xx months
	 * so there's only one bit of calculation below:
	 */

	//Step one: the first chunk
	for ( $i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];

		// Finding the biggest chunk (if the chunk fits, break)
		if ( ( $count = floor($since / $seconds) ) != 0 )
			break;
	}

	// Set output var
	$duration = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
	

	if ( !(int)trim($duration) ){
		$duration = '0 ' . __( 'seconds', 'ptibogxivtheme' );
	}
	
	return sprintf( esc_html__( '%s %s ago', 'ptibogxivtheme' ), $duration, $unit);
}

// Filter our ptibogxivtheme_time_ago() function into WP's the_time() function
add_filter('the_time', 'ptibogxivtheme_time_ago');

