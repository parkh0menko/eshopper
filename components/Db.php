<?php 

class Db { 
	
	public static function getConnection() {

		// Получение параметров соединения
		$paramsPath = ROOT . '/config/db_params.php';
		$params = require $paramsPath;

		$dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
		// Создание объекта для "общения" с бд
		$db = new PDO($dsn, $params['user'], $params['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

		$db->exec("set names utf8");

		return $db;
	}
}