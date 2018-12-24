<?php 

abstract class AdminBase {

	public static function checkAdmin() {
		// Проверка авторизации пользователя
		$userId = User::checkLogged();

		// Получение информации о пользователе
		$user = User::getUserById($userId);

		// Если пользователь является администратором - "пустить" в админпанель
		if ($user['role'] == 'admin') {
			return true;
		}
		
		die('Access denied');
	}
}