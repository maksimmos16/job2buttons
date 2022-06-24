<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Change a currency symbol
 */
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);
function change_existing_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
          case 'ZMW': $currency_symbol = 'ZMK'; break;
     }
     return $currency_symbol;
}

function easyjob_enqueue_scripts() {
    wp_enqueue_script( 'my', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), null, true );  
 }
add_action( 'wp_enqueue_scripts', 'easyjob_enqueue_scripts', 10 );

/* Search JObs */
add_action('wp_ajax_search_job', 'home_job_search'); 
add_action('wp_ajax_nopriv_search_job', 'home_job_search');
function home_job_search(){   
	global $wpdb;
	$searchText = $_POST['search'];
    $search_arr = array();
    $query_args = array( 's' => $searchText, 'post_type' => 'job_post', 'post_status' => 'publish' );
	$query = new WP_Query( $query_args );
	// echo $query->request;
	/*$searchquery = $wpdb->get_results("
       SELECT * FROM ej_posts WHERE `ej_posts.post_title` LIKE ('%$searchText%')
OR (`ej_posts.post_content` LIKE '%$searchText%')))
       ORDER BY `ej_posts.post_date` DESC");
	print_r($searchquery);*/
	// echo $searcshquery;
	if ( $query->have_posts() ) {
		while ($query -> have_posts()): $query -> the_post(); 
		/*$id = get_the_ID();
        $name = get_the_title();
        $link = get_the_permalink();*/
        $search_arr[] = array("id" => get_the_ID(), "name" => get_the_title(), "link" => get_the_permalink() );	
		endwhile;
	}
	/*foreach ($query->posts as $post) {
        $id = $post->ID;
        $name = $post->post_title;
        $link = $post->guid;

        $search_arr[] = array("id" => $id, "name" => $name, "link" => $link );
    }*/
    /*$sql = "SELECT id,name FROM user where name like '%".$searchText."%' order by name asc limit 5";

    $result = mysqli_query($con,$sql);


    */
// $search_arr[] = array("id" => "1", "name" => "zee");
    echo json_encode($search_arr);
die();
}


/* * ********************************* */
/* Ajax handler for Candidate Edit Application */
/* * ********************************* */
add_action('wp_ajax_view_application', 'nokri_view_application_edit');
if (!function_exists('nokri_view_application_edit')) {

    function nokri_view_application_edit() {
        global $nokri;
        $job_id = ($_POST['app_job_id']);
        $allow = (isset($nokri['allow_questinares']) && $nokri['allow_questinares'] != "") ? $nokri['allow_questinares'] : false;
        $user_id = get_current_user_id();
        $job_cvr = get_post_meta($job_id, '_job_applied_cover_' . $user_id, true);
        $job_cv = get_post_meta($job_id, '_job_applied_resume_' . $user_id, true);
        $array_data = explode('|', $job_cv);
        $attachment_id = $array_data[1];

        $qstn_ans_html = '';
        if ($allow) {
            $qstn_ans_html = nokri_get_questions_answers($job_id, $user_id);
            $qstn_ans_html = '<div class="dashboard-questions-box">' . $qstn_ans_html . '</div>';
        }
        if (is_numeric($attachment_id)) {
            $link = nokri_set_url_param(get_the_permalink($attachment_id), 'attachment_id', esc_attr($attachment_id));
            $final_url = esc_url(nokri_page_lang_url_callback($link));
            $resume_link = '<a class="btn btn-custom" href="' . $final_url . '&download_file=1"">' . esc_html__('Download', 'nokri') . '</a>';
            $label = esc_html__('You have Applied Against Resume', 'nokri');
        } else {
            $resume_link = '<a href="' . $attachment_id . '">' . esc_html__('View profile', 'nokri') . '</a>';
            $label = esc_html__('You have Applied Against Linkedin Profile', 'nokri');
        }
        if ($attachment_id == '') {
            $resume_link = '<a href="' . esc_url(get_author_posts_url($user_id)) . '">' . esc_html__('View profile', 'nokri') . '</a>';
            $label = esc_html__('You have Applied Against your Profile', 'nokri');
        }
        $filename_only = basename(get_attached_file($attachment_id));
        if ($job_cvr != '') {
            $job_cvr_html = '<div class="form-group">
							<label class="">' . esc_html__('Your Cover Letter777', 'nokri') . '</label>
							<textarea class="form-control rich_textarea" rows="10" name="ckeditor" >' . $job_cvr . '</textarea>
						 </div>';
        }
        echo '<div class="modal fade resume-action-modal" id="appmodeledit" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">' . get_the_title($job_id) . '</h4>
                </div>
                
                <div class="modal-body">
                	<div class="form-group">
                    	<div class="row">
                        	<div class="company-search-toggle">
                                <div class="col-md-9 col-xs-12 col-sm-9">
                                    <label>' . $label . '</label>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-3">
                                  ' . $resume_link . '
                                </div>
                            </div>
                        </div>
                    </div>
                   
  
					' . $qstn_ans_html . '
                    ' . $job_cvr_html . "1234567890".'
                </div>
                <div class="modal-footer">
                </div>
              </div>
            </div>
        </div>';
        die();
    }

}