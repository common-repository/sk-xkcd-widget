<?php
/*
Plugin Name: Sk XKCD Widget
Plugin URI: 
Description: XKCD Widget
Author: Skipstorm
Version: 1.2
Author URI: http://www.skipstorm.org/
*/

add_action( 'widgets_init', 'sk_xkcd_widget_load_widget' );


function sk_xkcd_widget_load_widget() {
	register_widget( 'Sk_xkcd_Widget' );
}

class Sk_xkcd_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Sk_xkcd_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sk_xkcd_widget', 'description' => __('Shows the latest image from xkcd.', 'sk_xkcd_widget') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 350, 'height' => 350, 'id_base' => 'sk_xkcd_widget-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'sk_xkcd_widget-widget', __('sk XKCD widget', 'sk_xkcd_widget'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		extract($args);
		
        global $wp_query,$wpdb,$wp_rewrite;
		$title = '<a href="http://xkcd.com" target="_blank">'.'<img src="'.get_option( 'siteurl' ).'/wp-content/plugins/sk-xkcd-widget/xkcdLogo.png" style="width:100%;"/></a>';
		
        @$page = $this->getPage('http://xkcd.com/');

        if($page && !empty($page)){
			$doc = new DOMDocument();
			@$doc->loadHTML($page);
			$imgContainer = $doc->getElementById('middleContent');
			$images = $imgContainer->getElementsByTagName( 'img' );

			foreach($images as $img){
				$title .= '<a href="http://xkcd.com" target="_blank">'.$img->getAttribute('title').'</a>';
				echo $before_widget.$before_title.$title.$after_title;
				echo '<img src="'.$img->getAttribute('src').'" style="width:100%;"/>';
				break;
			}
			echo $after_widget;
		}
	}
	
	function getPage($url){
		if (!function_exists('curl_init')){
			die('CURL non è installato sul server!');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		curl_close($ch);

		return $output;
	}

}
?>