<?php

namespace HsBremen\WebApi;

use Basster\Silex\Provider\Swagger\SwaggerProvider;
use Basster\Silex\Provider\Swagger\SwaggerServiceKey;
use HsBremen\WebApi\Course\CourseServiceProvider;
use HsBremen\WebApi\Database\DatabaseProvider;
use HsBremen\WebApi\Error\ErrorProvider;
use HsBremen\WebApi\Logging\LoggingProvider;
use HsBremen\WebApi\Order\OrderServiceProvider;
use HsBremen\WebApi\Security\SecurityProvider;
use HsBremen\WebApi\Security\UserServiceProvider;
use JDesrosiers\Silex\Provider\CorsServiceProvider;
use Silex\Application as Silex;
use Silex\Provider\ServiceControllerServiceProvider;
use Swagger\Annotations as SWG;
use SwaggerUI\Silex\Provider\SwaggerUIServiceProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Application
 *
 * @package HsBremen\WebApi
 * @SWG\Swagger(
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     basePath="/",
 *     host="web-api.vm"
 * )
 * @SWG\Info(
 *     title="Kurs-Planer",
 *     version="0.1"
 * )
 */
class Application extends Silex
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);
        $this->register(new ServiceControllerServiceProvider());

        $app = $this;

        $app['base_path']    = __DIR__;
        $app['logging_path'] = $app['base_path'] . '/../logs';

        $this->register(new SwaggerProvider(),
                        [
                          SwaggerServiceKey::SWAGGER_SERVICE_PATH => $app['base_path'],
                          SwaggerServiceKey::SWAGGER_API_DOC_PATH => '/docs/swagger.json',
                        ]);

        $app->register(new SwaggerUIServiceProvider(),
                       [
                         'swaggerui.path' => '/docs/swagger',
                         'swaggerui.docs' => '/docs/swagger.json',
                       ]);

        // logging
        $this->register(new LoggingProvider());

        // enable cross origin requests!
        $app->register(new CorsServiceProvider());

        // enable database connection
        $app->register(new DatabaseProvider());

        // Security
        $this->register(new SecurityProvider());
        $this->register(new UserServiceProvider());
        
        // all about orders
        $this->register(new OrderServiceProvider());
        // course Provider
        $this->register(new CourseServiceProvider());

        // error handling
        // https://github.com/financialmedia/FMKeystoneBundle/issues/12 // Fuck this shit!!!!!!!!!!
        $this->register(new ErrorProvider());

        // http://silex.sensiolabs.org/doc/cookbook/json_request_body.html
        $this->before(function (Request $request) use ($app) {
            if ($app->requestIsJson($request)) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : []);
            }
        });

        $app->after($app['cors']);
    }

    private function requestIsJson(Request $request)
    {
        return 0 === strpos(
          $request->headers->get('Content-Type'),
          'application/json'
        );
    }
}
