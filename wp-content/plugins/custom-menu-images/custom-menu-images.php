<?php
/*
Plugin Name: Custom menu images
Plugin URI: http://8manos.com
Description: None
Version: 0.8.5
Author: 8manos
Author URI: http://8manos.com
License: GPLv2
*/

$prefix = 'menu-item-image';
class CustomMenuImage{
	private $prefix = 'custom-menu-image';
	private $styles = array();
	function __construct(){
		$this->dir = dirname( __FILE__ );
		$this->uri = plugin_dir_url( __FILE__ );
		$this->url = $this->uri;
		$this->plugin_basename = plugin_basename( __FILE__ );
		global $wp_filter;		
		
		if(!get_option($this->prefix)){
			add_option( $this->prefix, array() );
		}
	
			

		add_filter( 'attachment_fields_to_edit', array(&$this, 'control_add_image_to_menu'), 20,2);
		add_filter("wp_get_nav_menu_items", array(&$this, "get_custom_menu_image_css"), 20, 2);
		
		add_action( 'admin_print_scripts-nav-menus.php', array( &$this, 'add_js' ) );
		add_action( 'admin_print_styles-nav-menus.php', array( &$this, 'add_css' ) );
		add_action('admin_head', array(&$this, 'add_js'));
		add_action('wp_footer', array(&$this, 'print_custom_menu_image_css'));
		
		add_action('wp_head', array(&$this, 'front_end_head'));
		add_action( 'admin_print_scripts-media-upload-popup', array( &$this, 'media_upload_popup_js' ), 2000 );
		add_action('admin_head', array(&$this, 'admin_head'));
		add_action('wp_ajax_add_image', array(&$this, "add_image"));
		add_action('wp_ajax_remove_image', array(&$this, "remove_image"));

		register_activation_hook(__FILE__, array(&$this, 'custom_menu_image_activation'));		
		register_uninstall_hook(__FILE__, array(&$this, 'custom_menu_image_uninstall'));

		if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') $this->save_config();
	}
	//
	function findMenu($id = null){		
		$locations = get_registered_nav_menus();
		$menus = wp_get_nav_menus();
		if($id){
			foreach ( $menus as $menu ){
				if($id == $menu->term_id ){
					return $menu;
				}
			}
			return null;
		}
		$menu_locations = get_nav_menu_locations();
		$return = null;
		// find current menu
		foreach ( $locations as $location => $description ) {
			foreach ( $menus as $menu ){
				if( isset( $menu_locations[ $location ] ) && $menu_locations[ $location ] == $menu->term_id ){
					$return = $menu;
					break;	
				}
			}				
		}	
		return $return;	
	}
	// add style into front-end header	
	function front_end_head(){
		wp_register_style("custom-menu-images", site_url()."/wp-content/plugins/custom-menu-images/css/custom_menu_images.css");
		wp_print_styles(array(
			'custom-menu-images'
		));
	}
	function media_upload_popup_js(){
		//wp_enqueue_script('wp-ajax-response');
	}
	function custom_menu_image_activation(){
		$options = get_option($this->prefix);
		if(!$options || !is_array($options))
			add_option($this->prefix, array());
		
		$options = get_option($this->prefix."_settings");
		if(!$options || !is_array($options))
			add_option($this->prefix."_settings", array(
				"cmi_id"	=> "menu-main-menu",
				"cmi_class" => "menu"
			));	
	}
	function custom_menu_image_uninstall(){
		delete_option($this->prefix);
	}
	function save_config(){
		if(strpos($_POST['_wp_http_referer'], 'nav-menus.php')!==false){
	
			$needSaveID = $_POST['menu'];

			$urls 		= $_POST[$this->prefix.'-url'];
			$urls_type 	= $_POST[$this->prefix.'-url-type'];
			$medias_lib = $_POST[$this->prefix.'-ml'];
			if(count($urls)){ 
				$data = array();
				foreach($urls as $key => $val){
					$data[$key] = array(
						'url_type' 	=> $urls_type[$key],
						'url'		=> $urls[$key],
						'media_lib'	=> $medias_lib[$key]
					);
				}
				$menus = wp_get_nav_menus();
				
				if(count($menus)){
				$this->styles = array();
					$this->styles[] = "@charset \"utf-8\";\n/* 1/CSS Document */";
	
					
				foreach($menus as $menu){
					$parentID = $menu->term_id;
				
					$settings = get_option($this->prefix."_settings");
				
			
					$element = $menu->slug ? "#menu-".$menu->slug:"";
					$element = "ul".$element;
					$this->styles[] = "\n\n".'/* START <'.strtoupper($menu->slug).'>*/';
					$this->styles[] = $element.' ul{width:auto;}';
					$this->styles[] = ''
					.$element.' li > a,'
					.$element.' li:hover > a{
						background-repeat: no-repeat;
						background-position: 5px center;
						padding-left: 23px;
					}';
					if($menu->term_id== $needSaveID){
						if(get_option($this->prefix."_".$parentID)){
							update_option($this->prefix."_".$parentID, $data);
						}else{
							add_option($this->prefix."_".$parentID, $data);
						}
						$custom_options = (get_option($this->prefix."_".$parentID));
						
					}else{
						$custom_options = (get_option($this->prefix."_".$parentID));
					}
					foreach ($custom_options as $key => $val ) {
						$image_url = ($custom_options[$key]['url_type'] != 'lib' ? $custom_options[$key]['url'] : $custom_options[$key]['media_lib']);
						if($image_url){
							$this->styles[] = ''
							.$element.' li.cmi_menu_item_'.$key.' > a,'
							.$element.' li.cmi_menu_item_'.$key.':hover > a {
								background-image: url('.$image_url.');						
							}';
						}
					}
					$this->styles[] = '/* END <'.strtoupper($menu->slug).'>*/';
				}
				}
				file_put_contents($this->dir."/css/custom_menu_images.css", preg_replace("#[\t]+#", "\t", implode("\n", $this->styles))); 
			}	
		}
	}
	function get_custom_menu_image_css($menuItems, $args){
		foreach ($menuItems as $key => $val ) {
			$menuItems[$key]->classes = array('cmi_menu_item_'.$val->ID);
		}
		return $menuItems;
	}
	function print_custom_menu_image_css(){	
		//print implode("\n", $this->styles);
	}
	function control_add_image_to_menu( $fields, $post ) {
		if(isset($_GET['cmi_id'])){
			$id = (int) $post->ID;
			$text = __( 'Add Thumbnail Menu' );
			$button = '<a rel="'.$id.'" class="button-primary" href="javascript:void(0);" onclick="CustomMenuImages.addImage(this, \''.$_GET['cmi_id'].'\');">'.$text.'</a>';
			$fields['image-size']['extra_rows']['button_add_to_menu']['html'] = $button;
		}
		return $fields;
	}
	function add_css(){
		wp_enqueue_style('thickbox');
	}
	function add_js(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('wp-ajax-response');
		wp_enqueue_script('custom_menu_images', $this->url.'/js/custom_menu_images.js');
		wp_enqueue_script('thickbox');
		add_action("admin_head", array(&$this, "admin_head_script"));
	}
	function admin_head_script(){
	global $nav_menu_selected_id;
	?>
   	<script>
    var menu_item_options = new Array();
    <?php
    $menu_item_options = get_option($this->prefix."_".$nav_menu_selected_id);
    if(count($menu_item_options) && is_array($menu_item_options)){
        foreach($menu_item_options as $k=>$v){
            if(is_array($v))
                echo 'menu_item_options['.$k.'] = '.json_encode($v).';' . "\n";
        }
    }
    ?>
	jQuery(document).ready(function(){
		CustomMenuImages
			.init({prefix: '<?php echo $this->prefix?>', mediaUploadUrl: '<?php echo admin_url('media-upload.php');?>'})
			.loadCustomFields();		
    })	
    </script>
    <?php
	}
	function admin_head(){
	?>
    <script src="<?php echo $this->url;?>/js/custom_menu_images.js"></script>
    <script>
    var menuID = '<?php echo $_GET['cmi_id'];?>';
    jQuery(document).ready(function(){
		CustomMenuImages
			.init({prefix: '<?php echo $this->prefix?>', mediaUploadUrl: '<?php echo admin_url('media-upload.php');?>'})
	});    
    </script>
    <?php
	}	
	public function get_thumb( $id, $size = 'full' ) {
		global $wp_version;				
		/* Get the originally uploaded size path. */
		list( $img_url, $img_path ) = get_attachment_icon_src( $id, true );
		if($size == 'full') return $img_url;
		/* Attepmt to get custom intermediate size. */
		$img = image_get_intermediate_size( $id, $size ); //detail
		
		/* If custom intermediate size cannot be found, attempt to create it. */
		if( !$img ) {
			
			/* Need to check to see if fullsize path can be found - sometimes this disappears during import/export. */
			if( !is_file( $img_path ) ) {
				$wp_upload_dir = wp_upload_dir();
				$img_path = $wp_upload_dir['path'] . get_post_meta( $id, '_wp_attached_file', true );
			}
			
			if( is_file( $img_path ) ) {
				$new = image_resize( $img_path, $this->detail_size[0], $this->detail_size[1], $this->detail_size[2] );
				
				if( !is_wp_error( $new ) ) {
					$meta = wp_generate_attachment_metadata( $id, $img_path );
					wp_update_attachment_metadata( $id, $meta );
					$img = image_get_intermediate_size( $id, $size );
				}
			}
		}
		
		/* Custom intermediate size cannot be created, try for thumbnail. */
		if( !$img ) {
			$img = image_get_intermediate_size( $id, 'thumbnail' );
			//echo "3:";print_r($img);
		}
		
		/* Thumbnail cannot be found, try fullsize. */
		if( !$img ) {
			$img['url'] = wp_get_attachment_url( $id );
		}
		
		/* Administration */
		if( isset( $img['url'] ) && !empty( $img['url'] ) ) {
			return $img['url'];
		}
		else if( is_admin() ) {
			return $this->url . 'deleted-image.png';
		}
		return false;
	}
	function add_image(){
		die(json_encode(array(
			'cmi_id' 	=> $_POST['cmi_id'],
			'thumb' => $this->get_thumb($_POST['attachment_id'], $_POST['size'])
		)));
	}
	function remove_image(){
		die(json_encode(array(
			'cmi_id' 	=> $_POST['cmi_id']
		)));
	}
	
}
$custom_menu_image = new CustomMenuImage();
