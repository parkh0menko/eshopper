<?php 

class Router {
	private $routes; // Массив для хранения маршрутов

	public function __construct() {
		$routesPath = ROOT . '/config/routes.php';
		$this->routes = require $routesPath;
	}

	// Получение строки запроса
	private function getURI() {
		if (!empty($_SERVER['REQUEST_URI'])) {
			return trim($_SERVER['REQUEST_URI'], '/');
		}
	}

	public function run() {		
		$uri = $this->getURI();

		foreach ($this->routes as $uriPattern => $path) {
			if (preg_match("~$uriPattern~", $uri)) {
				// Получение внутреннего пути из внешнего согласно правилу
				$internalRoute = preg_replace("~$uriPattern~", $path, $uri);

				$segments = explode('/', $internalRoute);
				
				/*
				   Магия.
				   Без нее не работает.
				*/  
				if ($segments[0] == 'eshopper') {
					$segments = array_slice($segments, 1);
				}

				// Определение необходимых controller, action, параметров
				$controllerName = ucfirst(array_shift($segments) . 'Controller');				
				$actionName     = 'action' . ucfirst(array_shift($segments));
				$parameters     = $segments;

				// Подключение файла класса-контроллера
				$controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
				if (file_exists($controllerFile)) {
					require_once $controllerFile;
				}

				// Создание объекта класса контроллера
				$controllerObject = new $controllerName;

				// Вызов метода $actionName у объекта $ControllerObject
				// И передача ему массива с параметрами
				// Параметры будут переданы как переменные
				$result = call_user_func_array(array($controllerObject, $actionName), $parameters);

				if($result != null) {
					break;
				}
			}
		}
	}
}