<?php
/**
 * The Header for our theme.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */
?><!DOCTYPE html>
<?php 
	if( $_GET && key_exists('mfn-rtl', $_GET) ):
		echo '<html class="no-js" lang="ar" dir="rtl">';
	else:
?>
<html class="no-js<?php echo mfn_user_os(); ?>" <?php language_attributes(); ?><?php mfn_tag_schema(); ?>>
<?php endif; ?>

<!-- head -->
<head>

<!-- meta -->
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php 
	if( mfn_opts_get('responsive') ){
		if( mfn_opts_get('responsive-zoom') ){
			echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
		} else {
			echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />';
		}
		 
	}
?>

<?php do_action('wp_seo'); ?>

<link rel="shortcut icon" href="<?php mfn_opts_show( 'favicon-img', THEME_URI .'/images/favicon.ico' ); ?>" />	
<?php if( mfn_opts_get('apple-touch-icon') ): ?>
<link rel="apple-touch-icon" href="<?php mfn_opts_show( 'apple-touch-icon' ); ?>" />
<?php endif; ?>	

<!-- wp_head() -->
<?php wp_head(); ?>
</head>

<!-- body -->
<body <?php body_class(); ?>>
	
	<?php do_action( 'mfn_hook_top' ); ?>

	<?php get_template_part( 'includes/header', 'sliding-area' ); ?>
	
	<?php if( mfn_header_style( true ) == 'header-creative' ) get_template_part( 'includes/header', 'creative' ); ?>
	
	<!-- #Wrapper -->
	<div id="Wrapper">

		<!-- #Header_bg -->
		<div id="Header_wrapper" <?php echo $header_style; ?>>
	
			<!-- #Header -->
			<header id="Header">

<?php 
	if( mfn_header_style( true ) == 'header-overlay' ){
		
		// Overlay ----------
		echo '<div id="Overlay">';
			mfn_wp_overlay_menu();
		echo '</div>';
		
		// Button ----------
		echo '<a class="overlay-menu-toggle" href="#">';
			echo '<i class="open icon-menu-fine"></i>';
			echo '<i class="close icon-cancel-fine"></i>';
		echo '</a>';
		
	}
?>


<div id="Top_bar" class="loading">

	<div class="container">
		<div class="header-container">
		
			<div class="custom-header">
				<div class="contact">
					<a href="mailto:">info@dentalfeeds.com</a>
					<a href="#"><img src="/wp-content/themes/betheme-child/dental-feeds_social_1.png"></a>
					<a href="#"><img src="/wp-content/themes/betheme-child/dental-feeds_social_2.png"></a>
					<a href="#"><img src="/wp-content/themes/betheme-child/dental-feeds_social_3.png"></a>
					<a href="#"><img src="/wp-content/themes/betheme-child/dental-feeds_social_4.png"></a>
				</div>
				<!-- Logo -->
				<a id="logo" href="http://dentalfeeds.com" title="Dental Feeds">
					<img class="logo-main scale-with-grid" src="http://dentalfeeds.com/wp-content/uploads/2017/06/dental-feeds_logo_03.png" alt="dental-feeds_logo">
					<img class="logo-mobile scale-with-grid" src="http://dentalfeeds.com/wp-content/uploads/2017/06/dental-feeds_logo_03.png" alt="dental-feeds_logo">
				</a>
					<!-- #searchform -->
				<div class="search">
					<?php get_search_form( true ); ?>					
				</div>	
		
			</div>
				<div class="menu_wrapper">
					<?php 
						if( ( mfn_header_style( true ) != 'header-overlay' ) && ( mfn_opts_get( 'menu-style' ) != 'hide' ) ){
	
							// TODO: modify the mfn_header_style() function to be able to find the text 'header-split' in headers array
							
							mfn_wp_nav_menu(); 
						
							// responsive menu button ---------
							$mb_class = '';
							if( mfn_opts_get('header-menu-mobile-sticky') ) $mb_class .= ' is-sticky';

							echo '<a class="responsive-menu-toggle '. $mb_class .'" href="#">';
								if( $menu_text = trim( mfn_opts_get( 'header-menu-text' ) ) ){
									echo '<span>'. $menu_text .'</span>';
								} else {
									echo '<i class="icon-menu-fine"></i>';
								}  
							echo '</a>';
							
						}
					?>					
				</div>			
			
			<?php 
				if( ! mfn_opts_get( 'top-bar-right-hide' ) ){
					get_template_part( 'includes/header', 'top-bar-right' );
				}
			?>
			
		</div>
	</div>
</div>

			</header>
				
			
		</div>
		
		
		
		<?php do_action( 'mfn_hook_content_before' );
		
// Omit Closing PHP Tags