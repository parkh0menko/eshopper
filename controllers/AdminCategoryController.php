<?php

class AdminCategoryController extends AdminBase {
    
    // Action для страницы "Управление категориями"
    public function actionIndex() {
        self::checkAdmin();
        
        $categoriesList = Category::getCategoriesListAdmin();
        
        require_once(ROOT . '/views/admin_category/index.php');
        
        return true;
    }
    
    // Action для страницы "Добавить категорию"
    public function actionCreate() {
        self::checkAdmin();
        
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получение данных из формы
            $name      = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status    = $_POST['status'];
            
            $errors = false;
            
            if (!isset($name) || empty($name)) {
                $errors[] = 'Заполните поля';
            }
            
            if ($errors == false) {
                // Если ошибок нет
                // Добавление новой категории
                Category::createCategory($name, $sortOrder, $status);
                // Перенаправление пользователя на страницу управлениями категориями
                header("Location: /eshopper/admin/category");
            }
        }

        require_once(ROOT . '/views/admin_category/create.php');
        return true;
    }
    
    // Action для страницы "Редактировать категорию"
    public function actionUpdate($id) {
        self::checkAdmin();
        
        // Получение данных о конкретной категории
        $category = Category::getCategoryById($id);
        
        if (isset($_POST['submit'])) {
            // Если форма отправлена   
            // Получене данных из формы
            $name      = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status    = $_POST['status'];
            
            // Сохранение изменений
            Category::updateCategoryById($id, $name, $sortOrder, $status);
            // Перенаправление пользователя на страницу управлениями категориями
            header("Location: /eshopper/admin/category");
        }
        // Подключение вида
        require_once(ROOT . '/views/admin_category/update.php');
        return true;
    }
    
    // Action для страницы "Удалить категорию"
    public function actionDelete($id) {
        self::checkAdmin();
        
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Удаление категории
            Category::deleteCategoryById($id);
            // Перенаправление пользователя на страницу управлениями товарами
            header("Location: /eshopper/admin/category");
        }
        // Подключение вида
        require_once(ROOT . '/views/admin_category/delete.php');
        return true;
    }
}