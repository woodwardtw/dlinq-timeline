<?php
/**
 * ACF specific functions
 *
 * @package WP-Bootstrap-Navwalker
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


//timeline acf constructor 
function treehouse_timeline(){
	if(get_field('timeline_events')){
		echo '<div class="timeline">';
		$events = get_field('timeline_events');
		//var_dump($events);
		foreach ($events as $key => $event) {
			$title = $event['title'] ? "<h2>{$event['title']}</h2>" : '';
			$content = $event['content'];
			$align = ($key % 2 == 0) ? 'right' : 'left';
			$date = $event['date'];
			echo "
				 <div class='timeline-container {$align}'>
				    <div class='date'>{$date}</div>
				    <i class='icon fa fa-grimace'></i>
				    <div class='content'>
				    	{$title}
				      	{$content}				    
				    </div>
				  </div>
			";
		}
		echo '</div>';

	}
}



function timelinesorter_load_value( $value, $post_id, $field ) {
    
    // vars
    $order = array();
    
    
    // bail early if no value
    if( empty($value) ) {
        
        return $value;
        
    }
    
    
    // populate order
    foreach( $value as $i => $row ) {
        
        $order[ $i ] = $row['field_651edc13497dc'];
        
    }
    
    
    // multisort
    array_multisort( $order, SORT_DESC, $value );
    
    
    // return   
    return $value;
    
}

add_filter('acf/load_value/key=group_6512e4deef651', 'timelinesorter_load_value', 10, 3);


	//save acf json
		add_filter('acf/settings/save_json', 'timeline_json_save_point');
		 
		function timeline_json_save_point( $path ) {
		    
		    // update path
		    $path = get_stylesheet_directory() . '/acf-json'; //replace w get_stylesheet_directory() for theme
		    
		    
		    // return
		    return $path;
		    
		}


		// load acf json
		add_filter('acf/settings/load_json', 'timeline_json_load_point');

		function timeline_json_load_point( $paths ) {
		    
		    // remove original path (optional)
		    unset($paths[0]);
		    
		    
		    // append path
		    $paths[] = get_stylesheet_directory()  . '/acf-json';//replace w get_stylesheet_directory() for theme
		    
		    
		    // return
		    return $paths;
		    
		}