<?php 

class Cart {

	public static function addProduct($id) {
		$id = intval($id);

		$productsInCart = array();

		if (isset($_SESSION['products'])) {
			$productsInCart = $_SESSION['products'];
		}

		// Если товар уже есть в корзине - увеличить количество
		if (array_key_exists($id, $productsInCart)) {
			$productsInCart[$id]++;
		} else {
			// Если нет - добавить новый товар в корзину
			$productsInCart[$id] = 1;
		}
		
		$_SESSION['products'] = $productsInCart;

		return self::countItems();
	}

	public static function countItems() {
		if (isset($_SESSION['products'])) {
			$count = 0;
			foreach ($_SESSION['products'] as $id => $quantity) {
				$count += $quantity;
			}
			return $count;
		} else {
			return 0;
		}
	}

	public static function getProducts() {
        if (isset($_SESSION['products'])) {
            return $_SESSION['products'];
        }
        return false;
    }

    public static function getTotalPrice($products) {
        $productsInCart = self::getProducts();

        $total = 0;
        
        if ($productsInCart) {            
            foreach ($products as $item) {
                $total += $item['price'] * $productsInCart[$item['id']];
            }
        }

        return $total;
    }

    public static function clear() {
    	if (isset($_SESSION['products'])) {
    		unset($_SESSION['products']);
    	}
    }

    public static function deleteProduct($id) {
    	if (isset($_SESSION['products']["$id"])) {
    		unset($_SESSION['products']["$id"]);        	
        }
        return true;
    }
}