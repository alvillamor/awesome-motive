<?php

//  wp am_challenge refresh

WP_CLI::add_command( 'am_challenge', 'AM_Challenge_CLI' );

class AM_Challenge_CLI extends WP_CLI_Command {

	const KEY_FETCH_TIME = AM_Challenge::KEY_FETCH_TIME;

	public function refresh() {
		update_option(self::KEY_FETCH_TIME, null);
		WP_CLI::line('New data will be fetched on the next time the AJAX endpoint is called.');
	}

}