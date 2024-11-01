<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$out = $files = '';
$search_subdir = isset($_POST['search_subdir'])?$_POST['search_subdir']:0;

if(isset($_POST['attach_action']) and check_admin_referer('mgh_attach_config_action', 'mgh_attach_config_nonce_field') and current_user_can( 'administrator' )){
    
    if(is_numeric($_POST['search_subdir'])){
        $out  = mgh_attach_addfile_todb('',$_POST['search_subdir']);
        if(!empty($out)){
        echo '<div class="error">'.__('عملیات با موفقیت انجام شد','attach_media').'</div>';
            foreach($out as $file)
            $files.= "$file<br />\n";
        }else
        echo '<div class="error">'.__('فایلی برای افزودن یافت نشد','attach_media').'</div>';
    }
}
?>
<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">
<form method="post" enctype="multipart/form-data">

    <div id="post-body-content" style="position: relative;">
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
        	<td colspan="2" dir="ltr"><?php echo $files;?></td>
        </tr>
        </table>
    </div>

    <div id="postbox-container-1" class="postbox-container">
        <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
                <div class="postbox ">
                    <div class="handlediv" title="<?php _e('برای جانشینی کلیک کنید','attach_media')?>">
                    <br/>
                    </div>
                    <h3 class="hndle ui-sortable-handle"><?php _e('اجرای عملیات','attach_media')?></h3>
                    <div class="inside">
                     <?php wp_nonce_field( 'mgh_attach_config_action', 'mgh_attach_config_nonce_field' ); ?>
                    <input type="submit" name="attach_action" value="<?php _e('اجرا','attach_media')?>" class="button-primary" />
                    </div>
                </div>
        </div>
    </div>

</form>
</div>
</div>