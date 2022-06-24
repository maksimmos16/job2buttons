<?php
if ( !function_exists ( 'nokri_getMenuItemsData' ) ) {
function nokri_getMenuItemsData($item_id, $item, $menu_level = 1, $show_indicator = true)
{
		$item_data = array();
		$arr = array();
		$angleAerrow = '';
		global $wpdb;
		$metaID = is_numeric( $item_id ) ? $item_id : '0';
		$query = "SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key='_menu_item_menu_item_parent' AND meta_value=$metaID";
		$count_children = $wpdb->get_var( $query );
		$data['is_parent'] = $count_children;
		$data['item_parent_id'] = get_post_meta($metaID, '_menu_item_menu_item_parent', true);
		$data['lebel_color'] = get_post_meta($metaID, '_menu_item_color', true);
		$data['lebel_text'] = get_post_meta($metaID, '_menu_item_color_text', true);
		$data['menu_icons'] = get_post_meta($metaID, '_menu_item_menu_icons', true);
		$data['menu_classes'] = get_post_meta($metaID, '_menu_item_is_megamenu_class', true);
		$data['megamenu_cols'] = get_post_meta($metaID, '_menu_item_is_megamenu', true);
		$data['megamenu_open_side'] = get_post_meta($metaID, '_menu_item_menu_open_side', true);
		$data['megamenu_simple_offset'] = get_post_meta($metaID, '_menu_item_menu_simple_offset', true);
		$data['megamenu_vertical_offset'] = get_post_meta($metaID, '_menu_item_menu_vertical_offset', true);
		$menu_icon = $data['menu_icons'];
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';		
		$menu_title = apply_filters( 'the_title', $item->title, $item->ID );
		$lebel_color = $data['lebel_color'] != "" ? $data['lebel_color'] : '';
		$lebel_text  = $data['lebel_text'] != "" ? $data['lebel_text'] : '';
		$lebel_html = '';
		if( $lebel_text != "" )
		{
			$bg_img = "\\s\\t\\y\\l\\e ='background-color: $lebel_color'";
			$lebel_html = '<span class="label ml-10" '. str_replace('\\',"",$bg_img) .'>'.esc_html($lebel_text).'</span>';	
		}
		$menu = '';
		$item_data['has_parent'] = ($data['is_parent']  > 0 ) ? 'yes'  : 'no';
		$item_data['cols']  	 = ($data['megamenu_cols'] != "") ? $data['megamenu_cols'] : '';
		$item_data['megamenu_menu_title'] = $item->title;
		$item_data['megamenu_open_side'] = ($data['megamenu_open_side'] == 'left-side' ) ? 'left-side'  : '';
		$item_data['megamenu_simple_offset'] = ((isset($data['megamenu_simple_offset'])) && $data['megamenu_simple_offset'] != '' ) ? $data['megamenu_simple_offset']  : '';
		$item_data['megamenu_vertical_offset'] = ((isset($data['megamenu_simple_offset'])) && $data['megamenu_vertical_offset'] != '' ) ? $data['megamenu_vertical_offset']  : '';
		$menu_classes = '';
		if( $data['menu_classes'] == "mega-menu" )
		{
			$menu_classes = 'drop-down';	
		}		
		else
		{
			$menu_classes = ( $data['is_parent'] > 0 ) ? 'drop-down-multilevel' : '';
		}
	
		$item_data['class'] = $menu_classes;
		
		$indicatorR ='';
		$indicatorL = '';
		if( $menu_level == 1 )
		{
			$icon  = ( $menu_icon != "" ) ? ' <i class="'.$menu_icon.'"></i>' : '';
			$indicatorL = ($data['is_parent'] != "" )  ? $icon : '';
			
			$angleAerrow = ($data['is_parent'] > 0 )  ? '<i class="fa fa-angle-down fa-indicator"></i>' : '';
			
			$cls = '';
			if( $menu_classes  == "drop-down-multilevel")
			{
				$cls = $item_data['class'] .' '. $item_data['cols'];
				$item_data['wrapHTMLStarts']	= '<ul class="'.$cls.'">';
				$item_data['wrapHTMLEnds']		= '</ul>';
			}
			else if( $menu_classes  == "drop-down")
			{
				
				$megamenu_simple_offset  = ((isset($data['megamenu_simple_offset'])) && $data['megamenu_simple_offset'] != '' ) ? $data['megamenu_simple_offset']  : '';
				$megamenu_vertical_offset = ((isset($data['megamenu_simple_offset'])) && $data['megamenu_vertical_offset'] != '' ) ? $data['megamenu_vertical_offset']  : '';
				
				$newClass = $megamenu_simple_offset . ' '. $megamenu_vertical_offset;				
				
				$cls = $item_data['class'] .' ' . $newClass .' '. $item_data['cols'];
				$item_data['wrapHTMLStarts']	= '<div class="'.$cls.'"><div class="grid-row">';
				$item_data['wrapHTMLEnds']	= '</div></div>';
			}
			else
			{
				$item_data['wrapHTMLStarts']	= '';
				$item_data['wrapHTMLEnds']	= '';
				
			}
			
			
		}
		// checkinf for RTL
		global $invest_theme;
		$angle = 'fa-angle-right';
		if( isset( $invest_theme['sb_rtl'] ) && $invest_theme['sb_rtl'] )
		{
			$angle = 'fa-angle-left';
		}
		if( $menu_level == 2 )
		{
			$icon  = ( $menu_icon != "" ) ? ' <i class="'.$menu_icon.'"></i>' : '';
			$indicatorL = ($data['is_parent'] != ""  )  ? $icon : '';
			$angleAerrow = ($data['is_parent'] > 0 )  ? '<i class="fa '.esc_attr( $angle ).' fa-indicator"></i>' : '';
		}
		if( $menu_level == 3 )
		{
			if($show_indicator == true )
			{
				$indicatorL = ( $menu_icon != "" ) ? ' <i class="'.$menu_icon.'"></i>' : '<i class="fa '.esc_attr( $angle ).' fa-indicator"></i>';
			}
			else
			{
				$indicatorL = ( $menu_icon != "" ) ? ' <i class="'.$menu_icon.'"></i>' : '';
			}
		}		
		
			
		
		$item_data['achor'] = '<a '.$attributes.'>'.$indicatorL. $menu_title . ' '. $lebel_html .' '.$angleAerrow.$indicatorR.'</a>';
		
		 
		 return $item_data;
}
}


if ( !function_exists ( 'nokri_themeMenu' ) ) {
function nokri_themeMenu($theme_location)
{

$menu_html = '';
if ( ($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location]) ) { 
	
	
	$menu = get_term( $locations[$theme_location], 'nav_menu' );
        
  
	if( isset($menu->term_id) )
	{
		$menu_items = wp_get_nav_menu_items($menu->term_id);
                
               
                
                
		foreach( $menu_items as $item )
		{
			if($item->object == 'wpml_ls_menu_item')
			{
				continue;
			}
		}
		
		foreach( $menu_items as $item )
		{
                    
                    
			if( $item->menu_item_parent == 0 )
			{	
				$level1 = 1;
				$menuItems1  = nokri_getMenuItemsData($item->ID, $item, 1);
                                
                                $add_class = isset($item->classes[0])  ?  $item->classes[0] : '' ;
				$menu_html .= '<li  class="'.$add_class.'">';
				$menu_html .= 	$menuItems1['achor'];	
				$lvlHTMLClose = '';
				foreach( $menu_items as $sub_item )
				{
                                                                  
					if( $item->ID == $sub_item->menu_item_parent )
					{
						$add_class1 = isset($sub_item->classes[0])  ?  $sub_item->classes[0] : '' ;
						$mainMenuClass  =  $menuItems1['class'];
						$mainMenuClass2 = $menuItems1['cols'];
		
						/* For Mega Menu  && Has Parent Starts*/		
						if($mainMenuClass == 'drop-down' || $mainMenuClass == 'drop-down-multilevel')
						{
							$menuItems2 = nokri_getMenuItemsData($sub_item->ID, $sub_item, 2);
							if( $level1 == 1 ){	
								$menu_html .= $menuItems1['wrapHTMLStarts'];
								$lvlHTMLClose = $menuItems1['wrapHTMLEnds'];
							}
							if( $mainMenuClass == 'drop-down-multilevel' )
							{		
								$menu_html .= '<li class="hoverTrigger '.$add_class1.'">';
								$menu_html .= 	$menuItems2['achor'];
							}
				
							$mega_menu_html = '';
							$megamenu_cols = ($menuItems2['cols'] != "") ? $menuItems2['cols'] : 'grid-col-4';
							$level2 = 1;
							$closeHTML = 'no';
							//megamenu_open_side
							$openSide = $menuItems2['megamenu_open_side'];
							foreach( $menu_items as $sub_sub_item )
							{
								if( $sub_item->ID == $sub_sub_item->menu_item_parent )	
								{
                                                                    $add_class2 = isset($sub_sub_item->classes[0])  ?  $sub_sub_item->classes[0] : '' ;
									if( $level2 == 1 )
									{ 
									 $mega_menu_html .= ($mainMenuClass == 'drop-down-multilevel') ? '<ul class="drop-down-multilevel '.$megamenu_cols.' '. $openSide. '">' : '<div class="'.esc_attr($megamenu_cols).'"><h4>'.esc_html($menuItems2['megamenu_menu_title']).'</h4><ul>';
										
									}	
									$show_indicator = ($mainMenuClass == 'drop-down-multilevel') ? false : true;	
									$menuItems3 = nokri_getMenuItemsData($sub_sub_item->ID, $sub_sub_item, 3, $show_indicator);
									$mega_menu_html .= '<li class="'.$add_class2.'">';	
									$mega_menu_html .= $menuItems3['achor'];
									$mega_menu_html .= '</li>';														
									$closeHTML = 'yes';
									$level2++;
								}						
							}
							if( $closeHTML == 'yes' ) 
							{ 
								$mega_menu_html .= ($mainMenuClass == 'drop-down-multilevel') ? '</ul>' : '</ul></div>'; 
								$closeHTML == 'no'; 
							}
							$menu_html .= $mega_menu_html;
							$level1++;
						
						}
						/* For Mega Menu  && Has Parent Ends*/	
						
					}			
					
				}
				if( $lvlHTMLClose != "" ){ $menu_html .= $lvlHTMLClose; $lvlHTMLClose = ''; }
				$menu_html .= '</li>';
			}	
		}
	}
	else
	{
	// $menu_html .= '<li><a href="'.esc_url( home_url('/') ).'">'.esc_html__("Home", "nokri").'</a></li>'; 
	}
}
else
{
 $menu_html .= '<li><a href="'.esc_url( home_url('/') ).'">'.esc_html__("Home", "nokri").'</a></li>'; 
}
//echo($menu_html);

echo nokri_wpml_nav_menu_callback($menu_html,$theme_location);


}
}


add_action( 'init', array( 'nokri_Nav_Menu_Item_Custom_Fields', 'setup' ) );
if (! class_exists ( 'nokri_Nav_Menu_Item_Custom_Fields' )) {
class nokri_Nav_Menu_Item_Custom_Fields {
static $options = array(
	'item_tpl' => '
		<p class="additional-menu-field-{name} description description-thin">
			<label for="edit-menu-item-{name}-{id}">
				{label}<br>
				<input
					type="{input_type}"
					id="edit-menu-item-{name}-{id}"
					class="widefat code edit-menu-item-{name}"
					name="menu-item-{name}[{id}]"
					placeholder="{placeholder}"
					value="{value}">
			</label>
		</p>',
	'select_tpl' => '
		<p class="additional-menu-field-{name} description description-thin">
			<label for="edit-menu-item-{name}-{id}">
				{label}<br>
				<select
					id="edit-menu-item-{name}-{id}"
					class="widefat code edit-menu-item-{name}"
					name="menu-item-{name}[{id}]"
					>{value}</select>
			</label>
		</p>',		
);
static function setup() {
	if ( !is_admin() )
		return;
	$new_fields = apply_filters( 'nokri_nav_menu_item_additional_fields', array() );
	if ( empty($new_fields) )
		return;
		
	self::$options['fields'] = self::get_fields_schema( $new_fields );
	add_filter( 'wp_edit_nav_menu_walker', function () {
		return 'nokri_Walker_Nav_Menu_Edit';
	});
	add_action( 'save_post', array( __CLASS__, '_save_post' ), 10, 2 );
}
static function get_fields_schema( $new_fields ) {
	$schema = array();
	foreach( $new_fields as $name => $field) {
		if (empty($field['name'])) {
			$field['name'] = $name;
		}
		$schema[] = $field;
	}
	return $schema;
}
static function get_menu_item_postmeta_key($name) {
	return '_menu_item_' . $name;
}
/**
 * Inject the 
 * @hook {action} save_post
 */
static function get_field( $item, $depth, $args ) {
	$new_fields = '';
	foreach( self::$options['fields'] as $field ) {
		
		
		$field['id'] = $item->ID;
		
		if( $field['input_type'] == 'select') 
		{
				
	
				$ar = '';
				
				
				/*Main menu select options*/
				if( $field['name'] == 'is_megamenu_class' )
				{
					$ar = '';
					$savedValue = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
					$vals = array(esc_html__("Select Option", "nokri") => "", esc_html__("Simple Menu", "nokri") => "simple-menu", esc_html__("Mega Menu", "nokri") =>"mega-menu");
					foreach( $vals as $val => $name )
					{
						$selected = ( $savedValue == $name ) ? 'selected="selected"' : '';
						$ar .= '<option value="'.esc_attr($name).'" '.$selected.'>'.esc_html($val).'</option>';	
					}
					
					
				}
				
				/*Main menu column*/
				if( $field['name'] == 'is_megamenu' )
				{
					$savedValue1 = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
					$ar = '';
					for( $size = 12; $size >= 1 ; $size-- )
					{
						$selected = ( 'grid-col-'.$size == $savedValue1 ) ? 'selected="selected"' : '';
						$ar .= '<option value="grid-col-'.esc_attr($size).'" '.$selected.'>'.esc_html__("Column size ","nokri").$size.'</option>';	
					}
										
				}				

				/*Main menu column*/
				if( $field['name'] == 'menu_open_side' )
				{
					$savedValue = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
					$vals = array(esc_html__("Select Option", "nokri") => "", esc_html__("Right Side", "nokri") => "right-side", esc_html__("Left Side", "nokri") =>"left-side");
					$ar = '';
					foreach( $vals as $val => $name )
					{
						$selected = ( $savedValue == $name ) ? 'selected="selected"' : '';
						$ar .= '<option value="'.esc_attr($name).'" '.$selected.'>'.esc_html($val).'</option>';	
					}
					
				}	
				
				/*Main offset*/
				if( $field['name'] == 'menu_simple_offset' )
				{
					$savedValue1 = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
					$ar  = '';
					$ar .= '<option value="">'.esc_html__("Select Option - (No Offset)","nokri").'</option>';
					for( $size = 12; $size >= 1 ; $size-- )
					{
						$selected = ( 'offset-'.$size == $savedValue1 ) ? 'selected="selected"' : '';
						$ar .= '<option value="offset-'.esc_attr($size).'" '.$selected.'>'.esc_html__("Column Offset ","nokri").$size.'</option>';	
					}
										
				}	
				/*Main menu menu_vertical_offset */
				if( $field['name'] == 'menu_vertical_offset' )
				{
					$savedValue1 = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
					$ar  = '';
					$ar .= '<option value="">'.esc_html__("Select Option - (No Offset)","nokri").'</option>';
					for( $size = 12; $size >= 1 ; $size-- )
					{
						$selected = ( 'offset-'.$size . '-vertical' == $savedValue1 ) ? 'selected="selected"' : '';
						$ar .= '<option value="offset-'.esc_attr($size).'-vertical" '.$selected.'>'.esc_html__("Vertical Offset","nokri").$size.'</option>';	
					}
										
				}									

								
				$field['value'] = $ar;
				$new_fields .= str_replace(
					array_map(function($key){ return '{' . $key . '}'; }, array_keys($field)),
					array_values($field),
					self::$options["select_tpl"]
				);			
			
		}
		else
		{
			$field['value'] = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);

			$new_fields .= str_replace(
				array_map(function($key){ return '{' . $key . '}'; }, array_keys($field)),
				array_values(array_map('esc_attr', $field)),
				self::$options["item_tpl"]
			);			
		}

	}
	return $new_fields;
}
/**
 * Save the newly submitted fields
 * @hook {action} save_post
 */
static function _save_post($post_id, $post) {
	if ( $post->post_type !== 'nav_menu_item' ) {
		return $post_id; // prevent weird things from happening
	}
	foreach( self::$options['fields'] as $field_schema ) {
		$form_field_name = 'menu-item-' . $field_schema['name'];
		// @todo FALSE should always be used as the default $value, otherwise we wouldn't be able to clear checkboxes
		if (isset($_POST[$form_field_name][$post_id])) {
			$key = self::get_menu_item_postmeta_key($field_schema['name']);
			$value = stripslashes($_POST[$form_field_name][$post_id]);
			update_post_meta($post_id, $key, $value);
		}
	}
}
}
}
// @todo This class needs to be in it's own file so we can include id J.I.T.
// requiring the nav-menu.php file on every page load is not so wise
require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

if (! class_exists ( 'nokri_Walker_Nav_Menu_Edit' )) {
class nokri_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	$item_output = '';
	parent::start_el($item_output, $item, $depth, $args);
	// Inject $new_fields before: <div class="menu-item-actions description-wide submitbox">
	if ( $new_fields = nokri_Nav_Menu_Item_Custom_Fields::get_field( $item, $depth, $args ) ) {
		$item_output = preg_replace('/(?=<div[^>]+class="[^"]*submitbox)/', $new_fields, $item_output);
	}
	$output .= $item_output;
}
}
}

// Somewhere in config...
add_filter( 'nokri_nav_menu_item_additional_fields', 'nokriMenu_item_additional_fields' );
if ( !function_exists ( 'nokriMenu_item_additional_fields' ) ) {
function nokriMenu_item_additional_fields( $fields ) {

	
	
	$fields['color'] = array(
		'name' => 'color',
		'label' => esc_html__('Lebel Color', 'nokri'),
		'container_class' => 'link-color',
		'input_type' => 'color',
	);
	$fields['color_text'] = array(
		'name' => 'color_text',
		'label' => esc_html__('Lebel Text', 'nokri'),
		'container_class' => 'link-color-text',
		'placeholder' => esc_html__('Enter some text', 'nokri'),
		'input_type' => 'text',
	);	
	$fields['megamenu_class'] = array(
		'name' => 'is_megamenu_class',
		'label' => esc_html__('This select option is only for 1st level menu', 'nokri'),
		'container_class' => 'megamenu-classes',
		'input_type' => 'select',
	);		
	$fields['megamenu'] = array(
		'name' => 'is_megamenu', 
		'label' => esc_html__('In parent add bigger value and in 2nd level add small value', 'nokri'),
		'container_class' => 'megamenu-checkbox',
		'input_type' => 'select',
	);	
	$fields['menu_icons'] = array(
		'name' => 'menu_icons',
		'label' => esc_html__('Menu Icons (Font Awesome Icons). ', 'nokri').'http://fontawesome.io/icons/',
		'container_class' => 'megamenu-icons',
		'placeholder' => 'fa fa-address-book',
		'input_type' => 'text',
	);
	$fields['menu_open_side'] = array(
		'name' => 'menu_open_side',
		'label' => esc_html__('Select Options - For simple menu only', 'nokri'),
		'container_class' => 'megamenu-open-side',
		'input_type' => 'select',
	);	
	
	$fields['menu_simple_offset'] = array(
		'name' => 'menu_simple_offset',
		'label' => esc_html__('Select Options (For mega menu 1st level menu only)', 'nokri'),
		'container_class' => 'megamenu-simple-offset',
		'input_type' => 'select',
	);		

	$fields['menu_vertical_offset'] = array(
		'name' => 'menu_vertical_offset',
		'label' => esc_html__('Select Options (For mega menu 1st level menu only)', 'nokri'),
		'container_class' => 'megamenu-vertical-offset',
		'input_type' => 'select',
	);		
		
	return $fields;
}
}

function nokri_wpml_nav_menu_callback($menu_html = '', $theme_location ="") 
{
        $nokri_theme = false;
        if (class_exists('Redux')) {
            $nokri_theme = Redux::getOption('nokri', 'nokri_display_wpml_in_nav');
        }
        if (function_exists('icl_object_id') && $nokri_theme) {
            if (($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location])) {
                $menu = get_term($locations[$theme_location], 'nav_menu');
				if (isset($menu->term_id)) {
                    $menu_items = wp_get_nav_menu_items($menu->term_id);
                    $menu_position = 'end';
                    if (isset($menu_items[0]) && $menu_items[0]->object == 'wpml_ls_menu_item') {
                        $menu_position = 'start';
                    }
                    $wpml_menu_html = '';
                    ob_start();
                    $wpml_menu_html .= '<li class="hoverTrigger sb-wpml">';
                    do_action('wpml_add_language_selector');
                    $wpml_menu_html .= ob_get_contents();
                    $wpml_menu_html .= '</li>';
                    ob_end_clean();
                    $final_menu = '';
                    if ($menu_position == 'start') {
                        $menu_html = $wpml_menu_html . $menu_html;
                    } else {
                        $menu_html = $menu_html . $wpml_menu_html;
                    }
                    return $menu_html;
                }
            }
        } 
		else 
		{
            return $menu_html;
        }
}