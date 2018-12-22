<?php 

class CartController {

	public function actionAdd($id) {
		// Добавление товара в корзину
		Cart::addProduct($id);

		// Возвращение пользователя на страницу
		$referrer = $_SERVER['HTTP_REFERER'];
		print_r($referrer);
		header("Location: $referrer");
	}

	public function actionDelete($id) {
        Cart::deleteProduct($id);
        // Возвращение пользователя на страницу
        header("Location: /eshopper/cart/");
    }

	public function actionAddAjax($id) {
		echo Cart::addProduct($id);
		return true;
	}

	public function actionIndex() {
        $categories = array();
        $categories = Category::getCategoriesList();

        $productsInCart = false;

        // Получение данных из корзины
        $productsInCart = Cart::getProducts();

        if ($productsInCart) {
            // Получение полной информации о товарах для списка
            $productsIds = array_keys($productsInCart);
            $products = Product::getProdustsByIds($productsIds);

            // Получение общей стоимости товаров
            $totalPrice = Cart::getTotalPrice($products);
        }

        require_once(ROOT . '/views/cart/index.php');

        return true;
    }

    public function actionCheckout() {
    	// Список категорий для левого меню
    	$categories = Category::getCategoriesList();

    	// Статус успешного оформления заказа
    	$result = false;

    	if (isset($_POST['submit'])) {
    		$userName    = $_POST['userName'];
    		$userPhone   = $_POST['userPhone'];
    		$userComment = $_POST['userComment'];

    		// Валидация полей
    		$errors = false;
    		if (!User::checkName($userName)) {
    			$errors[] = 'Неправильное имя';
    		}
    		if (!User::checkPhone($userPhone)) {
    			$errors[] = 'Неправильный телефон';
    		}

    		// Если форма заполнена корректно
    		if ($errors == false) {
    			// Сохранение заказа в базе данных

    			// Сбор информации о заказе
    			$productsInCart = Cart::getProducts();
    			if (User::isGuest()) {
    				$userId = false;
    			} else {
    				$userId = User::checkLogged();
    			}

    			// Сохранение заказа в бд
    			$result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);

                if ($result) {
                    // Оповещение администратора о новом заказе                
                    $adminEmail = 'evg.par.work@gmail.com';
                    $message 	= 'http://eshopper/admin/orders';
                    $subject 	= 'Новый заказ!';
                    mail($adminEmail, $subject, $message);

                    // Очищение корзины
                    Cart::clear();
                }
            } else {
                // Форма заполнена не корректно
                // Итоги: общая стоимость, количество товаров
                $productsInCart = Cart::getProducts();
                $productsIds 	= array_keys($productsInCart);
                $products 		= Product::getProdustsByIds($productsIds);
                $totalPrice 	= Cart::getTotalPrice($products);
                $totalQuantity  = Cart::countItems();
            }
        } else {
            // Если форма не отправлена
            // Получение данных из корзины      
            $productsInCart = Cart::getProducts();

            if ($productsInCart == false) {
                // Если в корзине нет товаров
                // Отправление пользователя на главную искать товары
                header("Location: /eshopper");
            } else {
                // Если в корзине есть товары
                // Итоги: общая стоимость, количество товаров
                $productsIds   = array_keys($productsInCart);
                $products 	   = Product::getProdustsByIds($productsIds);
                $totalPrice    = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();


                $userName 	 = false;
                $userPhone   = false;
                $userComment = false;

                // Если пользователь не авторизован
                if (User::isGuest()) {
                    // Значения для формы пустые
                } else {
                    // Если авторизирован                    
                    // Получение информации о пользователе из БД по id
                    $userId = User::checkLogged();
                    $user   = User::getUserById($userId);
                    // Подставление в форму
                    $userName = $user['name'];
                }
            }
        }

        require_once(ROOT . '/views/cart/checkout.php');

        return true;    	    	
    }
}