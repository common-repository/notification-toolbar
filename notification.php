<?php
/*
Plugin Name: Notification Toolbar
Version: 0.1
Plugin URI: http://tek3d.org
Author: tek3d
Description: This is a toolbar placed on the footer of your blog and it will show your custom notifications on it. This plugin is developed based on the Static Toolbar plugin.
*/

class WPStaticToolbar{

	function WPStaticToolbar(){$this->__construct();}
		
	function __construct(){

		add_action('init',array(&$this,'init'));
		add_action('wp_head',array(&$this,'head'));
		add_action('admin_head',array(&$this,'admin_head'));
		add_action('wp_footer',array(&$this,'footer'));
		add_action('admin_menu', array(&$this,'admin_menu'));
		register_activation_hook( __FILE__, array(&$this,'activate') );

	}
	
	function head(){
		?>
		<style type="text/css">
			<?php 
			$opacity = get_option('statictoolbar_opacity');
			if(strlen($opacity)>0){ ?>
		div#static-toolbar{
		opacity: .<?php echo (int)$opacity; ?>;	
		-moz-opacity:0.<?php echo (int)$opacity; ?>;
		filter : alpha(opacity=<?php echo $opacity; ?>); 
		}	
		<?php } ?>
		div#static-toolbar li, div#static-toolbar a, 
		div#static-toolbar li#static-toolbar-social-network div#static-toolbar-social-network-panel p,
		div#static-toolbar li#static-toolbar-share div#static-toolbar-share-panel p{ color:<?php echo get_option('statictoolbar_txtcolor'); ?>; }
		div#static-toolbar ul#static-toolbar-blocs,
		div#static-toolbar li#static-toolbar-share div#static-toolbar-share-panel,
		div#static-toolbar li#static-toolbar-social-network div#static-toolbar-social-network-panel{ background-color:<?php echo get_option('statictoolbar_bgcolor'); ?>}

		</style>
		<?php
	}

	function activate(){
		if(!get_option('statictoolbar_bgcolor')){
			add_option('statictoolbar_bgcolor','#215B87');
		}
		if(!get_option('statictoolbar_txtcolor')){
			add_option('statictoolbar_txtcolor','#d9c9c9');
		}	
		if(!get_option('statictoolbar_rss')){
			add_option('statictoolbar_rss','on');
		}	
		if(!get_option('statictoolbar_opacity')){
			add_option('statictoolbar_opacity',90);
		}	
		if(!get_option('statictoolbar_nb')){
			add_option('statictoolbar_nb','Put your notification here');
		}			
	}

	function admin_menu(){
		add_options_page('Notification', 'Notification', 8, 'notification.php',array(&$this,'adminpage'));
	}

	function maj_option($name,$value){
		if ( get_option($name) === false ) {
			add_option($name, $value);	
		} else {
			update_option($name, $value);
		}
	}

	function admin_head(){
		if(is_admin() && $_SERVER['QUERY_STRING'] == 'page=notification.php'){
			?>
			<style type="text/css">
			#statictoolbar #social_network p,#statictoolbar #colors p{	overflow:hidden;	}
			#statictoolbar label.general{	width:150px; float:left; line-height:25px;	}
			#statictoolbar #social_network label{	 width:280px;  float:left; line-height:25px; text-align:right; color:#5B5B5B;}
			#statictoolbar #social_network input.text{	height:25px; padding-left:30px; width:150px;}
			#statictoolbar .ui-tabs-panel{overflow:hidden;}	
			#statictoolbar #sharing{	overflow:hidden;}
			#statictoolbar #sharing .share-link{	width:150px; float:left;}	
			#statictoolbar .share-link label{ padding-left:20px;}
			</style>
			<script type="text/javascript">
			jQuery(document).ready(function($){
				var f = $.farbtastic('#picker');
				var p = $('#picker').css('display','none');
				var selected;
				$('.colorwell')
				.each(function () { f.linkTo(this);  })
				.focus(function() {
					f.linkTo(this);
					p.css('display', 'block');
				})
				.blur(function() {
					f.linkTo(this);
					p.css('display', 'none');
				});	
			 
				$("#statictoolbar-tabs").tabs(); 
				<?php if(isset($_POST['tab'])){ ?>
				$("#statictoolbar-tabs").tabs("select",<?php echo $_POST['tab']; ?>); 
				<?php } ?>			 
			 
			 });
			</script>		
			<?php
		}
	}

	
	function adminpage(){
		if(isset($_POST['statictoolbar-submit'])){

			$this->maj_option('statictoolbar_nb',$_POST['nb']);
			$this->maj_option('statictoolbar_rss',$_POST['rss']);
			$this->maj_option('statictoolbar_opacity',$_POST['opacity']);
			$this->maj_option('statictoolbar_bgcolor',$_POST['bgcolor']);
			$this->maj_option('statictoolbar_txtcolor',$_POST['txtcolor']);
		}

		?>
		<div class="wrap" id="statictoolbar">
			<h2><?php  _e("Toolbar options","statictoolbar"); ?></h2>
									
			<form action="" method="post" onsubmit="jQuery('#statictoolbar-tab').val(jQuery('#statictoolbar-tabs').tabs().tabs('option', 'selected')); ">
				<input type="hidden" name="tab" value="0" id="statictoolbar-tab" />
				<div id="statictoolbar-tabs">
					<ul>
						<li><a href="#statictoolbar-tabs-1"><?php echo __("Settings","statictoolbar"); ?></a></li>	
					</ul>
					
					<div id="statictoolbar-tabs-1">			
						<p>
							<input type="checkbox" name="rss" id="rss"  <?php if(get_option('statictoolbar_rss') == 'on') echo 'checked="checked"'; ?>/>
							<label for="rss"><?php _e('Show Notification','statictoolbar'); ?></label>
						</p>				
						<p>
							<label for="opacity" class="general likeColor"><?php _e('Toolbar opacity','statictoolbar'); ?></label>
							<input type="text" name="opacity" id="opacity"  value="<?php echo get_option('statictoolbar_opacity');  ?>" /> %						
						</p>	
						<p>
							<label for="nb" class="general"><?php _e('Your Message','statictoolbar'); ?></label>
							<input type="text" name="nb" id="nb" size="80" maxlength="80"  value="<?php echo get_option('statictoolbar_nb');  ?>" />				
						</p>							
						<div id="colors">
							<div id="picker" ></div>
							<h3><?php _e('colors','statictoolbar'); ?></h3>
							<p>
								<label for="bgcolor" class="general"><?php _e('Background color','statictoolbar'); ?></label>
								<input type="text" class="colorwell" id="bgcolor" name="bgcolor" value="<?php echo get_option('statictoolbar_bgcolor'); ?>" />
							</p>
							<p>
								<label for="txtcolor" class="general"><?php _e('Text color','statictoolbar'); ?></label>
								<input type="text" class="colorwell" id="txtcolor" name="txtcolor" value="<?php echo get_option('statictoolbar_txtcolor'); ?>" />
							</p>				
						</div>
					</div>
				</div>
				<p class="submit">
					<input type="submit" name="statictoolbar-submit" class="button-primary" value="<?php echo _e('Save the configuration','statictoolbar'); ?>" />
				</p>				
			</form>
		</div>
		<?php
	}

	function footer(){ 
		?>
		
	<?php if(get_option('statictoolbar_rss') == 'on'){ ?>
		<div id="static-toolbar">
			<ul id="static-toolbar-blocs">
				<?php if(get_option('statictoolbar_rss') == 'on'){ ?>
				<li id="static-toolbar-feed">
					<img src="<?php echo WP_PLUGIN_URL.'/notification-toolbar/images/warning.png'; ?>" alt="Message"/>
				</li>
				<?php } ?>			
				<li id="static-toolbar-posts">	
				<ul>
					<?php
					$nb = get_option('statictoolbar_nb');
					?>
					<li id="entryActive"> <?php echo $nb; ?> </li>			
				</ul>
				</li>
				<li id="static-toolbar-button">
					<img title="<?php _e('Close static bar','statictoolbar'); ?>" id="static-toolbar-close" src="<?php echo WP_PLUGIN_URL.'/notification-toolbar/images/close.png'; ?>" alt="<?php echo addslashes(__('Close','statictoolbar')); ?>"/>
					<img title="<?php _e('Open static bar','statictoolbar'); ?>" style="display:none" id="static-toolbar-open" src="<?php echo WP_PLUGIN_URL.'/notification-toolbar/images/open.png'; ?>" alt="<?php echo addslashes(__('Open','statictoolbar')); ?>"/>
				</li>
			</ul>
		</div>
	<?php } ?>
		<?php
	}


	function init(){
		if(!is_admin()){
			wp_enqueue_script('static-toolbar-js',  WP_PLUGIN_URL . '/notification-toolbar/static-toolbar.js',array('jquery'));
			wp_enqueue_script('jquery-corner',  WP_PLUGIN_URL . '/notification-toolbar/jquery.corner.js',array('jquery'));
			wp_enqueue_style('static-toolbar-css', WP_PLUGIN_URL . '/notification-toolbar/static-toolbar.css');
		}
		elseif($_SERVER['QUERY_STRING'] == 'page=notification.php'){
			wp_enqueue_style('farbtastic-css', WP_PLUGIN_URL . '/notification-toolbar/farbtastic.css');
			wp_enqueue_script('farbtastic-js',  WP_PLUGIN_URL . '/notification-toolbar/farbtastic.js',array('jquery'));			
			wp_enqueue_script('jquery-ui',WP_PLUGIN_URL . '/notification-toolbar/jquery-ui.js',array('jquery'),'');
			wp_enqueue_style('jquery-ui-style',WP_PLUGIN_URL . '/notification-toolbar/css/ui.all.css',array(),false,'all');		
		}
	}
}

new WPStaticToolbar();
?>