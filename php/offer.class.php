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
 
if(!isset($settings['base_url'])){
	die('Access denied!');
}

class offer {
	
	public function __get($value){
		if(isset($this->offer_data[$value])){
			return $this->offer_data[$value];
		}else{
			return '';
		}
	}
	
	public function loadOffers($limit=10,$type='all'){
		global $db, $smarty, $settings, $user;
		$limit = (int)$limit;
		$where_statement = ' true ';
		$select = '';
		if(isset($_GET['search'])){
			if(isset($_GET['id']) and $_GET['id']>0){
				$where_statement .= ' AND id = "'.filter($_GET['id']).'" ';
			}
			if(!empty($_GET['username'])){
				$where_statement .= ' AND user_id = (SELECT id FROM '._DB_PREFIX_.'users WHERE username="'.filter($_GET['username']).'" LIMIT 1) ';
			}
			if(isset($_GET['name']) and $_GET['name']!=''){
				$names = explode(' ', $_GET['name']);
				$where_statement .= ' AND ( ';
				for($i=0; $i < count($names); $i++){
					$where_statement .= ' name_url LIKE "%'.filter(name_url($names[$i])).'%"  ';
					if($i!=(count($names)-1)){$where_statement .= ' OR ';}
				}
				$where_statement .= ' ) ';
			}
			if(isset($_GET['keywords']) and $_GET['keywords']!=''){
				$keywords = explode(' ', $_GET['keywords']);
				$where_statement .= ' AND ( ';
				for($i=0; $i < count($keywords); $i++){
					$where_statement .= ' (name_url LIKE "%'.filter(name_url($keywords[$i])).'%" OR description LIKE "%'.filter(name_url($keywords[$i])).'%")  ';
					if($i!=(count($keywords)-1)){$where_statement .= ' OR ';}
				}
				$where_statement .= ' ) ';
			}
			if(isset($_GET['kind']) and is_array($_GET['kind'])){
				$where_statement .= ' AND (';
				$last = count($_GET['kind']);
				foreach($_GET['kind'] as $key => $value){
					$where_statement .= ' kind = (select id from '._DB_PREFIX_.'offers_kind WHERE name_url="'.filter($value).'" limit 1) ';
					if(($last-1)!=$key){$where_statement .= ' OR ';}
				}
				$where_statement .= ') ';
			}
			if(isset($_GET['type']) and is_array($_GET['type'])){
				$where_statement .= ' AND (';
				$last = count($_GET['type']);
				foreach($_GET['type'] as $key => $value){
					$where_statement .= ' type = (select id from '._DB_PREFIX_.'offers_type WHERE name_url="'.filter($value).'" limit 1) ';
					if(($last-1)!=$key){$where_statement .= ' OR ';}
				}
				$where_statement .= ') ';
			}
			if(isset($_GET['state']) and is_array($_GET['state'])){
				$where_statement .= ' AND (';
				$last = count($_GET['state']);
				foreach($_GET['state'] as $key => $value){
					$where_statement .= ' state = (SELECT id FROM '._DB_PREFIX_.'offers_state WHERE name_url="'.filter($value).'" LIMIT 1) ';
					if(($last-1)!=$key){$where_statement .= ' OR ';}
				}
				$where_statement .= ') ';
			}
			if(isset($_GET['user_id']) and $_GET['user_id']>0){
				$where_statement .= ' AND user_id="'.filter($_GET['user_id']).'" ';
			}
			if(isset($_GET['active'])){
				if($_GET['active']=='yes'){
					$where_statement .= ' AND active="1" ';
				}elseif($_GET['active']=='no'){
					$where_statement .= ' AND active="0" ';
				}
			}
			if(isset($_GET['promoted'])){
				if($_GET['promoted']=='yes'){
					$where_statement .= ' AND promoted="1" ';
				}elseif($_GET['promoted']=='no'){
					$where_statement .= ' AND (promoted="0" OR promoted is null) ';
				}
			}
			if(isset($_GET['distance']) and $_GET['distance']>0){
				$address = '';
				if(isset($_GET['address']) and $_GET['address']!=''){
					$address .= ' '.filter($_GET['address']);
				}
				if($address){
					$coordinates = get_coordinates($address);
					$select = ' , (6371 * acos( cos( radians('.$coordinates['lat'].')) * cos( radians(address_lat) ) * cos( radians(address_long) - radians('.$coordinates['long'].')) + sin(radians('.$coordinates['lat'].')) * sin(radians(address_lat)))) AS distance '; 
					$where_statement .= ' AND address_lat!=0 AND address_long!=0 HAVING distance < '.intval($_GET['distance']).' ';
				}
			}else{
				if(isset($_GET['address']) and $_GET['address']!=''){
					$where_statement .= ' AND address like "%'.filter($_GET['address']).'%"  ';
				}
			}
			if(isset($_GET['price_from']) and $_GET['price_from']>=0){
				$where_statement .= ' AND price>='.intval($_GET['price_from']).' ';
			}
			if(isset($_GET['price_to']) and $_GET['price_to']>=0){
				$where_statement .= ' AND price<='.intval($_GET['price_to']).' ';
			}
			if(isset($_GET['options']) and is_array($_GET['options'])){
				$where_statement .= ' AND (';
				$last = count($_GET['options']);
				$i = 0;
				foreach($_GET['options'] as $key => $value){
					if(is_array($value)){
						$where_statement .= ' ( ';
						if(isset($value['from']) || isset($value['to'])){
							if(isset($value['from']) and $value['from']>=0){
								$where_statement .= ' (SELECT count(1) FROM '._DB_PREFIX_.'offers_options_values, '._DB_PREFIX_.'offers_options WHERE '._DB_PREFIX_.'offers_options.id=offers_options_values.option_id AND name_url="'.filter($key).'" AND offer_id='._DB_PREFIX_.'offers.id AND CAST(value AS UNSIGNED) >='.intval($value['from']).' LIMIT 1) > 0 ';
								
							}
							if(isset($value['from']) and $value['from']>=0 and isset($value['to']) and $value['to']>=0){$where_statement .= ' AND ';}
							if(isset($value['to']) and $value['to']>=0){
								$where_statement .= ' (SELECT count(1) FROM '._DB_PREFIX_.'offers_options_values, '._DB_PREFIX_.'offers_options WHERE '._DB_PREFIX_.'offers_options.id=offers_options_values.option_id AND name_url="'.filter($key).'" AND offer_id='._DB_PREFIX_.'offers.id AND CAST(value AS UNSIGNED) <='.intval($value['to']).' LIMIT 1) > 0 ';
							}
							
						}else{
							$last2 = count($value);
							$j = 0;
							foreach($value as $key2 => $value2){
								$where_statement .= ' (SELECT count(1) FROM '._DB_PREFIX_.'offers_options_values, '._DB_PREFIX_.'offers_options WHERE '._DB_PREFIX_.'offers_options.id=offers_options_values.option_id AND name_url="'.filter($key).'" AND offer_id='._DB_PREFIX_.'offers.id AND value ="'.filter($value2).'" LIMIT 1) > 0 ';
								if($j!=$last2-1){$where_statement .= ' OR ';}
								$j++;
							}
						}
						$where_statement .= ' ) ';
					}else{
						$where_statement .= ' (SELECT count(1) FROM '._DB_PREFIX_.'offers_options_values, '._DB_PREFIX_.'offers_options WHERE '._DB_PREFIX_.'offers_options.id=offers_options_values.option_id AND name_url="'.filter($key).'" AND offer_id='._DB_PREFIX_.'offers.id AND value LIKE "%'.filter($value).'%" LIMIT 1) > 0 ';
					}
					if($i!=$last-1){$where_statement .= ' AND ';}
					$i++;
				}
				$where_statement .= ') ';
			}
		}
		$sort = ' rand() ';
		if(isset($_GET['sort'])){
			if($_GET['sort']=='random'){
				$sort = ' rand() ';
			}elseif($_GET['sort']=='newest'){
				$sort = ' id desc ';
			}elseif($_GET['sort']=='oldest'){
				$sort = ' id ';
			}elseif($_GET['sort']=='cheapest'){
				$sort = ' ISNULL(price), price, id DESC ';				
			}elseif($_GET['sort']=='most_expensive'){
				$sort = ' ISNULL(price), price DESC, id DESC ';
			}elseif($_GET['sort']=='a-z'){
				$sort = ' name, id DESC ';
			}elseif($_GET['sort']=='z-a'){
				$sort = ' name DESC, id DESC ';
			}elseif($select and $_GET['sort']=='nearest'){
				$sort = ' distance, id DESC ';
			}elseif($select and $_GET['sort']=='farthest'){
				$sort = ' distance DESC, id DESC ';
			}else{
				$sort = sort_by();
			}
		}
		if($type=='my_offers'){
			$where_statement .= ' and user_id='.$user->id.' ';
		}elseif($type=='all'){
			$where_statement .= ' and active=1 ';
		}
		if($type!='all_cms'){
			$sort = ' ifnull(promoted,0) DESC, '.$sort;
		}
		
		$sth = $db->prepare('SELECT * '.$select.', 
			(SELECT thumb FROM '._DB_PREFIX_.'photos WHERE offer_id='._DB_PREFIX_.'offers.id LIMIT 1) AS thumb 
			FROM '._DB_PREFIX_.'offers WHERE '.$where_statement.' ORDER BY '.$sort.' LIMIT :limit_from, :limit_to');
	
		$sth->bindValue(':limit_from', page_count('offers', $limit, $where_statement, ', active '.$select), PDO::PARAM_INT);
		$sth->bindValue(':limit_to', $limit, PDO::PARAM_INT);
		$sth->execute();
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$row['kind'] = $this->getKind($row['kind']);
			$row['state'] = $this->getState($row['state']);
			$row['type'] = $this->getTypeOffer($row['type']);
			if(isset($smarty) and ($row['address_lat']!=0 or $row['address_long']!=0)){$smarty->assign("offers_show_map", true);}
			if($type=='all_cms'){
				$sth2 = $db->prepare('SELECT username, email FROM '._DB_PREFIX_.'users WHERE id=:id LIMIT 1');
				$sth2->bindValue(':id', $row['user_id'], PDO::PARAM_INT);
				$sth2->execute();
				$row['user'] = $sth2->fetch(PDO::FETCH_ASSOC);
				$sth2->closeCursor();
				$row['view'] = $this->getViews($row['id']);
				
			}
			$offers[] = $row;
		}
		$sth->closeCursor();
		if(isset($offers)){
			$this->offer_data = $offers;
			if(isset($smarty)){$smarty->assign("offers", $offers);}
		}
	}
	
	public function addOffer($data){
		global $db, $user, $settings, $links;
		$code = md5(uniqid(rand(), true));
		
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'offers`(`user_id`, `name`, `name_url`, `active`, `code`, `date`) VALUES (:user_id,:name,:name_url,:active,:code,NOW())');
		$sth->bindValue(':user_id', $user->getId(), PDO::PARAM_INT);
		$sth->bindValue(':name', $data['name'], PDO::PARAM_STR);
		$sth->bindValue(':name_url', name_url($data['name']), PDO::PARAM_STR);
		$sth->bindValue(':active', $settings['automatically_activate_offers'], PDO::PARAM_INT);
		$sth->bindValue(':code', $code, PDO::PARAM_STR);
		$sth->execute();
		$id = $db->lastInsertId();
		$this->editOffer($id,$data);
		
		$offer_edit_link = $links['edit'].'/'.$id.','.name_url($data['name']).'?code='.$code;
		
		send_mail('offer_start',$data['email'],array('offer_edit_link'=>$offer_edit_link, 'offer_name'=>$data['name'], 'offer_url'=>$id.','.name_url($data['name'])));
		
		header("Location: ".$settings['base_url']."/".$id.",".name_url($data['name']));
		die('redirect');
	}
	
	public function editOffer($id,$data){
		global $db, $settings, $user;
		
		$name_url = name_url(strip_tags($data['name']));
		if(!empty($data['address'])){$data['address'] = trim(strip_tags($data['address']));}else{$data['address'] = null;}
		if(empty($data['kind'])){$data['kind'] = 0;}
		if(empty($data['state'])){$data['state'] = 0;}
		if(empty($data['type'])){$data['type'] = 0;}
		if(!empty($data['address_lat'])){$data['address_lat'] = strval($data['address_lat']);}else{$data['address_lat'] = null;}
		if(!empty($data['address_long'])){$data['address_long'] = strval($data['address_long']);}else{$data['address_long'] = null;}
		if(isset($data['price']) and is_numeric($data['price'])){$data['price'] = strval($data['price']);}else{$data['price'] = null;}
		
		$sth = $db->prepare('UPDATE '._DB_PREFIX_.'offers SET name=:name, name_url=:name_url, address=:address, url=:url, phone=:phone, phone_mobile=:phone_mobile, email=:email, kind=:kind, state=:state, type=:type, description=:description, youtube=:youtube, address_lat=:address_lat, address_long=:address_long, price=:price WHERE id=:id LIMIT 1');
		
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->bindValue(':name', trim(strip_tags($data['name'])), PDO::PARAM_STR);
		$sth->bindValue(':name_url', $name_url, PDO::PARAM_STR);
		$sth->bindValue(':address', $data['address'], PDO::PARAM_STR);
		$sth->bindValue(':url', web_address(trim(strip_tags($data['url']))), PDO::PARAM_STR);
		$sth->bindValue(':phone', trim(strip_tags($data['phone'])), PDO::PARAM_STR);
		$sth->bindValue(':phone_mobile', trim(strip_tags($data['phone_mobile'])), PDO::PARAM_STR);
		$sth->bindValue(':email', trim(strip_tags($data['email'])), PDO::PARAM_STR);
		$sth->bindValue(':kind', $data['kind'], PDO::PARAM_INT);
		$sth->bindValue(':state', $data['state'], PDO::PARAM_INT);
		$sth->bindValue(':type', $data['type'], PDO::PARAM_INT);
		$sth->bindValue(':description', nofollow(purify(trim($data['description']))), PDO::PARAM_STR);
		$sth->bindValue(':youtube', strip_tags($data['youtube']), PDO::PARAM_STR);
		$sth->bindValue(':address_lat', $data['address_lat'], PDO::PARAM_STR);
		$sth->bindValue(':address_long', $data['address_long'], PDO::PARAM_STR);
		$sth->bindValue(':price', $data['price'], PDO::PARAM_STR);
		$sth->execute();
		$sth->closeCursor();
		
		if($settings['photo_add'] and isset($data['photos']) and $data['photos']!=''){
			$where_statement = filter(' id!='.implode($data['photos'],' and id!= '));
		}else{
			$where_statement = ' true ';
		}
		$sth = $db->prepare('SELECT * from '._DB_PREFIX_.'photos WHERE offer_id=:offer_id and ('.$where_statement.')');
		$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
		$sth->execute();
		foreach($sth as $row){;
			unlink(realpath(dirname(__FILE__)).'/../upload/photos/'.$row['thumb']);
			unlink(realpath(dirname(__FILE__)).'/../upload/photos/'.$row['url']);
		}
		$sth->closeCursor();
		$sth = $db->prepare('DELETE from '._DB_PREFIX_.'photos WHERE offer_id=:offer_id and ('.$where_statement.')');
		$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
		$sth->execute();
		$sth->closeCursor();

		if($settings['photo_add'] and isset($data['photos']) and $data['photos']!=''){
			
			$sth = $db->prepare('SELECT * from '._DB_PREFIX_.'photos WHERE id=:id and user_id=:user_id LIMIT 1');
			foreach($data['photos'] as $key => $value){
				$sth->bindValue(':id', $value, PDO::PARAM_INT);
				$sth->bindValue(':user_id', $user->getId(), PDO::PARAM_INT);
				$sth->execute();
				$photo = $sth->fetch(PDO::FETCH_ASSOC);
				if($photo!=''){
					$sth2 = $db->prepare('UPDATE `'._DB_PREFIX_.'photos` SET offer_id=:offer_id WHERE id=:id');
					$sth2->bindValue(':offer_id', $id, PDO::PARAM_INT);
					$sth2->bindValue(':id', $photo['id'], PDO::PARAM_INT);
					$sth2->execute();
					$sth2->closeCursor();
				}
			}
			$sth->closeCursor();
		}
		
		$sth = $db->prepare('DELETE from '._DB_PREFIX_.'offers_options_values WHERE offer_id=:offer_id');
		$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
		$sth->execute();
		$sth->closeCursor();
		if(isset($data['options']) and is_array($data['options'])){
			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'offers_options_values`(`offer_id`, `option_id`, `value`) VALUES (:offer_id, :option_id, :value)');
			$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
			foreach($data['options'] as $key=>$value){
				if($value){
					$sth->bindValue(':option_id', $key, PDO::PARAM_INT);
					$sth->bindValue(':value', strip_tags($value), PDO::PARAM_STR);
					$sth->execute();
				}
			}
			$sth->closeCursor();
		}
	}
	
	public function loadOffer($id,$all_data=false){
		global $db, $user, $settings;
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'offers WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$offer = $sth->fetch(PDO::FETCH_ASSOC);
		if($offer!=''){
			$sth->closeCursor();
			$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'photos WHERE offer_id=:offer_id');
			$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
			$sth->execute();
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
				$offer['photos'][] = $row;
			}
			$sth->closeCursor();
			
			if(isset($offer['photos'])){$settings['logo_facebook'] = $settings['base_url'].'/upload/photos/'.$offer['photos'][0]['thumb'];}
			
			$sth = $db->prepare('SELECT name, id, value FROM '._DB_PREFIX_.'offers_options_values, '._DB_PREFIX_.'offers_options WHERE '._DB_PREFIX_.'offers_options_values.option_id = '._DB_PREFIX_.'offers_options.id and '._DB_PREFIX_.'offers_options_values.offer_id=:offer_id');
			$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
			$sth->execute();
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
				$offer['options'][$row['id']] = $row;
			}
			$sth->closeCursor();
			
			if($all_data){
				$offer['kind'] = $this->getKind($offer['kind']);
				$offer['state'] = $this->getState($offer['state']);
				$offer['type'] = $this->getTypeOffer($offer['type']);
				$offer['user'] = $this->getUser($offer['user_id']);
				
				$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'logs_offers`(`offer_id`, `user_id`, `ip`, `date`) VALUES (:offer_id,:user_id,:ip,NOW())');
				$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
				$sth->bindValue(':user_id', $user->getId(), PDO::PARAM_INT);
				$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
				$sth->execute();
				$sth->closeCursor();
				
				$offer['view'] = $this->getViews($id);
				
				if($offer['youtube']){$offer['youtube_embed'] = getYoutubeIdFromUrl($offer['youtube']);}
				
			}
			return $offer;			
		}
	}
	
	public function checkActiveOffer($id){
		global $db, $user;
		$sth = $db->prepare('SELECT 1 from '._DB_PREFIX_.'offers WHERE (active=1 or user_id=:user_id) and id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->bindValue(':user_id', $user->id, PDO::PARAM_INT);
		$sth->execute();
		if($sth->fetchColumn()){
			return true;
		}
		return false;
	}
	
	public function checkPermissions($id,$code=''){
		global $user, $db;
		if($user->logged_in){
			if($user->moderator){return true;}
			$user_id = $user->id;
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'offers WHERE id=:id AND user_id=:user_id LIMIT 1');
			$sth->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		}else{
			if(empty($code)){
				return false;
			}else{
				$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'offers WHERE id=:id AND code=:code LIMIT 1');
				$sth->bindValue(':code', $code, PDO::PARAM_STR);
			}
		}
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		if($sth->fetchColumn()){return true;}
		return false;
	}
	
	public function newSessionCode(){
		global $db, $smarty, $user;
		$session_code = md5(uniqid(rand(), true));		
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'session_offer`(`user_id`, `code`, `ip`, `date`) VALUES (:user_id,:code,:ip,NOW())');
		$sth->bindValue(':user_id', $user->getId(), PDO::PARAM_INT);
		$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
		$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
		$sth->execute();
		if(isset($smarty)){$smarty->assign("session_code", $session_code);}
	}
	
	public function checkSessionCode($session_code){
		global $db, $settings;
		if($settings['check_ip_user']){
			$sth = $db->prepare('SELECT 1 from '._DB_PREFIX_.'session_offer WHERE code=:code AND ip=:ip LIMIT 1');
			$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
		}else{
			$sth = $db->prepare('SELECT 1 from '._DB_PREFIX_.'session_offer WHERE code=:code LIMIT 1');
		}
		$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
		$sth->execute();
		if($sth->fetchColumn()){
			$this->removeSessionCode($session_code);
			return true;
		}
		return false;
	}
	
	public function removeSessionCode($session_code){
		global $db;
		$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'session_offer` WHERE code=:code');
		$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
		$sth->execute();
	}
	
	public function getKind($id){
		global $db;
		$kind = array('name'=>'','name_url'=>'');
		$sth = $db->prepare('SELECT name, name_url from '._DB_PREFIX_.'offers_kind WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$kind = $sth->fetch(PDO::FETCH_ASSOC);
		return $kind;
	}
	
	public function getTypeOffer($id){
		global $db;
		$type = array('name'=>'','name_url'=>'');
		$sth = $db->prepare('SELECT name, name_url from '._DB_PREFIX_.'offers_type WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$type = $sth->fetch(PDO::FETCH_ASSOC);
		return $type;
	}
	
	public function getState($id){
		global $db;
		$state = array('name'=>'','name_url'=>'');
		$sth = $db->prepare('SELECT name, name_url from '._DB_PREFIX_.'offers_state WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$state = $sth->fetch(PDO::FETCH_ASSOC);
		return $state;
	}
	
	public function getUser($id){
		global $db;
		$sth = $db->prepare('SELECT id, username from '._DB_PREFIX_.'users WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$user = $sth->fetch(PDO::FETCH_ASSOC);
		return $user;
	}
	
	public function getViews($id){
		global $db;
		$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'logs_offers WHERE offer_id=:offer_id');
		$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
		$sth->execute();
		$rows = $sth->fetchAll();
		$all = count($rows);
		
		$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'logs_offers WHERE offer_id=:offer_id GROUP BY ip');
		$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
		$sth->execute();
		$rows = $sth->fetchAll();
		$unique = count($rows);
		
		return array('all'=>$all, 'unique'=>$unique);
	}
	
	public function deactivate($id){
		global $db;
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'offers` SET active=0 WHERE id=:id limit 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}
	
	public function activate($id){
		global $db;
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'offers` SET active=1 WHERE id=:id limit 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}
	
	public function disablePromote($id){
		global $db;
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'offers` SET promoted=0 WHERE id=:id limit 1');
		$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
		$sth->execute();
	}

	public function enablePromote($id,$date=false){
		global $db, $settings;
		$offer = $this->loadOffer($id);	
		if($date){
			$promoted_date_end = $date;
		}elseif($offer['promoted'] and $offer['promoted_date_end'] > date("Y-m-d")){
			$promoted_date_end = date('Y-m-d', strtotime($offer['promoted_date_end']. ' + '.$settings['promote_days'].' days'));
		}else{
			$promoted_date_end = date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$settings['promote_days'].' days'));
		}
		$sth = $db->prepare('UPDATE '._DB_PREFIX_.'offers SET promoted=1, promoted_date_end=:promoted_date_end WHERE id=:id LIMIT 1');
		$sth->bindValue(':promoted_date_end', $promoted_date_end, PDO::PARAM_STR);
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}
	
	public function loadSimilarOffers($id, $limit=10){
		global $db;
		$this_offer = $this->loadOffer($id);
		
		$limit = (int)$limit;
		$where_statement = ' (false ';
		
		if($this_offer['kind']){
			$where_statement .= ' OR kind = '.$this_offer['kind'];
		}
		if($this_offer['type']){
			$where_statement .= ' OR type = '.$this_offer['type'];
		}
		if($this_offer['state']){
			$where_statement .= ' OR state = '.$this_offer['state'];
		}

		$where_statement .= ' ) ';
		
		$sth = $db->prepare('SELECT *, (SELECT thumb FROM '._DB_PREFIX_.'photos WHERE offer_id='._DB_PREFIX_.'offers.id LIMIT 1) AS thumb 
			FROM '._DB_PREFIX_.'offers WHERE id!=:id AND active=1 AND '.$where_statement.' ORDER BY rand() LIMIT :limit_to');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->bindValue(':limit_to', $limit, PDO::PARAM_INT);
		$sth->execute();
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$row['kind'] = $this->getKind($row['kind']);
			$row['state'] = $this->getState($row['state']);
			$similar_offers[] = $row;
		}
		$sth->closeCursor();
		if(isset($similar_offers)){return($similar_offers);}
	}
	
	public function remove($id){
		global $db;
		if($id){
			$sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'logs_offers WHERE offer_id=:offer_id');
			$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
			$sth->execute();
			$sth->closeCursor();
			$sth = $db->prepare('SELECT * from '._DB_PREFIX_.'photos WHERE offer_id=:offer_id');
			$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
			$sth->execute();
			foreach($sth as $row){
				unlink(realpath(dirname(__FILE__)).'/../upload/photos/'.$row['thumb']);
				unlink(realpath(dirname(__FILE__)).'/../upload/photos/'.$row['url']);
			}
			$sth->closeCursor();
			$sth = $db->prepare('DELETE from '._DB_PREFIX_.'photos WHERE offer_id=:offer_id');
			$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
			$sth->execute();
			$sth->closeCursor();
			$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'offers_options_values` WHERE offer_id=:offer_id');
			$sth->bindValue(':offer_id', $id, PDO::PARAM_INT);
			$sth->execute();
			$sth->closeCursor();
			$sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'offers WHERE id=:id LIMIT 1');
			$sth->bindValue(':id', $id, PDO::PARAM_INT);
			$sth->execute();
			$sth->closeCursor();
		}
	}
}
