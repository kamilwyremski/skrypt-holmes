<?php
/************************************************************************
 * The script of real estate HOLMES
 * Copyright (c) 2017 - 2019 Kamil Wyremski
 * http://wyremski.pl
 * kamil.wyremski@gmail.com
 * 
 * All right reserved
 * 
 * *********************************************************************
 * THIS SOFTWARE IS LICENSED - YOU CAN MODIFY THESE FILES
 * BUT YOU CAN NOT REMOVE OF ORIGINAL COMMENTS!
 * ACCORDING TO THE LICENSE YOU CAN USE THE SCRIPT ON ONE DOMAIN. DETECTION
 * COPY SCRIPT WILL RESULT IN A HIGH FINANSIAL PENALTY AND WITHDRAWAL
 * LICENSE THE SCRIPT
 * *********************************************************************/
 
session_start(); 

require_once('../config/config.php');
include('user.class.php');

if(!($settings['add_offers_not_logged'] or $user->logged_in)){
	die('Access denied!');
}

if($settings['photo_add'] and isset($_FILES["file"]["type"])){

	$path_parts = pathinfo($_FILES['file']['name']);
	$path_parts['extension'] = strtolower($path_parts['extension']);
	
	$url = substr(name_url($path_parts['filename']), 0, 200).'.'.$path_parts['extension'];
	if(file_exists('../upload/photos/'.$url)) {  
		$url = substr(name_url($path_parts['filename']), 0, 200).'_'.time().'.'.$path_parts['extension'];
	}
	
	if(!in_array($path_parts['extension'] , array('jpg','jpeg', 'gif', 'png'))){
		echo json_encode(array('status'=>0, 'info'=>lang('Invalid file type')));
	}elseif($settings['photo_max_size'] and $_FILES["file"]["size"] > $settings['photo_max_size']*1024){
		echo json_encode(array('status'=>0, 'info'=>lang('The file size is too large')));
	}elseif($settings['photo_max'] and $_POST['count_photo']>=$settings['photo_max']){
		echo json_encode(array('status'=>0, 'info'=>lang('Photo limit exceeded').' ('.$settings['photo_max'].')'));
	}else{
		
		if($path_parts['extension']=="jpg" || $path_parts['extension']=="jpeg"){
			$src = imagecreatefromjpeg($_FILES['file']['tmp_name']);
		}else if($path_parts['extension']=="png"){
			$src = imagecreatefrompng($_FILES['file']['tmp_name']);
		}else{
			$src = imagecreatefromgif($_FILES['file']['tmp_name']);
		}
		
		imagesavealpha($src, true);
		$color = imagecolorallocatealpha($src, 0, 0, 0, 127);
		imagefill($src, 0, 0, $color);
		
		if($settings['watermark_add'] and $settings['watermark']!=''){
			$settings['watermark'] = get_full_url($settings['watermark']);
			$stamp_parts = pathinfo($settings['watermark']);
			$stamp_parts['extension'] = strtolower($stamp_parts['extension']);
			
			if(in_array($stamp_parts['extension'] , array('jpg','jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG'))){
				
				if($stamp_parts['extension']=="jpg" || $stamp_parts['extension']=="jpeg"){
					$stamp = imagecreatefromjpeg($settings['watermark']);
				}else if($stamp_parts['extension']=="png"){
					$stamp = imagecreatefrompng($settings['watermark']);
				}else{
					$stamp = imagecreatefromgif($settings['watermark']);
				}
				imagecopy($src,$stamp,imagesx($src)-imagesx($stamp) - 5, imagesy($src) - imagesy($stamp) - 5, 0, 0, imagesx($stamp), imagesy($stamp));
			}
			if($path_parts['extension']=="jpg" || $path_parts['extension']=="jpeg"){
				imagejpeg($src,'../upload/photos/'.$url,$settings['photo_quality']);
			}else if($path_parts['extension']=="png"){
				imagepng($src,'../upload/photos/'.$url);
			}else{
				imagegif($src,'../upload/photos/'.$url);
			}
		}else{
			move_uploaded_file($_FILES['file']['tmp_name'], '../upload/photos/'.$url);
		}

		list($width,$height)=getimagesize('../upload/photos/'.$url);
		
		if($settings['photo_max_height'] or $settings['photo_max_width']){
			$newheight = $height;
			$newwidth = $width;
			if($settings['photo_max_height'] and $height>=$settings['photo_max_height']){
				$newheight=$settings['photo_max_height'];
			}else{
				$newheight=$height;
			}
			$newwidth=round($newheight/$height*$width);	
			if($settings['photo_max_width'] and $newwidth>=$settings['photo_max_width']){
				$newwidth=$settings['photo_max_width'];
			}
			$newheight=round($newwidth/$width*$height);	
			$new_image=imagecreatetruecolor($newwidth,$newheight);
			imagesavealpha($new_image, true);
			$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
			imagefill($new_image, 0, 0, $color);
			imagecopyresampled($new_image,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
			if($extension=="jpg" || $extension=="jpeg" || $extension=="JPG" || $extension=="JPEG" ){
				imagejpeg($new_image,'../upload/photos/'.$url,$settings['photo_quality']);
			}else if($extension=="png" || $extension=="PNG" ){
				imagepng($new_image,'../upload/photos/'.$url);
			}else{
				imagegif($new_image,'../upload/photos/'.$url);
			}
			imagedestroy($new_image);
		}
		
		if($height>=300){
			$newheight=300;
		}else{
			$newheight=$height;
		}
		$newwidth=round($newheight/$height*$width);			
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagesavealpha($tmp, true);
		$color = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
		imagefill($tmp, 0, 0, $color);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
		$thumb_temp = explode('.', $url)[0];
		$thumb = $thumb_temp.'_thumb.'.$path_parts['extension'];

		$i = 0;
		while(file_exists('../upload/photos/'.$thumb)) {  
			$thumb = $thumb_temp.'_'.$i.'_thumb.'.$path_parts['extension'];
			$i++;
		}

		if($path_parts['extension']=="jpg" || $path_parts['extension']=="jpeg" || $path_parts['extension']=="JPG" || $path_parts['extension']=="JPEG" ){
			imagejpeg($tmp,'../upload/photos/'.$thumb,$settings['photo_quality']);
		}elseif($path_parts['extension']=="png" || $path_parts['extension']=="PNG" ){
			imagepng($tmp,'../upload/photos/'.$thumb);
		}else{
			imagegif($tmp,'../upload/photos/'.$thumb);
		}
		imagedestroy($src);
		
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'photos`(`user_id`, `thumb`, `url`, `date`) VALUES (:user_id,:thumb,:url,NOW())');
		$sth->bindValue(':user_id', $user->getId(), PDO::PARAM_INT);
		$sth->bindValue(':thumb', $thumb, PDO::PARAM_STR);
		$sth->bindValue(':url', $url, PDO::PARAM_STR);
		$sth->execute();
		$id = $db->lastInsertId();
		
		echo json_encode(array('status'=>1, 'id'=>$id, 'url'=>$url, 'thumb'=>$thumb, 'remove_title'=>lang('Remove photo:').' '.$url, 'remove'=>lang('Remove')));
	}
}else{
	echo json_encode(array('status'=>0, 'info'=>lang('Select file')));
}
?>