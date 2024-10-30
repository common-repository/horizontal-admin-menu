<?php
/**
 * Plugin Name: Horizontal Admin Menu
 * Plugin URI: http://wordpress.org/extend/plugins/horizontal-admin-menu/
 * Description: Allows you to move your existing WordPress admin menu to the top of the screen. Existing submenus show as drop-down menus when you mouse over a top level menu. Alters existing WordPress styles so the menus match the default admin theme.
 * Version: 1.0
 * Author: Eddie Krebs
 * Author URI: http://profiles.wordpress.org/users/ekrebs
 * License: GPLv2 or later
 */

/**
 * Inserts the new admin styles into the page
 *
 * Note that this code inserts the styles directly into the HEAD, rather than a link to
 * an external stylesheet. This is because WP uses load-styles.php to insert its admin
 * styles, which loads after any linked css files. Injecting the css directly will
 * allow you to override the existing styles. I don't like it either.
 *
 * @return void
 */
function ek_admin_styles() {
    if ( ! get_user_option( 'ek_admin_enable' ) )
        return;
    echo '<style type="text/css" media="all">' . PHP_EOL;
    include( dirname( __FILE__ ) . '/css/admin.css' );
    ek_admin_print_user_style_preferences();
    echo PHP_EOL . '</style>' . PHP_EOL;
}
add_action('admin_head', 'ek_admin_styles');

/**
 * Fix IE7 style issues that are encountered
 *
 * Specifically, add script to get around the z-index bug, and use float instead of
 * inline-block.
 *
 * @return void
 */
function ek_admin_ie7_hacks() {
    if ( ! get_user_option( 'ek_admin_enable' ) )
        return;

    // Fix IE7 styles
    echo '<!--[if IE 7]>' . PHP_EOL;
    echo '<style type="text/css">' . PHP_EOL;
        include( dirname( __FILE__ ) . '/css/admin-ie.css' );
    echo '</style>' . PHP_EOL;
    // Fix IE7 z-index issue
    echo '<script type="text/javascript">' . PHP_EOL;
        include( dirname( __FILE__ ) . '/script/admin-ie.js' );
    echo '</script>' . PHP_EOL;
    echo '<![endif]-->' . PHP_EOL;
}
add_action('admin_head', 'ek_admin_ie7_hacks');

/**
 * Show the settings on the user profile screen used by this plugin.
 *
 * @return void
 */
function ek_admin_show_prefs() {
    ?>

	<h3>Horizontal Admin Menu</h3>

	<table class="form-table">

		<tr>
			<th scope="row">Enable/Disable</th>
			<td>
                <label for="ek-admin-enable">
				    <input type="checkbox" name="ek-admin-enable" id="ek-admin-enable" value="1" <?php
                    checked( true, get_user_option( 'ek_admin_enable' ), true )
                    ?> />
                    Move your admin menu to the top of the screen
                </label>
			</td>
		</tr>

		<tr>
			<th scope="row">Menu Width</th>
			<td>
                <label for="ek-use-1024">
				    <input type="checkbox" name="ek-use-1024" id="ek-use-1024" value="1" <?php
                    checked( true, get_user_option( 'ek_admin_use_1024' ), true )
                    ?> />
                    Optimize for 1024x768 screen resolution
                </label>
			</td>
		</tr>

	</table>

    <?php
}
add_action( 'show_user_profile', 'ek_admin_show_prefs' );
add_action( 'edit_user_profile', 'ek_admin_show_prefs' );

/**
 * Save user preferences
 *
 * @param $user_id
 * @return bool
 */
function ek_admin_save_prefs( $user_id ) {

	if ( ! current_user_can( 'edit_user', $user_id ) )
		return false;

	update_user_option( $user_id, 'ek_admin_enable', $_POST['ek-admin-enable'] );
    update_user_option( $user_id, 'ek_admin_use_1024', $_POST['ek-use-1024'] );
    return true;
}
add_action( 'personal_options_update', 'ek_admin_save_prefs' );
add_action( 'edit_user_profile_update', 'ek_admin_save_prefs' );

/**
 * Print styles as defined by the user.
 *
 * Called by ek_admin_styles()
 *
 * @return void
 */
function ek_admin_print_user_style_preferences() {
    if ( get_user_option( 'ek_admin_use_1024' ) )
        if ( true == get_user_option( 'ek_admin_use_1024' ) ) {
            ?>
    #adminmenu {
            width: 978px;
            border-right: 1px solid #DFDFDF;
            border-left: 1px solid #DFDFDF;
    }
    #wpcontent {
            width: 980px;
            margin: 0 auto;
            padding: 0;
    }

    #wp-body {
            width: 960px;
            padding: 0 10px;
    }

    #footer {
            width: 980px;
            margin: 0 auto 20px;
    }
            <?php
        }
}

?>