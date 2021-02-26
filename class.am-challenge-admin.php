<?php

class AM_Challenge_Admin extends AM_Challenge{

	const NAME = 'AM Challenge';
	const SLUG = 'am_challenge';

	public static function init() {

		add_action('admin_menu', [__CLASS__, 'add_admin_page']);
		add_action('admin_enqueue_scripts', [__CLASS__, 'load_admin_resources']);
		add_action("wp_ajax_refresh_data_ajax",  [__CLASS__, 'refresh_data_ajax']);

	}

	public function add_admin_page() {
		add_menu_page(
			esc_html__(self::NAME, 'am-challenge'),
			esc_html__(self::NAME, 'am-challenge'),
			'manage_options',
			self::SLUG,
			[__CLASS__, 'admin_page']
		);
	}

	public static function refresh_data_ajax() {

		$data =  self::get_remote_data();
		echo self::render($data);
		die();

	}


	public function admin_page() {

?>
		<div class="wrap" id="am-challenge-admin">

			<div class="am-challenge-title">
				<?= esc_html__('Awesome Motive Challenge Admin', 'am-challenge') ?>
			</div>

			<div class="am-challenge-content">
				<div id="am-challenge-data">
					<?= self::print_data(); ?>
				</div>

				<button type="submit" class="am-challenge-submit-btn">
					<?= esc_html__('Refresh Data', 'am-challenge') ?>
				</button>
			</div>
		</div>
<?php


	}



	public static function load_admin_resources($content) {
		wp_register_style('am-challenge-admin.css', ALBRECHT_AM_CHALLENGE__PLUGIN_DIR_URL . 'assets/css/am-challenge-admin.css', array(), ALBRECHT_AM_CHALLENGE__VERSION );
		wp_enqueue_style('am-challenge-admin.css');
		wp_register_script('am-challenge-admin.js', ALBRECHT_AM_CHALLENGE__PLUGIN_DIR_URL . 'assets/js/am-challenge-admin.js', array('jquery'), ALBRECHT_AM_CHALLENGE__VERSION);
		wp_enqueue_script( 'am-challenge-admin.js');
		wp_localize_script('am-challenge-admin.js', 'am_challenge_admin_script',  ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}	

}

