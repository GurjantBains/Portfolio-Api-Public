<?php

class Router
{
    /**
     * @throws Exception
     */
    public static function handle($url, $method)
    {
        $routes = Route::getRoutes();

        if(!array_key_exists($method,$routes)){
            self::notFound();
            return;

        }

        foreach ($routes[$method] as $pattern => $action){
            if(preg_match($pattern,$url,$matches)){
                $params = array_filter($matches,'is_string',ARRAY_FILTER_USE_KEY);
                self::callAction($action,$params);
                return;
            }
            }
                self::notFound();


    }

    /**
     * @throws Exception
     */
    private static function callAction($action, $params){

        if(is_string($action)){
            list($controller,$method) = explode('@',$action);
            $controllerFile = realpath(__DIR__ . "/../controllers/{$controller}.php");
            if(file_exists($controllerFile)){
                require_once($controllerFile);
            $controller = 'controllers\\'.$controller;
//            echo $controllerFile;
                if(class_exists($controller) && method_exists($controller,$method)){
                    $controllerInstance = new $controller();
                    call_user_func_array([$controllerInstance,$method],$params);
                    return;
                }
                else{
                    self::notFound();
                }
                return;

            }
            else{
                self::notFound();

            }

        }
        elseif (is_callable($action)){
            call_user_func_array($action,$params);
            return;
        }
        throw new Exception("Unknown action");

    }



    private static function notFound()
    {
        echo "Page not found <br>";
        http_response_code(404);
    }



}