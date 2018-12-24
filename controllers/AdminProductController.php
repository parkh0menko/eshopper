<?php 

class AdminProductController extends AdminBase {

	// Action для страницы "Управление товарами"
	public function actionIndex() {
		self::checkAdmin();

		$productsList = Product::getProductsList();

		require_once ROOT . '/views/admin_product/index.php';

		return true;
	}

	// Action для страницы "Создание товара"
	public function actionCreate() {
        self::checkAdmin();
        
        $categoriesList = Category::getCategoriesListAdmin();
        
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получение данных из формы
            $options['name'] 		   = $_POST['name'];
            $options['code'] 		   = $_POST['code'];
            $options['price']		   = $_POST['price'];
            $options['category_id']    = $_POST['category_id'];
            $options['brand'] 		   = $_POST['brand'];
            $options['availability']   = $_POST['availability'];
            $options['description']    = $_POST['description'];
            $options['is_new'] 		   = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status']         = $_POST['status'];
            
            $errors = false;
            
            if (!isset($options['name']) || empty($options['name'])) {
                $errors[] = 'Заполните поля';
            }
            
            if ($errors == false) {
                $id = Product::createProduct($options);
            
                // Если запись добавлена
                if ($id) {
                    // Проверка, загружалось ли через форму изображение
                    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                        // Если загружалось, перемещение его в нужную папку, переименование
                        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/eshopper/upload/images/products/{$id}.jpg");
                    }
                };

                // Перенаправление пользователя на страницу управлениями товарами
                header("Location: /eshopper/admin/product");
            }
        }

        // Подключение вида
        require_once(ROOT . '/views/admin_product/create.php');
        return true;
    }

    // Action для страницы "Редактировать товар"    
    public function actionUpdate($id) {
        self::checkAdmin();
        
        $categoriesList = Category::getCategoriesListAdmin();
        
        // Получение данных о конкретном заказе
        $product = Product::getProductById($id);
        
        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получение данных из формы редактирования.
            $options['name'] 		   = $_POST['name'];
            $options['code'] 		   = $_POST['code'];
            $options['price'] 		   = $_POST['price'];
            $options['category_id']    = $_POST['category_id'];
            $options['brand'] 		   = $_POST['brand'];
            $options['availability']   = $_POST['availability'];
            $options['description']    = $_POST['description'];
            $options['is_new'] 		   = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] 		   = $_POST['status'];

            // Сохранение изменений
            if (Product::updateProductById($id, $options)) {
                // Если запись сохранена
                // Проверка, загружалось ли через форму изображение
                if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                    // Если загружалось, перемещение его в нужную папку, переименование
                   move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/eshopper/eshopper/upload/images/products/{$id}.jpg");
                }
            }

            // Перенаправление пользователя на страницу управлениями товарами
            header("Location: /eshopper/admin/product");
        }

        // Подключение вида
        require_once(ROOT . '/views/admin_product/update.php');
        return true;
    }
    
    // Action для страницы "Удалить товар"
    public function actionDelete($id) {
        self::checkAdmin();
        
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Удаление товара
            Product::deleteProductById($id);
            // Перенаправление пользователя на страницу управлениями товарами
            header("Location: /eshopper/admin/product");
        }

        // Подключение вида
        require_once(ROOT . '/views/admin_product/delete.php');
        return true;
    }
}