<?php 
require_once ROOT . '/components/Db.php';

class Product {

	const SHOW_BY_DEFAULT = 6;

	public static function getLatestProducts($count = self::SHOW_BY_DEFAULT) {
		$count = intval($count);

		$db = Db::getConnection();

		$productsList = array();

		$query = 'SELECT id, name, price, image, is_new FROM product '
			   . 'WHERE status = "1" '
			   . 'ORDER BY id DESC '
			   . 'LIMIT ' . $count;

		$result = $db->query($query);

		$i = 0;
		while ($row = $result->fetch()) {
			$productsList[$i]['id']	   = $row['id'];
			$productsList[$i]['name']   = $row['name'];
			$productsList[$i]['image']  = $row['image'];
			$productsList[$i]['price']  = $row['price'];
			$productsList[$i]['is_new'] = $row['is_new'];
			$i++;
		}

		return $productsList;
	}

	public static function getRecommendedProducts() {
		$db = Db::getConnection();

		$productsList = array();

		$query = 'SELECT id, name, price, image, is_new FROM product '
			   . 'WHERE status = "1" AND '
			   . 'is_recommended = "1" '
			   . 'ORDER BY id DESC ';

		$result = $db->query($query);

		$i = 0;
		while ($row = $result->fetch()) {
			$productsList[$i]['id']	   = $row['id'];
			$productsList[$i]['name']   = $row['name'];
			$productsList[$i]['image']  = $row['image'];
			$productsList[$i]['price']  = $row['price'];
			$productsList[$i]['is_new'] = $row['is_new'];
			$i++;
		}

		return $productsList;
	}

	public static function getProductsListByCategory($categoryId = false, $page = 1) { 
		if ($categoryId) {

			$page = intval($page);
			$offset = --$page * self::SHOW_BY_DEFAULT;

			$db = Db::getConnection();

			$products = array();

			$query = "SELECT id, name, price, image, is_new FROM product "
			   	   . "WHERE status = '1' "
			   	   . "AND category_id = '$categoryId' "
			       . "ORDER BY id DESC "
			       . "LIMIT " . self::SHOW_BY_DEFAULT
			       . " OFFSET " . $offset;

			$result = $db->query($query);

			$i = 0;
			while ($row = $result->fetch()) {
				$products[$i]['id']	    = $row['id'];
				$products[$i]['name']   = $row['name'];
				$products[$i]['image']  = $row['image'];
				$products[$i]['price']  = $row['price'];
				$products[$i]['is_new'] = $row['is_new'];
				$i++;
			}	
			return $products;	
		}
	}	

	public static function getProductById($id) {
		$id = intval($id);

		if ($id) {
			$db = Db::getConnection();

			$query = 'SELECT * FROM product WHERE id=' . $id;

			$result = $db->query($query);
			$result->setFetchMode(PDO::FETCH_ASSOC);

			return $result->fetch();
		} 
	}

	public static function getTotalProductsInCategory($categoryId) {

		$db = Db::getConnection();

		$query = "SELECT count(id) AS count FROM product "
			   . "WHERE status='1' AND category_id ='".$categoryId."'";

		$result = $db->query($query); 
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();

		return $row['count'];
	}

	public static function getProdustsByIds($idsArray) {
        $products = array();
        
        $db = Db::getConnection();
        
        $idsString = implode(',', $idsArray);

        $query = "SELECT * FROM product WHERE status='1' AND id IN ($idsString)";

        $result = $db->query($query);        
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        $i = 0;
        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['code'] = $row['code'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $i++;
        }

        return $products;
    }

    // Возвращает список товаров
    public static function getProductsList() {
        $db = Db::getConnection();
        
        $query = 'SELECT id, name, price, code FROM product ORDER BY id ASC';

        $result = $db->query($query);

        $productsList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['code'] = $row['code'];
            $productsList[$i]['price'] = $row['price'];
            $i++;
        }

        return $productsList;
    }
    
    public static function deleteProductById($id) {
        $db = Db::getConnection();
        
        $query = 'DELETE FROM product WHERE id = :id';
        
        $result = $db->prepare($query);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        return $result->execute();
    }
    
    public static function updateProductById($id, $options) {
        $db = Db::getConnection();
        
        $query = "UPDATE product
            SET 
                name = :name, 
                code = :code, 
                price = :price, 
                category_id = :category_id, 
                brand = :brand, 
                availability = :availability, 
                description = :description, 
                is_new = :is_new, 
                is_recommended = :is_recommended, 
                status = :status
            WHERE id = :id";
        
        $result = $db->prepare($query);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);

        return $result->execute();
    }
    
    public static function createProduct($options) {
        $db = Db::getConnection();
        
        $query = 'INSERT INTO product '
                . '(name, code, price, category_id, brand, availability,'
                . 'description, is_new, is_recommended, status)'
                . 'VALUES '
                . '(:name, :code, :price, :category_id, :brand, :availability,'
                . ':description, :is_new, :is_recommended, :status)';
        
        $result = $db->prepare($query);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
        if ($result->execute()) {
            // Если запрос выполенен успешно, возвращение id добавленной записи
            return $db->lastInsertId();
        }
        // Иначе вернуть 0
        return 0;
    }
    
    // Возвращает текстое пояснение наличия товара:
    // 0 - Под заказ, 1 - В наличии
    public static function getAvailabilityText($availability) {
        switch ($availability) {
            case '1':
                return 'В наличии';
                break;
            case '0':
                return 'Под заказ';
                break;
        }
    }
    
    // Возвращает путь к изображению
    public static function getImage($id) {
        // Название изображения-пустышки
        $noImage = 'no-image.jpg';

        // Путь к папке с товарами
        $path = '/eshopper/upload/images/products/';

        // Путь к изображению товара
        $pathToProductImage = $path . $id . '.jpg';
        if (file_exists($_SERVER['DOCUMENT_ROOT'].$pathToProductImage)) {
            // Если изображение для товара существует
            // Вернуть путь изображения товара
            return $pathToProductImage; 
        }
        // Вернуть путь изображения-пустышки
        return $path . $noImage;
    }
}