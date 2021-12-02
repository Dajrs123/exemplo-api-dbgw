<?php
namespace DatabaseGateway;

class Application {
    public function run() {
        $uri_segments = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $repositoryName = 'DatabaseGateway\\' . ucfirst($uri_segments[0]);
        if(class_exists($repositoryName))
            $repository = new $repositoryName($uri_segments[0]);
        else
            $repository = new Repository($uri_segments[0]);
        $request_parameter = isset($uri_segments[1]) ? ['id' => $uri_segments[1]] : [];
        $body_parameters = json_decode(file_get_contents('php://input'), true);
        $result = null;
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $result = call_user_func([$repository, 'select'], $request_parameter);
            break;
            case 'POST':
                $result = call_user_func([$repository, 'insert'], $body_parameters);
            break;
            case 'PUT':
                $result = call_user_func([$repository, 'update'], $body_parameters, $request_parameter);
            break;
            case 'DELETE':
                $result = call_user_func([$repository, 'delete'], $body_parameters);
            break;
        }
        return $result;
    }
}