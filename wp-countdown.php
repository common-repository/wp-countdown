<?php
/*
Plugin Name: COUNTdown
Plugin URI: http://www.dominik-laubach.de/2008/10/31/wp-plugin-countdown/
Description: WP Countdown is a simple script that display a text-countdown and refreshes every second via ajax.
Version: 1.1
Author: Dominik Laubach
Author URI: http://www.dominik-laubach.de
*/

	function wp_getCountdown() {

		echo '<div id="countdown"></div>';
		echo '<script language="javascript">
				updateCountdown();
				var test = window.setInterval("updateCountdown()", 1000);

				function updateCountdown() {
					jQuery.ajax({
   						type: "POST",
   						url: "' . get_bloginfo('wpurl') . '/wp-content/plugins/wp-countdown/update.php",
      					data: "action=update_coutdown",
   						success: function(msg){
     						jQuery("#countdown").html(msg);
   						}
 					});
				}
			</script>';

	}

	function update_coutdown() {

		global $wpdb, $table_prefix;
    	$tablename = $table_prefix . 'countdown';

		$res = $wpdb->get_results('SELECT * FROM ' . $tablename . ' LIMIT 1');
  		$end_datetime = $res[0]->enddate;
    	$now = new DateTime("now", new DateTimeZone('Europe/Berlin'));
    	$end = new DateTime(null, new DateTimeZone('Europe/Berlin'));
		$end->setDate(substr($end_datetime, 0, 4), substr($end_datetime, 5, 2), substr($end_datetime, 8, 2));
		$end->setTime(substr($end_datetime, 11, 2), substr($end_datetime, 14, 2), substr($end_datetime, 17, 2));
		$diff = $end->format('U') - $now->format('U');

		$days = floor($diff / (24 * 60 * 60));
		$diff -= floor($days * 24 * 60 * 60);

		$hours = floor($diff / (60 * 60));
		$diff -= floor($hours * 60 * 60);

		$minutes = floor($diff / 60);
		$diff -= floor($minutes * 60);

		$seconds = $diff;

		if($days >= 0) {
			die(
				($days > 0 ? $days . ' Tage, ' : '') .
				($hours > 0 ? $hours . ' Stunden, ' : '') .
				($minutes > 0 ? $minutes . ' Minuten und ' : '') .
				$seconds . ' Sekunden...'
			);
		} else {
			die('Countdown abgelaufen...');
		}

	}

	function add_options() {

    	if(function_exists('add_options_page')) {
			add_options_page('WP-COUNTdown Options', 'WP-COUNTdown', 8, basename(__FILE__), 'countdown_options');
		}

	}

	function countdown_options() {

		global $wpdb, $table_prefix;
    	$tablename = $table_prefix . 'countdown';

        $res = $wpdb->get_results('SHOW TABLES LIKE \'' . $tablename . '\'');
		if (count($res) == 0) {
			$wpdb->query('CREATE TABLE ' . $tablename . ' (enddate DATETIME NOT NULL, PRIMARY KEY (enddate))');
			$wpdb->query('INSERT INTO ' . $tablename . ' (enddate) VALUES (\'000-00-00 00:00:00\')');
		}

		if($_POST['wp-countdown_end']) {
			$regs = array();
			if (eregi('([0-9]{2})[.]{1}([0-9]{2})[.]{1}([0-9]{4})[ ]{1}([0-9]{2})[:]{1}([0-9]{2})[:]{1}([0-9]{2})', $_POST['wp-countdown_end'], $regs)) {
				if($_POST['wp-countdown_end'] == $regs[0]) {
     				$end_datetime_new = $regs[3] . '-' . $regs[2] . '-' . $regs[1] . ' ' . $regs[4] . ':' . $regs[5] . ':' . $regs[6];
     				$wpdb->query('UPDATE ' . $tablename . ' SET enddate = \'' . $end_datetime_new . '\'');
				}
			}
		}

        $res = $wpdb->get_results('SELECT * FROM ' . $tablename . ' LIMIT 1');
  		$end_datetime = $res[0]->enddate;
		$end = substr($end_datetime, 8, 2) . '.' . substr($end_datetime, 5, 2) . '.' . substr($end_datetime, 0, 4) . ' ' . substr($end_datetime, 11, 2) . ':' . substr($end_datetime, 14, 2) . ':' . substr($end_datetime, 17, 2);

		?>

			<div class="wrap">
				<h2>Einstellungen zum Plugin WP-COUNTdown</h2>
				<form method="post" action="<?= $_SERVER['REQUEST_URI'] . '&updated=true' ?>">
					<table class="form-table">
						<tr valign="top">
							<th scope="row">Enddatum </th>
							<td>
								<p>
									<input id="wp-countdown_end" type="text" name="wp-countdown_end" value="<?= $end ?>" />
									<label for="blog-public"> zum Beispiel: "<?= date('d.m.Y H:i:s', time()); ?>"</label>
								</p>
							</td>
						</tr>
					</table>
					<p class="submit"><input type="submit" name="Submit" value="&Auml;nderungen speichern" /></p>
				</form>
			</div>

		<?php

	}

	if($_POST['action'] == 'update_coutdown') {
		update_coutdown();
	} else {
    	add_action('admin_menu', 'add_options');
	}

?>