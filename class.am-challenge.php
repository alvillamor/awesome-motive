<?php

class AM_Challenge {

	const API_ENDPOINT = 'https://miusage.com/v1/challenge/1/';
	const DELAY_FETCH = 3600; // 1 hour 
	const SHORTCODE = 'am_challenge_data'; 
	const KEY_FETCH_TIME = 'albrecht_am_challenge_fetch_time';
	const KEY_DATA = 'albrecht_am_challenge_data';	


	public static function init() {

		add_shortcode(self::SHORTCODE, [__CLASS__, 'print_data_shortcode']);

		add_action("wp_ajax_print_data_ajax",  [__CLASS__, 'print_data_ajax']);
		add_action("wp_ajax_nopriv_print_data_ajax",  [__CLASS__, 'print_data_ajax']);

		// add javascript and style if shortcode is present.
		add_filter('the_content', [__CLASS__, 'load_ajax_resources']);
	}

	public static function get_data() {

		$last_fetch_time = get_option(self::KEY_FETCH_TIME) ?: 0;
		$data = get_option(self::KEY_DATA) ?? null;
		$data_age = time() - $last_fetch_time;
		
		if($data_age > self::DELAY_FETCH || empty($data)) {
			return self::get_remote_data();
		}

		return $data;
	}

	public static function get_remote_data() {

		$request = wp_remote_get(self::API_ENDPOINT);

		if(is_wp_error($request)) {
			return false; 
		}

		$body = wp_remote_retrieve_body($request);

		update_option(self::KEY_FETCH_TIME, time());
		update_option(self::KEY_DATA, $body);		

		return $body;

	}

	public static function print_data_shortcode() {
		return '<div id="am-challenge-data"></div>';
	}

	public static function print_data_ajax() {
		echo self::print_data();
		die();
	}

	public static function print_data() {

		$data = self::get_data();
		return self::render($data);

	}

	public static function render($data) {

		$content = json_decode($data);
		
		$title = $content->title;
		$headers = $content->data->headers;
		$rows = $content->data->rows;

		ob_start();

		echo '<table id="am-challenge-table">';

		echo '<tr>';
		foreach ($headers as $header) {
			echo '<th>' . $header . '</th>';
		}
		echo '</tr>';

		foreach($rows as $row) {
			echo '<tr>';

			foreach (get_object_vars($row) as $key => $value) {
				if ($key == 'date'){
					 $value = date('Y-m-d H:i:s', $value);
				}
				echo '<td>' . $value . '</td>';
			}

			echo '</tr>';
		}

		echo '</table>';

		return ob_get_clean();
	}
	
	public static function load_ajax_resources($content) {

	    if(has_shortcode($content, self::SHORTCODE)){
			wp_register_style('am-challenge.css', ALBRECHT_AM_CHALLENGE__PLUGIN_DIR_URL . 'assets/css/am-challenge.css', array(), ALBRECHT_AM_CHALLENGE__VERSION );
			wp_enqueue_style('am-challenge.css');

			wp_register_script('am-challenge.js', ALBRECHT_AM_CHALLENGE__PLUGIN_DIR_URL . 'assets/js/am-challenge.js', array('jquery'), ALBRECHT_AM_CHALLENGE__VERSION);
			wp_enqueue_script( 'am-challenge.js');
			wp_localize_script('am-challenge.js', 'am_challenge_script',  ['ajax_url' => admin_url( 'admin-ajax.php')]);
	    }

	    return $content;
	}	
}
