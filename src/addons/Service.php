<?php
declare(strict_types=1);

namespace taoser\addons;

use think\Route;
use think\helper\Str;
use think\facade\Config;
use think\facade\Cache;
use think\facade\Event;
use taoser\addons\middleware\Addons;

/**
 * 插件服务
 * Class Service
 * @package think\addons
 */
class Service extends \think\Service
{
    protected $addons_path;

    // 注册系统服务，将服务绑定到容器中
    public function register()
    {
<<<<<<< HEAD
        $this->addons_path = $this->getAddonsPath();
        // 加载系统语言包
        $this->loadLang();
        // 自动载入插件
        $this->autoload();
        // 加载插件事件
        $this->loadEvent();
<<<<<<< HEAD
        // 加载自定义路由
        $this->loadRoutes();
        // 加载插件系统服务
        $this->loadService();
        // 加载插件命令
        $this->loadCommand();
        // 加载配置
        $this->loadConfig();
=======
        // // 加载自定义路由
        $this->loadRoutes();
        // // 加载插件系统服务
        $this->loadService();
        // // 加载插件命令
        // $this->loadCommand();
        // // 加载配置
        // $this->loadConfig();
>>>>>>> 2.0
        // 绑定插件容器
        $this->app->bind('addons', Service::class);
=======
        $this->app->bind('addons', AddonsSystem::class);        

        // $this->initLazyLoader();
>>>>>>> 3.0
        
    }

    public function boot()
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $this->registerRoutes(function (Route $route) {
            // 只有在addons下进行注册解析
            $path = $this->app->request->pathinfo();
            $pathArr = explode("/", str_replace('.html','', str_replace('\\', '/', $path)));
            if($pathArr[0] === 'addons') {
                // 路由脚本
                $execute = '\\taoser\\addons\\Route::execute';

                // 中间件数组
                $middlewaresArr = [];

                // 注册插件公共中间件
            if (is_file($this->app->addons->getAddonsPath() . 'middleware.php')) {
                $this->app->middleware->import(include $this->app->addons->getAddonsPath() . 'middleware.php', 'route');
//
//                    // addons目录下全局中间件，对所有addons都生效
//                    //$middleware = (array) include $this->app->addons->getAddonsPath() . 'middleware.php';
//                    // 执行addons全局中间件
//                    //$route->rule("addons/:addon/[:controller]/[:action]", $execute)->middleware($middleware);
//                    //$middlewaresArr = array_merge($middlewaresArr, $middleware);
            }


//            $middlewareDir = $this->app->addons->getAddonsPath() . $addon. DIRECTORY_SEPARATOR . 'middleware' .  DIRECTORY_SEPARATOR;
                    // 如果插件下存在middleware文件夹
//            if(is_dir($middlewareDir)) {
//                //配置
//                $middleware_dir = scandir($middlewareDir);
//                foreach ($middleware_dir as $name) {
//                    if (in_array($name, ['.', '..'])) {
//                        continue;
//                    }
//                    if(is_dir($middlewareDir . $name)) continue;
//                    $middlewareClassName = str_replace('.php','',$name);
//                    $middlewareClass = "\\addons\\{$addon}\\middleware\\{$middlewareClassName}";
//
//                    array_push($middlewaresArr, $middlewareClass);
//                }
//            }

                // 注册控制器路由
                $route->rule("addons/:addon/[:controller]/[:action]", $execute)->middleware(Addons::class);

                // 自定义路由
                $routes = (array) Config::get('addons.route', []);
                foreach ($routes as $key => $val) {
                    if (!$val) {
                        continue;
                    }
                    if (is_array($val)) {
                        $domain = $val['domain'];
                        $rules = [];
                        foreach ($val['rule'] as $k => $rule) {
                            [$addon, $controller, $action] = explode('/', $rule);
                            $rules[$k] = [
                                'addon'        => $addon,
                                'controller'    => $controller,
                                'action'        => $action,
                                'indomain'      => 1,
                            ];
                        }
                        $route->domain($domain, function () use ($rules, $route, $execute) {
                            // 动态注册域名的路由规则
                            foreach ($rules as $k => $rule) {
                                $route->rule($k, $execute)
                                    ->name($k)
                                    ->completeMatch(true)
                                    ->append($rule);
                            }
                        });
                    } else {
                        list($addon, $controller, $action) = explode('/', $val);
                        $route->rule($key, $execute)
                            ->name($key)
                            ->completeMatch(true)
                            ->append([
                                'addon' => $addon,
                                'controller' => $controller,
                                'action' => $action
                            ]);
                    }
                }
            }
        });
=======
=======

        $addonsSystem = new AddonsSystem();

        $addonsSystem->autoload();
        // $addonsSystem->loadEvent();

>>>>>>> 3.0
        // 立即注册路由
        $route = $this->app->route;
        $execute = '\\taoser\\addons\\Route::execute';

        $middlewareArr = [Addons::class];
        // 注册插件公共中间件
        if (is_file($this->app->addons->getAddonsPath() . 'middleware.php')) {
            $middlewareArr = array_merge($middlewareArr, include $this->app->addons->getAddonsPath() . 'middleware.php');
        }

        // 注册插件控制器路由
        $route->rule("app/:addon/[:controller]/[:action]", $execute)->middleware($middlewareArr);
        
        /**** 监听注册路由
        // 监听注册路由
        $this->registerRoutes(function (Route $route) {
            
            // 路由脚本
            $execute = '\\taoser\\addons\\Route::execute';

<<<<<<< HEAD
        //     // 注册插件公共中间件
        //     if (is_file($this->app->addons->getAddonsPath() . 'middleware.php')) {
        //         // $this->app->middleware->import(include $this->app->addons->getAddonsPath() . 'middleware.php', 'route');
        //     }

        //     // 注册控制器路由
        //     // $route->rule("addons/:addon/[:controller]/[:action]", $execute)->middleware(Addons::class);
            
        //     // 使用路由分组
        //     $route->group('addons',function () use ($route, $execute){
        //         $route->rule(':addon/:controller/:action',':controller/:action');
        //         // $route->rule(':addon/:controller/:action', $execute);
        //     })
        //     ->middleware(\app\middleware\AccessControl::class)
        //     ->namespace('addons\:addon\controller');

        //     // 自定义路由
        //     // $routes = (array) Config::get('addons.route', []);

        //     // if(!empty($routes)) {
        //     //     foreach ($routes as $key => $val) {
        //     //         if (!$val) {
        //     //             continue;
        //     //         }
        //     //         if (is_array($val)) {
        //     //             $domain = $val['domain'];
        //     //             $rules = [];
        //     //             foreach ($val['rule'] as $k => $rule) {
        //     //                 [$addon, $controller, $action] = explode('/', $rule);
        //     //                 $rules[$k] = [
        //     //                     'addon'        => $addon,
        //     //                     'controller'    => $controller,
        //     //                     'action'        => $action,
        //     //                     'indomain'      => 1,
        //     //                 ];
        //     //             }
        //     //             $route->domain($domain, function () use ($rules, $route, $execute) {
        //     //                 // 动态注册域名的路由规则
        //     //                 foreach ($rules as $k => $rule) {
        //     //                     $route->rule($k, $execute)
        //     //                         ->name($k)
        //     //                         ->completeMatch(true)
        //     //                         ->append($rule);
        //     //                 }
        //     //             });
        //     //         } else {
        //     //             list($addon, $controller, $action) = explode('/', $val);
        //     //             $route->rule($key, $execute)
        //     //                 ->name($key)
        //     //                 ->completeMatch(true)
        //     //                 ->append([
        //     //                     'addon' => $addon,
        //     //                     'controller' => $controller,
        //     //                     'action' => $action
        //     //                 ]);
        //     //         }
        //     //     }
        //     // }

        // });

>>>>>>> 2.0
    }

    private function loadLang()
    {
        Lang::load([
            $this->app->getRootPath() . '/vendor/taoser/think-addons/src/lang/zh-cn.php'
        ]);
    }

    /**
     *  自定义路由文件
     */
    private function loadRoutes()
    {
        //配置
        $addons_dir = Cache::get('addons_list');

        // 如果缓存不存在，直接扫描插件目录
        if (empty($addons_dir)) {
            $addons_dir = scandir($this->addons_path);
        }
        
        // 如果仍然为空，直接返回
        if (empty($addons_dir)) {
            return;
        }
  
        foreach ($addons_dir as $name) {
            // 跳过 . 和 ..
            if (in_array($name, ['.', '..'])) {
                continue;
=======
            $middlewareArr = [Addons::class];
            // 注册插件公共中间件
            if (is_file($this->app->addons->getAddonsPath() . 'middleware.php')) {
                $middlewareArr = array_merge($middlewareArr, include $this->app->addons->getAddonsPath() . 'middleware.php');
>>>>>>> 3.0
            }

            // 注册插件控制器路由
            $route->rule("app/:addon/[:controller]/[:action]", $execute)->middleware($middlewareArr);

            // 自定义路由
            $routes = (array) Config::get('addons.route', []);

            if(!empty($routes)) {
                foreach ($routes as $key => $val) {
                    if (!$val) {
                        continue;
                    }
                    if (is_array($val)) {
                        $domain = $val['domain'];
                        $rules = [];
                        foreach ($val['rule'] as $k => $rule) {
                            [$addon, $controller, $action] = explode('/', $rule);
                            $rules[$k] = [
                                'addon'        => $addon,
                                'controller'    => $controller,
                                'action'        => $action,
                                'indomain'      => 1,
                            ];
                        }
                        $route->domain($domain, function () use ($rules, $route, $execute) {
                            // 动态注册域名的路由规则
                            foreach ($rules as $k => $rule) {
                                $route->rule($k, $execute)
                                    ->name($k)
                                    ->completeMatch(true)
                                    ->append($rule);
                            }
                        });
                    } else {
                        list($addon, $controller, $action) = explode('/', $val);
                        $route->rule($key, $execute)
                            ->name($key)
                            ->completeMatch(true)
                            ->append([
                                'addon' => $addon,
                                'controller' => $controller,
                                'action' => $action
                            ]);
                    }
                }
            }

        });

        ****/

    }

    /**
     * 初始化延迟加载器
     */
    private function initLazyLoader(): void
    {
        $loader = HookLazyLoader::getInstance();
        
        $hooks = $this->app->isDebug() ? [] : Cache::get('hooks', []);
        
        if (empty($hooks)) {
            $hooks = (array) Config::get('addons.hooks', []);
            foreach ($hooks as $key => $values) {
                if (is_string($values)) {
                    $values = explode(',', $values);
                } else {
                    $values = (array) $values;
                }
                $hooks[$key] = array_filter(array_map(function ($v) use ($key) {
                    return [get_addons_class($v), $key];
                }, $values));
            }
            Cache::set('hooks', $hooks);
        }

        foreach ($hooks as $hookName => $listeners) {
            if (!empty($listeners)) {
                $loader->registerHook($hookName, $listeners);
            }
        }
    }


    /**
     * 注册延迟加载的钩子
     * 只在钩子首次被触发时才加载对应的监听器
     */
    private function registerLazyHooks(array $hooks): void
    {
        foreach ($hooks as $hookName => $listeners) {
            if (empty($listeners)) {
                continue;
            }

            Event::listen($hookName, function ($params) use ($hookName, $listeners) {
                return HookProxy::execute($hookName, $listeners, $params);
            });
        }
    }


    /**
     * 加载插件命令
     */
    private function loadCommand()
    {
        $results = scandir($this->addons_path);
        foreach ($results as $name) {
            if ($name === '.' or $name === '..') {
                continue;
            }
            if (is_file($this->addons_path . $name)) {
                continue;
            }
            $addonDir = $this->addons_path . $name . DIRECTORY_SEPARATOR;
            if (!is_dir($addonDir)) {
                continue;
            }
            $command_file = $addonDir . 'command.php';
            if (is_file($command_file)) {
                $commands = include_once $command_file;
                if (is_array($commands))
                    $this->commands($commands);
            }
        }
    }

    /**
     * 获取 addons 路径
     * @return string
     */
    public function getAddonsPath()
    {
        // 初始化插件目录
        $addons_path = $this->app->getRootPath() . 'addons' . DIRECTORY_SEPARATOR;
        // 如果插件目录不存在则创建
        if (!is_dir($addons_path)) {
            @mkdir($addons_path, 0755, true);
        }

        return $addons_path;
    }

    /**
     * 获取插件的配置信息
     * @param string $name
     * @return array
     */
    public function getAddonsConfig()
    {
        $name = $this->app->request->addon;
        $addon = get_addons_instance($name);
        if (!$addon) {
            return [];
        }

        return $addon->getConfig();
    }
}
