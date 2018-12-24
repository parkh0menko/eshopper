<?php

class AdminOrderController extends AdminBase {
    
    // Action для страницы "Управление заказами"
    public function actionIndex() {
        self::checkAdmin();
        
        // Получение списка заказов
        $ordersList = Order::getOrdersList();
        // Подключение вида
        require_once(ROOT . '/views/admin_order/index.php');
        
        return true;
    }

    // Action для страницы "Редактирование заказа"
    public function actionUpdate($id) {
        self::checkAdmin();
        // Получение данных о конкретном заказе
        $order = Order::getOrderById($id);
        if (isset($_POST['submit'])) {
            // Если форма отправлена   
            // Получение данных из формы
            $userName    = $_POST['userName'];
            $userPhone   = $_POST['userPhone'];
            $userComment = $_POST['userComment'];
            $date        = $_POST['date'];
            $status      = $_POST['status'];
            // Сохранение изменений
            Order::updateOrderById($id, $userName, $userPhone, $userComment, $date, $status);
            // Перенаправление пользователя на страницу управлениями заказами
            header("Location: /eshopper/admin/order/view/$id");
        }
        // Подключение вида
        require_once(ROOT . '/views/admin_order/update.php');
        return true;
    }

    // Action для страницы "Просмотр заказа"
    public function actionView($id) {
        self::checkAdmin();
        // Получение данных о конкретном заказе
        $order = Order::getOrderById($id);
        
        // Получение массива с идентификаторами и количеством товаров
        $productsQuantity = json_decode($order['products'], true);
        // Получение массива с индентификаторами товаров
        $productsIds = array_keys($productsQuantity);
        // Получение списка товаров в заказе
        $products = Product::getProdustsByIds($productsIds);
        // Подключение вида
        require_once(ROOT . '/views/admin_order/view.php');
        return true;
    }

    // Action для страницы "Удалить заказ"
    public function actionDelete($id) {
        self::checkAdmin();
        
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Удаление заказа
            Order::deleteOrderById($id);
            // Перенаправление пользователя на страницу управлениями товарами
            header("Location: /eshopper/admin/order");
        }
        // Подключение вида
        require_once(ROOT . '/views/admin_order/delete.php');
        return true;
    }
}