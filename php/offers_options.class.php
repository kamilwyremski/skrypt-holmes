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

class offers_options {
	
	public function __construct () {
		$this->kinds = array('text'=>lang('Text field'),'number'=>lang('Numeric field'),'select'=>lang('Select field'));
	}
	
	public function getKinds(){
		global $smarty;
		if(isset($smarty)){$smarty->assign("offers_options_kinds", $this->kinds);}
		return $this->kinds;
	}
	
	public function getOptions($getType = false){
		global $smarty, $db;
		$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'offers_options ORDER BY position');
		foreach($sth as $row){
			$row['kindName'] = $this->getKindName($row['kind']);
			if($row['kind']=='select'){
				$row['choices'] = $this->getSelectChoices($row['select_choices']);
			}
			if($getType and !$row['type_all']){
				$row['types'] = $this->getTypes($row['id']);
			}
			$offers_options[$row['id']] = $row;
		}
		$sth->closeCursor();
		if(isset($smarty) and isset($offers_options)){$smarty->assign("offers_options", $offers_options);}
	}
	
	public function getOption($id){
		global $smarty, $db;
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'offers_options WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$offers_option = $sth->fetch(PDO::FETCH_ASSOC);
		if($offers_option){
			$offers_option['types'] = $this->getTypes($offers_option['id']);
			$smarty -> assign('offers_option',$offers_option);
		}
	}
	
	public function getTypes($id){
		global $db;
		$types = array();
		$sth = $db->prepare('SELECT option_type FROM '._DB_PREFIX_.'offers_options_type WHERE option_id=:option_id');
		$sth->bindValue(':option_id', $id, PDO::PARAM_INT);
		$sth->execute();
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$types[] = $row['option_type'];
		}
		$sth->closeCursor();
		return $types;
	}
	
	public function getSelectChoices($choices){
		return array_filter(array_map('trim', explode(',',$choices)));
	}
	
	public function getKindName($name){
		if(isset($this->kinds[$name])){
			return $this->kinds[$name];
		}
		return '';
	}
	
	public function add($data){
		global $db;
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'offers_options`(`position`) VALUES (:position)');
		$sth->bindValue(':position', getPosition('offers_options'), PDO::PARAM_INT);		
		$sth->execute();
		$this->edit($db->lastInsertId(),$data);
	}
	
	public function edit($id,$data){
		global $db;
		if($id>0 and $this->checkKind($data['kind'])){
	
			if(!empty($data['select_choices'])){$select_choices = $data['select_choices'];}else{$select_choices = '';}
			
			$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'offers_options` SET name=:name, name_url=:name_url, kind=:kind, required=:required, type_all=:type_all, select_choices=:select_choices WHERE id=:id LIMIT 1');
			$sth->bindValue(':name', strip_tags($data['name']), PDO::PARAM_STR);
			$sth->bindValue(':name_url', name_url(strip_tags($data['name'])), PDO::PARAM_STR);
			$sth->bindValue(':kind', $data['kind'], PDO::PARAM_STR);
			$sth->bindValue(':required', isset($data['required']), PDO::PARAM_INT);
			$sth->bindValue(':type_all', isset($data['type_all']), PDO::PARAM_INT);
			$sth->bindValue(':select_choices', $select_choices, PDO::PARAM_STR);
			$sth->bindValue(':id', $id, PDO::PARAM_INT);				
			$sth->execute();
			
			$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'offers_options_type` WHERE option_id=:option_id');
			$sth->bindValue(':option_id', $id, PDO::PARAM_INT);		
			$sth->execute();
			
			if(!isset($data['type_all']) and isset($data['types']) and is_array($data['types'])){
				$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'offers_options_type`(`option_id`, `option_type`) VALUES (:option_id,:option_type)');
				$sth->bindValue(':option_id', $id, PDO::PARAM_STR);
				foreach($data['types'] as $key => $item){
					$sth->bindValue(':option_type', $item, PDO::PARAM_STR);			
					$sth->execute();
				}
			}
		}
	}
	
	public function checkKind($name){
		if(isset($this->kinds[$name])){
			return true;
		}
		return false;
	}

	public function remove($id){
		global $db;
		$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'offers_options` WHERE id=:id limit 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'offers_options_values` WHERE option_id=:option_id');
		$sth->bindValue(':option_id', $id, PDO::PARAM_INT);
		$sth->execute();
		$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'offers_options_type` WHERE option_id=:option_id');
		$sth->bindValue(':option_id', $id, PDO::PARAM_INT);
		$sth->execute();
	}
}

?>