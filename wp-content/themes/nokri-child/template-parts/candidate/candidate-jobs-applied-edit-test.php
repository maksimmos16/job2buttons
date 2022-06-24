<?php
global $nokri;


$current_id = get_current_user_id();
$candidate = get_user_meta($current_id, '_sb_reg_type', true);
/* All Job Page */
$all_jobs = '';
if ((isset($nokri['sb_all_job_page'])) && $nokri['sb_all_job_page'] != '') {
    $all_jobs = ($nokri['sb_all_job_page']);
}
$job_name = '';
if (isset($_GET['job_name'])) {
    $job_name = $_GET['job_name'];
}
?>
<div class="main-body">
    <div class="cp-loader"></div>
    <div class="dashboard-job-stats">
        <div class="dashboard-job-filters">
            <div class="row">
                <div class="col-md-7 col-xs-12 col-sm-6">
                    <h4><?php echo esc_html__('Edit applied jobs', 'nokri'); ?></h4>
                </div>
                <div class="col-md-5 col-xs-12 col-sm-4">
                    <div class="form-group">
                        <form role="search"  id="job_search" method="get" class="searchform ">
                            <input type="hidden" name="candidate-page" value="jobs-applied" >
                            <input type="text" class="form-control" name="job_name" value="<?php echo esc_html($job_name); ?>" placeholder="<?php echo esc_html__('Keyword or name', 'nokri'); ?>">
                            <a href="javascript:void(0)" class="a-btn no-top search_aplied_job"><i class="ti-search"></i></a>
                            <?php echo nokri_form_lang_field_callback(true); ?>
                        </form>
                    </div>
                </div>  
            </div>
        </div>
        <div class="dashboard-posted-jobs">
            <div class="posted-job-list jobs-saved header-title">
                <ul class="list-inline">

                    <li class="posted-job-title"><?php echo esc_html__('Title', 'nokri'); ?></li>
                    <li class="posted-job-status"><?php echo esc_html__('Status', 'nokri'); ?></li>            
                    <li class="posted-job-status"><?php echo esc_html__('Type', 'nokri'); ?></li>
                    <li class="posted-job-expiration"><?php echo esc_html__('Applied on', 'nokri'); ?></li>
                    <li class="posted-job-expiration"><?php echo esc_html__('Detail ', 'nokri'); ?></li>
                </ul>
            </div>
            <?php
//pagination
            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
            $args = array(
                'post_type' => 'job_post',
                's' => $job_name,
                'paged' => $paged,
                'posts_per_page' => get_option('posts_per_page'),
                'meta_key' => '_job_applied_date_' . $current_id,
                'orderby' => 'meta_value',
                'order' => 'date',
                'meta_query' => array(
                    'relation' => 'AND',
                    array('key' => '_job_applied_resume_' . $current_id),
                    array(
                        'key' => '_job_status',
                        'value' => 'active',
                        'compare' => '='
                    ),
                ),
            );
            $args = nokri_wpml_show_all_posts_callback($args);
            $query = new WP_Query($args);
            if ($query->have_posts()) {
                $count = 1;
                while ($query->have_posts()) {
                    $query->the_post();
                    $query->post_author;
                    $job_id = get_the_id();
                    $post_author_id = get_post_field('post_author', $job_id);
                    $company_name = get_the_author_meta('display_name', $post_author_id);
                    $job_type = wp_get_post_terms($job_id, 'job_type', array("fields" => "ids"));
                    $job_type = isset($job_type[0]) ? $job_type[0] : '';
                    $job_salary = wp_get_post_terms($job_id, 'job_salary', array("fields" => "ids"));
                    $job_salary = isset($job_salary[0]) ? $job_salary[0] : '';
                    $job_currency = wp_get_post_terms($job_id, 'job_currency', array("fields" => "ids"));
                    $job_currency = isset($job_currency[0]) ? $job_currency[0] : '';
                    $job_salary_type = wp_get_post_terms($job_id, 'job_salary_type', array("fields" => "ids"));
                    $job_salary_type = isset($job_salary_type[0]) ? $job_salary_type[0] : '';
                    $job_date = get_post_meta($job_id, '_job_applied_date_' . $current_id, true);
                    $job_date = date_i18n(get_option('date_format'), strtotime($job_date));
                    $job_cvr = get_post_meta($job_id, '_job_applied_resume_' . $current_id, true);
                    $cand_status = get_post_meta($job_id, '_job_applied_status_' . $current_id, true);

                    $cand_final = nokri_canidate_apply_status($cand_status);
                    /* Getting Questions Answers */
                    $label_class = '';
                    if ($cand_status == '0') {
                        $label_class = 'default';
                    } elseif ($cand_status == '1') {
                        $label_class = 'info';
                    } elseif ($cand_status == '2') {
                        $label_class = 'danger';
                    } elseif ($cand_status == '3') {
                        $label_class = 'primary';
                    } elseif ($cand_status == '4') {
                        $label_class = 'warning';
                    } elseif ($cand_status == '5') {
                        $label_class = 'success';
                    }

                    /* Getting Company  Profile Photo */
                    $image_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
                    if (isset($nokri['nokri_user_dp']['url']) && $nokri['nokri_user_dp']['url'] != "") {
                        $image_link = array($nokri['nokri_user_dp']['url']);
                    }
                    if (get_user_meta($post_author_id, '_sb_user_pic', true) != "") {
                        $attach_id = get_user_meta($post_author_id, '_sb_user_pic', true);
                        $image_link = wp_get_attachment_image_src($attach_id, 'nokri_job_post_single');
                    }
                    ?>
                    <div class="posted-job-list jobs-saved">
                        <ul class="list-inline">

                            <li class="posted-job-title">
                                <?php if (esc_url($image_link[0])) { ?>
                                    <div class="posted-job-title-img">
                                        <a href="javascript:void(0)"><img src="<?php echo esc_url($image_link[0]); ?>" class="img-responsive" alt="<?php echo esc_html__('micheal', 'nokri'); ?>"></a>
                                    </div>
                                <?php } ?> 
                                <div class="posted-job-title-meta">
                                    <a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?> </a>
                                    <p><?php echo esc_html($company_name); ?></p>
                                </div>
                            </li>
                            <li class="posted-job-status"> <span class="label label-<?php echo esc_attr($label_class); ?>"><?php echo esc_html($cand_final); ?></span></li>
                            <li class="posted-job-status"> <span class="label label-default"><?php echo nokri_job_post_single_taxonomies('job_type', $job_type); ?></span></li>
                            <li class="posted-job-expiration"><?php echo esc_attr($job_date); ?></li>
                            <li class="posted-job-action"> 
                                <a href="javascript:void(0)" class="btn btn-custom view_app" data-value = "<?php echo esc_attr($job_id); ?>" data-toggle="modal"   data-target="#appmodel"><?php echo esc_html__('Details', 'nokri'); ?></a>
                            </li>
                            <!-- <li class="posted-job-action"> 
                                <a href="javascript:void(0)" class="btn btn-custom view_app" data-value = "<?php echo esc_attr($job_id); ?>" data-toggle="modal"   data-target="#appmodel"><?php echo esc_html__('Details', 'nokri'); ?></a>
                            </li> -->
                        </ul>
                    </div>	
                    <?php
                    $count++;
                }
            } else {
                ?>
                <div class="dashboard-posted-jobs">
                    <div class="notification-box">
                        <div class="notification-box-icon"><span class="ti-info-alt"></span></div>
                        <h4><?php echo esc_html__('You have not applied for any job yet', 'nokri'); ?></h4>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="pagination-box clearfix">
<?php echo nokri_job_pagination($query); ?>
        </div>




<?php
$current_id = get_current_user_id();
$candidate = get_user_meta($current_id, '_sb_reg_type', true);
$job_id = ($_POST['app_job_id']);
$allow = (isset($nokri['allow_questinares']) && $nokri['allow_questinares'] != "") ? $nokri['allow_questinares'] : false;
$user_id = get_current_user_id();
$job_cvr = get_post_meta($job_id, '_job_applied_cover_' . $user_id, true);
$job_cv = get_post_meta($job_id, '_job_applied_resume_' . $user_id, true);
$array_data = explode('|', $job_cv);
$attachment_id = $array_data[1];

echo "<br>job_id<br>";
echo $job_id;
echo "<br>job_cvr<br>";
echo $job_cvr;
echo "<br>job_cv<br>";
echo $job_cv;
print_r($job_cvr);
print_r($job_cv);
echo "<br>job_id<br>";

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
        echo get_the_title($job_id);
        echo $label;
        echo $resume_link;
        echo $qstn_ans_html;
        echo $job_cvr_html;

        echo '<div class="row">
            <div class="col-md-12">
              <div class="modal-content">
               
                
              
                	
                    	<div class="row">
                        	
                                <div class="col-md-9 col-xs-12 col-sm-9">
                                    <label>' . $label . '</label>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-3">
                                  ' . $resume_link . '
                                </div>
                          
                       
                 
                   
  
					' . $qstn_ans_html . '
                    ' . $job_cvr_html . "12345".'
                </div>
               
              </div>
            </div>
        </div>';

        
?>

<div class="container">
    <div class="row">
        <?php 
        echo "current_id<br>";
        echo $current_id;
        echo "<br>user_id<br>";
        echo $user_id;
        echo "<br>candidate<br>";
        echo $candidate?>
    </div>
</div>


