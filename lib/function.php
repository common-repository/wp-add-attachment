<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// create file list view
	function mgh_attach_dir_manager($path=''){

	   $path = $fpath =  empty($path)?str_replace('/',DIRECTORY_SEPARATOR,$_SERVER['DOCUMENT_ROOT']):str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR,rtrim($path,DIRECTORY_SEPARATOR));
	   $list = glob($path.DIRECTORY_SEPARATOR.'*');
       $path = explode(DIRECTORY_SEPARATOR,$path);
       $dir  = $fle = '';
       if(!empty($list)){
           foreach($list as $file){
            if(is_dir($file))
            $dir .= "<li><a href='javascript::void(0)' class='d_mgr' data-path='$file'>".basename($file).'</a></li>';
            else
            $fle .= "<li class='f_mgr' style='display:none;'><a href='javascript::void(0)' data-path='$file'>".basename($file).'</a></li>';        
           }        
       }
        $mpath = $jpath = '';
        foreach($path as $mdir){
            $jpath.= $mdir.DIRECTORY_SEPARATOR;
            $mpath.="<a href='javascript::void(0)' class='d_mgr' data-path='$jpath'>$mdir</a>/";
        }
       return "<ul dir='ltr' class='dir_manager'>
       <li class='m_dir'>$mpath</li>
       ".$dir.'
       <li class="m_file"><a href="javascript::void(0)">لیست فایلها</a></li>
       '.$fle."
       </ul>
       <input type='hidden' name='attach_path' value='$fpath' />";
	}
    //return list all file and folder in path
    function mgh_attach_dir_list($path,$sub=true){

        $ret  = glob($path.DIRECTORY_SEPARATOR.'*.{*}',GLOB_BRACE);
        if(!$sub)
        return $ret;
        $dirs = glob($path.DIRECTORY_SEPARATOR.'*',GLOB_ONLYDIR);
        if(!empty($dirs)){
            foreach($dirs as $dir){
                $ret = array_merge($ret,mgh_attach_dir_list($dir,$sub));
            }    
        }
        return $ret;
    }
    //add list files to wordpress media attachment
    function mgh_attach_addfile_todb($path='',$sub){

        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attachs  = $wpdb->get_col("select replace(replace(guid,'".site_url('/')."','".get_home_path()."'),'/','".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR."') from {$wpdb->prefix}posts where post_type='attachment'");
        $dir_path = wp_upload_dir();
        $base_path= str_replace(array('/','\\'),DIRECTORY_SEPARATOR,$dir_path['basedir']);
        $path     = empty($path)?$base_path:$path;
        $files    = mgh_attach_dir_list($path,$sub);
        $out      = array();
        if(!empty($files)){
            $base_url  = $dir_path['baseurl'];

            foreach($files as $file){
                if(basename($file)=='.htaccess' or basename($file)=='index.html')
                continue;
                $filetype   = wp_check_filetype( basename( $file ), null );
                if(substr($filetype['type'],0,5)=='image' and preg_match('/\-\d+x\d+\.[a-zA-Z0-9]{3,4}$/i',$file,$match))
                continue;

                if(!in_array($file,$attachs)){
                    
                    
                    $attachment = array(
                    	'guid'           => str_replace(array($base_path,DIRECTORY_SEPARATOR),array($base_url,'/'),$file),
                    	'post_mime_type' => $filetype['type'],
                    	'post_title'     => pathinfo( $file,PATHINFO_FILENAME),
                    	'post_content'   => '',
                    	'post_status'    => 'inherit');
                    // Insert the attachment.
                    $attach_id   = wp_insert_attachment( $attachment, $file, 0 );
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
                    wp_update_attachment_metadata( $attach_id, $attach_data );
                    $out[]       = $file;
                }
            }
        }
        return $out;
    }
    //copy files to wordpress upload dir
    function mgh_attach_copyto($path,$sub){

        $list = mgh_attach_dir_list($path,$sub);
        if(count($list)>0){
            ksort($list);
            $dir_path = wp_upload_dir()['basedir'];
            $dir_path = str_replace((DIRECTORY_SEPARATOR==='/'?'\\':'/'),DIRECTORY_SEPARATOR,$dir_path);
            $dir_path.= DIRECTORY_SEPARATOR.basename($path);
            if(file_exists($dir_path))
            return false;
            else
            mkdir($dir_path);
            
            foreach($list as $itm){
                $file = str_replace($path,$dir_path,$itm);

                if(is_dir($itm))
                mkdir($file);
                else
                copy($itm,$file);
            }
            return true;
        }else
        return false;
    }
?>