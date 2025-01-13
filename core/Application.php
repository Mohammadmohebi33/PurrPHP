<?php


namespace App\core;

class Application{

    public Request $request;
    public Response $response;
    public Router $router;
    public static $app;

    public static string $ROOT_DIR;
    public Controller $controller;
    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);

    }


    public function run()
    {
        $this->router->resolve();
    }
}