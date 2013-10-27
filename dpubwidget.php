<?php
/*
 Plugin Name: Dpub count down widget
 Plugin URI: http://azur256.com/
 Description: Count down widget for Dpub
 Author: @azur256
 Version: 0.1
 Author URI: http://azur256.com
 */

// Plugin (option) cleanup
 
register_uninstall_hook(__FILE__, 'dpubwidget_delete_plugin_options');

function dpubwidget_delete_plugin_options() {
	delete_option('dpub_widget');
}

// Create widget
class Dpub_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'dpub_widget', 'description' => __("Dpub開催までのカウントダウンウィジェット") );
		parent::__construct('dpub-widget', __('Dpubカウントダウン'), $widget_ops);
		$this->alt_option_name = 'dpub_widget';
	}

	function widget($args, $instance) {
		ob_start();
		extract($args);
    // Get customise data from options
	$title = apply_filters('widget_title', empty($instance['title']) ? __('Dpubカウントダウン') : $instance['title'], $instance, $this->id_base);
    $dpubDate_string = empty($instance['dpubDate_string']) ? __('') : $instance['dpubDate_string'];
    $before_message = empty($instance['before_message']) ? __('Dpub 開幕まで…') : $instance['before_message'];
    $opening_message = empty($instance['opening_message']) ? __('Dpub がはじまりました！') : $instance['opening_message'];
    $coming_message = empty($instance['coming_message']) ? __('Coming soon!') : $instance['coming_message'];
?>

		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
<style type="text/css">
.normal{font-size:15px; font-weight:bold; color:#333333;text-shadow:1px 1px 3px #AAAAAA;}
.big{font-size:20px; font-weight:bold; color:#333333;text-shadow:1px 1px 3px #AAAAAA;}
.alert{font-size:24px; font-weight:bold; color:#ff3300;}
</style>
<div id="dpub_counter"></div>

<script charset="utf-8" type="text/javascript"><!--
function count_down() {
    <?php if(empty($dpubDate_string)) { ?>

            document.getElementById("dpub_counter").innerHTML = '<?php echo htmlspecialchars($coming_message); ?>';

    <?php } else { ?>
        nowdaytime    = new Date();
        dpubdaytime   = new Date("<?php echo $dpubDate_string; ?>");

        cntdwnMils  = dpubdaytime.getTime() - nowdaytime.getTime();
        cntdwn_day  = Math.floor(cntdwnMils / (1000*60*60*24));
        cntdwnTime  = cntdwnMils - (cntdwn_day * (1000*60*60*24));
        cntdwn_hour = Math.floor(cntdwnTime / (1000*60*60));
        cntdwnTime  = cntdwnTime - (cntdwn_hour * (1000*60*60));
        cntdwn_min  = Math.floor(cntdwnTime / (1000*60));
        cmtdwnTime  = cntdwnTime - (cntdwn_min * (1000*60));
        cntdwn_sec  = Math.floor(((dpubdaytime-nowdaytime)%(24*60*60*1000))/1000)%60%60;
    	cmtdwnTime  = cntdwnTime - (cntdwn_sec * (1000));
        if ( cntdwn_min < 10) {
            cntdwn_min = '0' + cntdwn_min;
        }
        if ((dpubdaytime - nowdaytime) > 0) {
            document.getElementById("dpub_counter").innerHTML =
                '<span>' +
                '<?php echo htmlspecialchars($before_message); ?> ' + '<br />残り ' + 
                '<span class="alert">' + cntdwn_day + '</span><span class="normal"> 日 </span> ' +
				'<span class="alert">' + cntdwn_hour + '</span><span class="normal"> 時間 </span> ' +
				'<span class="alert">' + cntdwn_min + '</span><span class="normal"> 分 </span> ' +
				'<span class="alert">' + cntdwn_sec + '</span><span class="normal"> 秒 </span> ' +
				"</span>";
        } else {
            document.getElementById("dpub_counter").innerHTML = "<?php echo htmlspecialchars($opening_message); ?>";
        }
    <?php } ?>
}
setInterval("count_down()",1000);
// --></script>
		<?php echo $after_widget; ?>
<?php

	}

 // YYYY-MM-DD HH:MM:SS
  function form( $instance ) {
	$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
    $dpubDate_string = isset($instance['dpubDate_string']) ? esc_attr($instance['dpubDate_string']) : '';
    $before_message = isset($instance['before_message']) ? esc_attr($instance['before_message']) : '';
    $opening_message = isset($instance['opening_message']) ? esc_attr($instance['opening_message']) : '';
    $coming_message = isset($instance['coming_message']) ? esc_attr($instance['coming_message']) : '';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
    <p><label for="<?php echo $this->get_field_id('dpubDate_string'); ?>">日時:</label>  (YYYY-MM-DD hh:mm:ss)
    <input class="widefat" id="<?php echo $this->get_field_id('dpubDate_string'); ?>" name="<?php echo $this->get_field_name('dpubDate_string'); ?>" type="text" value="<?php echo $dpubDate_string; ?>" /></p>
    <p><label for="<?php echo $this->get_field_id('before_message'); ?>">先頭のメッセージ:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('before_message'); ?>" name="<?php echo $this->get_field_name('before_message'); ?>" type="text" value="<?php echo $before_message; ?>" /></p>
    <p><label for="<?php echo $this->get_field_id('coming_message'); ?>">日付未設定時のメッセージ:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('coming_message'); ?>" name="<?php echo $this->get_field_name('coming_message'); ?>" type="text" value="<?php echo $coming_message; ?>" /></p>
    <p><label for="<?php echo $this->get_field_id('opening_message'); ?>">開始後のメッセージ:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('opening_message'); ?>" name="<?php echo $this->get_field_name('opening_message'); ?>" type="text" value="<?php echo $opening_message; ?>" /></p>

<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget("Dpub_Widget");' ) );
