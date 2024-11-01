<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$path          = '';
$search_subdir = isset($_POST['search_subdir'])?$_POST['search_subdir']:0;

if(isset($_POST['attach_action']) and check_admin_referer('mgh_attach_media_action', 'mgh_attach_media_nonce_field') and current_user_can( 'administrator' )){
    $path = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR,rtrim($_POST['attach_path'],'/'));
    if(is_dir($path) and file_exists($path) and is_numeric($_POST['search_subdir'])){
        if(mgh_attach_copyto($path,$_POST['search_subdir']))
            echo '<div class="error">'.__('انتقال با موفقیت انجام شد.','attach_media').'</div>';
        else
            echo '<div class="error">'.__('خطا','attach_media').'!!<br />'.__('در پوشه مدیا','attach_media').' '.basename($path).' '.__('وجود دارد یا آدرس انتخاب شده دارای فایل نمیباشد','attach_media').'.</div>';        
    }
}
?>
<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">
<form method="post" enctype="multipart/form-data">

<div id="post-body-content" style="position: relative;">
<style type="text/css">
.dir_manager li{
    border:1px solid silver;
    max-width:100%;
    overflow: hidden;
}
.dir_manager .m_dir,
.dir_manager .m_file{
    background: #FFCC99;
}
.dir_manager li:not(.m_dir) a{
    display:block;
    padding:5px;
}

.dir_manager .m_dir{
        padding:5px;
}
</style>
<table class="widefat">
<thead>
<tr>
	<th colspan="2"><?php _e('مدیریت افزونه','attach_media')?></th>
</tr>
</thead>
<tr>
	<td><?php _e('جستجو در زیر پوشه ها','attach_media')?></td>
	<td>
    <input type="radio" name="search_subdir" value="1" id="search_subdir_yes" <?php checked(1,$search_subdir) ?>/> <label for="search_subdir_yes"><?php _e('بلی','attach_media')?></label><br />
    <input type="radio" name="search_subdir" value="0" id="search_subdir_no" <?php checked(0,$search_subdir) ?>/> <label for="search_subdir_no"><?php _e('خیر','attach_media')?></label>
    </td>
</tr>
<tr>
	<td><?php _e('آدرس پوشه','attach_media')?></td>
	<td class="dir_manager"><?php echo mgh_attach_dir_manager($path) ?></td>
</tr>
</table>

<script type="text/javascript">
jQuery('body').on('click','.m_file',function(){

    jQuery( ".f_mgr" ).slideToggle("slow");
});

jQuery('body').on('click','.d_mgr',function(){
    jQuery.post(ajaxurl,{action:'wp_attach_getdirlist',path:jQuery(this).data('path')},function(data){
       jQuery('.dir_manager').html(data); 
    });
});
</script>
</div>

<div id="postbox-container-1" class="postbox-container">
    <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
            <div class="postbox ">
                <div class="handlediv" title="<?php _e('برای جانشینی کلیک کنید','attach_media')?>">
                <br/>
                </div>
                <h3 class="hndle ui-sortable-handle"><?php _e('اجرای عملیات','attach_media')?></h3>
                <div class="inside">
                 <?php wp_nonce_field( 'mgh_attach_media_action', 'mgh_attach_media_nonce_field' ); ?>
                <input type="submit" name="attach_action" value="<?php _e('اجرا','attach_media')?>" class="button-primary" />
                </div>
            </div>
    </div>
</div>

</form>
</div>
</div>