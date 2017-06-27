<?php

/* ---------------------------------------------------------------------------
 * Child Theme URI | DO NOT CHANGE
 * --------------------------------------------------------------------------- */
define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );


/* ---------------------------------------------------------------------------
 * Define | YOU CAN CHANGE THESE
 * --------------------------------------------------------------------------- */

// White Label --------------------------------------------
define( 'WHITE_LABEL', false );

// Static CSS is placed in Child Theme directory ----------
define( 'STATIC_IN_CHILD', false );


/* ---------------------------------------------------------------------------
 * Enqueue Style
 * --------------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'mfnch_enqueue_styles', 101 );
function mfnch_enqueue_styles() {
	
	// Enqueue the parent stylesheet
// 	wp_enqueue_style( 'parent-style', get_template_directory_uri() .'/style.css' );		//we don't need this if it's empty
	
	// Enqueue the parent rtl stylesheet
	if ( is_rtl() ) {
		wp_enqueue_style( 'mfn-rtl', get_template_directory_uri() . '/rtl.css' );
	}
	
	// Enqueue the child stylesheet
	wp_dequeue_style( 'style' );
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() .'/style.css' );
	
}


/* ---------------------------------------------------------------------------
 * Load Textdomain
 * --------------------------------------------------------------------------- */
add_action( 'after_setup_theme', 'mfnch_textdomain' );
function mfnch_textdomain() {
    load_child_theme_textdomain( 'betheme',  get_stylesheet_directory() . '/languages' );
    load_child_theme_textdomain( 'mfn-opts', get_stylesheet_directory() . '/languages' );
}


/* ---------------------------------------------------------------------------
 * Override theme functions
 * 
 * if you want to override theme functions use the example below
 * --------------------------------------------------------------------------- */
// require_once( get_stylesheet_directory() .'/includes/content-portfolio.php' );


class Paginator {
	private $_limit;
	private $_page;
	private $_query;
	private $_total;

	public function __construct($query) {

		$this->_query = $query;
		
		global $wpdb;
		$this->_total = count($wpdb->get_results($query)); 
	
	}

	public function getData( $limit = 10, $page = 1, $offset = null) {

    	$this->_limit = $limit;
    	$this->_page = $page;
    	$this->_offset = $offset;

    	if ($this->_limit == 'all') {
    		$query = $this->_query;
    	} else if ($limit && $offset) {
    		$query = $this->_query . " LIMIT " . $this->_limit . " OFFSET " . $this->_offset;
    	}
    	else {
    		$query = $this->_query . " LIMIT " . ( ($this->_page - 1 ) * $this->_limit) . ", $this->_limit";
    	}

    	global $wpdb;
		$rs = $wpdb->get_results($query);

		$results = array();
    	foreach ($rs as $row) {
    		array_push($results, $row);
    	}

		$result         = new stdClass();
	    $result->page   = $this->_page;
	    $result->limit  = $this->_limit;
	    $result->total  = $this->_total;
	    $result->data   = $results;
		return $result;



    }

    public function create_links($links, $list_class) {

    	if( $this->_limit == 'all' ) {
    		return '';
    	}

    	$last = ceil( $this->_total / $this->_limit );

    	$start = ( ($this->_page - $links) > 0 ) ? $this->_page - $links : 1;
    	$end = ( ($this->_page + $links) < $last ) ? $this->_page + $links : $last;

    	$html = '<ul class="' . $list_class . '">';

    	$class = ($this->_page == 1) ? "disabled" : "";

    	$html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&9463=' . ( $this->_page - 1 ) . '">&laquo;</a></li>'; 

    	if ($start > 1) {
    		$html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&9463=1">1</a><li>';
    		$html .= '<li class="disabled"><span>...</span></li>';
    	}

    	for( $i = $start; $i < $end; $i++) {
    		$class  = ( $this->_page == $i ) ? "active" : "";
        	$html   .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&9463=' . $i . '">' . $i . '</a></li>';
    	}

    	if ( $end < $last ) {
	        $html   .= '<li class="disabled"><span>...</span></li>';
	        $html   .= '<li><a href="?limit=' . $this->_limit . '&9463=' . $last . '">' . $last . '</a></li>';
    	}

    	$class      = ( $this->_page == $last ) ? "disabled" : "";
    	$html       .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&9463=' . ( $this->_page + 1 ) . '">&raquo;</a></li>';
 	
	    $html       .= '</ul>';
 
    	echo $html;

    }
    
}



function getFeed() {
     
    $feeds_url = [
    		'https://trustdentalcare.com/feed/',
    		'https://serenasandiegodentist.com/feed/',
    		'http://cosmeticdentistinsandiego.com/blog/feed/'];

    foreach ($feeds_url as $feed_url) {
    	
	    $rss = new DOMDocument();
	    $rss->load($feed_url);

	    if ($feed) {
	    	$feed = $feed;
	    } else {
			$feed = array();
	    }

	    foreach ($rss->getElementsByTagName('item') as $node) {
	    
	        $item = array (
	                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
	                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
	                'pubDate' => date( 'Y-m-d', strtotime( $node->getElementsByTagName('pubDate')->item(0)->nodeValue ) ),
	                'content' => $node->getElementsByTagName('encoded')->item(0)->nodeValue,
	                'description' => wp_trim_words( $node->getElementsByTagName('encoded')->item(0)->nodeValue, 55 ),
	                'image' => firstImg($node->getElementsByTagName('encoded')->item(0)->nodeValue)
	                );
	        array_push($feed, $item);
	    }

    }
    insertFeed($feed);
}

function insertFeed($feed) {
	foreach ($feed as $entry) {
		global $wpdb;
		$result = $wpdb->get_results( "SELECT * FROM rss_feeds WHERE title = '" . $entry['title'] . "'");
		if ($result) {
			
		} else {
			$wpdb->insert( 
				'rss_feeds', 
				[
				'title' => $entry['title'],
				'link' => $entry['link'],
				'date' => $entry['pubDate'],
				'content' => $entry['content'],
				'description' => $entry['description'],
				'image' => $entry['image']
				]);
		}
	}
	displayFeed();
}

function displayFeed($atts) {

	$atts = shortcode_atts(
		array(
			'pagination' => 'no',
			'offset'	=> null,
			), $atts, 'Feed');

	
	$Paginator = new Paginator("SELECT * FROM rss_feeds ORDER BY date DESC");

	$page       = ( isset( $_GET['9463'] ) ) ? $_GET['9463'] : 1;
	$limit       = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 6;

	if ($atts['offset']) {
		$results = $Paginator->getData($limit, $page, $atts['offset']);
	} else {
		$results = $Paginator->getData($limit, $page);
	}

	foreach ($results->data as $post) {
		
	 	?>
		
	 	
	 		<div class="post">
	 			<div class="post-image">
	 				<a href="<?=$post->link?>"><img src="<?=$post->image?>"></a>
	 			</div>
	 			<h4><a href="<?=$post->link?>"><?=$post->title?></a></h4>
	 		</div>
	 	

		<?php
	 }
	
	 if ($atts['pagination'] == 'yes') {
		echo $Paginator->create_links(7, 'paginator_nums');
	 }

}

function firstImg( $post_content ) {
    $matches = array();
    $output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches );
    if ( isset( $matches[1][0] ) ) {
        $first_img = $matches[1][0];
    }

    if ( empty( $first_img ) ) {
        return '';
    }
    return $first_img;
}


add_shortcode( 'Feed', 'displayFeed' );


