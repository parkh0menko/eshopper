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
}