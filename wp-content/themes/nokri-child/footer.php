<?php
global $nokri;
$dashboard_page = $footer_style = '';
$is_elementor_footer = isset($nokri['is_elementor_footer']) ? $nokri['is_elementor_footer'] : false;
if ($is_elementor_footer == false) {
    /* Search page lay out */
    $search_page_layout = ( isset($nokri['search_page_layout']) && $nokri['search_page_layout'] != "" ) ? $nokri['search_page_layout'] : "";
    /* dashboard page */
    $dashboard_page = ( isset($nokri['sb_dashboard_page']) && $nokri['sb_dashboard_page'] != "" ) ? $nokri['sb_dashboard_page'] : "";
    if ((isset($nokri['select_footer_layout'])) && $nokri['select_footer_layout'] != '') {
        $footer_style = ($nokri['select_footer_layout']);
    }
    /* No footer in map search */
    if ($search_page_layout == '3' && basename(get_page_template()) == 'page-search.php') {
        
    } else {
        if (basename(get_page_template()) != 'page-dashboard.php') {
            get_template_part('template-parts/footers/footer', $footer_style);
        }
    }
    /* Hidden Inputs */
    get_template_part('template-parts/hidden', 'inputs');
    /* Email verification and reset password */
    get_template_part('template-parts/verification', 'logic');
    ?>
    </div>
    <div id="toast-container" class="toast-top-right"  >
        <div class="toast toast-success" aria-live="polite"  id="progress_loader">
            <div class="toast-title"><?php echo esc_html__('Uploading', 'nokri') ?></div>
            <div class="toast-message" id="progress_counter"></div>
        </div>
    </div>
    <div id="popup-data"></div>
    <div id="app-data"></div>
    <div id="short-desc-data"></div>
    <div id="status_action_data"></div>
    <div id="job-alert-dataaaaa"></div> 
    <?php
    if (isset($nokri['banners_code_footer']) && $nokri['banners_code_footer'] != '') {
        echo ''.($nokri['banners_code_footer']);
    }
    ?> 
    <?php if ((isset($nokri['scroll_to_top'])) && $nokri['scroll_to_top'] == '1') { ?>
        <a href="#" class="scrollup"><i class="fa fa-chevron-up"></i></a>
        <?php
    } echo nokri_authorization();
    wp_footer();
    /* Email job alerts */
    get_template_part('template-parts/job', 'alerts');
    ?>
    <div id="edit_meeting_container_modal"></div>
    <div id="zoom_edit_meeting_container"></div>
    <div id="user_chat_modal_popup"></div>
    <div id="chartcontainer_stats"></div>
    <div id="candidate_report_job"></div> 
    <?php
} else {
    // Elementor `footer` location
    if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) {
        get_template_part('template-parts/footer');
    }
    /* Search page layout */
    $search_page_layout = ( isset($nokri['search_page_layout']) && $nokri['search_page_layout'] != "" ) ? $nokri['search_page_layout'] : "";
    /* dashboard page */
    $dashboard_page = ( isset($nokri['sb_dashboard_page']) && $nokri['sb_dashboard_page'] != "" ) ? $nokri['sb_dashboard_page'] : "";
    /* Hidden Inputs */
    get_template_part('template-parts/hidden', 'inputs');
    /* Email verification and reset password */
    get_template_part('template-parts/verification', 'logic');
    ?>
    </div>
    <div id="toast-container" class="toast-top-right"  >
        <div class="toast toast-success" aria-live="polite"  id="progress_loader">
            <div class="toast-title"><?php echo esc_html__('Uploading', 'nokri') ?></div>
            <div class="toast-message" id="progress_counter"></div>
        </div>
    </div>
    <div id="popup-data"></div>
    <div id="app-data"></div>
    <div id="short-desc-data"></div>
    <div id="status_action_data"></div>
    <div id="job-alert-dataaaaa"></div> 
    <?php
    if (isset($nokri['banners_code_footer']) && $nokri['banners_code_footer'] != '') {
        echo ''.($nokri['banners_code_footer']);
    }
    ?> 
    <?php if ((isset($nokri['scroll_to_top'])) && $nokri['scroll_to_top'] == '1') { ?>
        <a href="#" class="scrollup"><i class="fa fa-chevron-up"></i></a>
        <?php
    } echo nokri_authorization();
    wp_footer();
    /* Email job alerts */
    get_template_part('template-parts/job', 'alerts');
    ?>
    <div id="edit_meeting_container_modal"></div>
    <div id="zoom_edit_meeting_container"></div>
    <div id="user_chat_modal_popup"></div>
<?php } ?>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/624a85762abe5b455fc44394/1fvphim2u';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<script type="text/javascript">
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
jQuery(document).ready(function() {
jQuery("#jobsearch").keyup(function(){
var search = jQuery(this).val();
// alert(search);
if(search != ""){
    $.ajax({
        url: ajaxurl,
        type: 'post',
        data: { 'action': 'search_job', 'search':search },
        dataType: 'json',
        success:function(response){
            var len = response.length;
            $("#searchResult").empty();
            $("#searchResult").show();
            for( var i = 0; i<len; i++){
                var id = response[i]['id'];
                var name = response[i]['name'];
                var link = response[i]['link'];
                // jQuery("#searchResult").hide();
                $("#searchResult").append("<li value='"+id+"'><a href='"+link+"'>"+name+"</a></li>");

            }

            // binding click event to li
            /*$("#searchResult li").bind("click",function(){
                setText(this);
            });*/


        }
    });
}

});
});
</script>
</body>
</html>