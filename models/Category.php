<?php 

class Category {

	public static function getCategoriesList() {
		$db = Db::getConnection();

		$categoryList = array();

		$query = 'SELECT id, name FROM category ' 
			   . 'ORDER BY sort_order ASC';			   
		$result = $db->query($query);

		$i = 0;
		while ($row = $result->fetch()) {
			$categoryList[$i]['id']   = $row['id'];
			$categoryList[$i]['name'] = $row['name'];	
			$i++;		 
		}

		return $categoryList;
	}
}