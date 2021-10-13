<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../private/config.php';

$app = AppFactory::create();

$twig = Twig::create(__DIR__ . '/../templates', [
    //'cache' => __DIR__ . '/../cache'
]);

$app->add(TwigMiddleware::create($app, $twig));

$algorithmRegister = new \AlgorithmRegister\AlgorithmRegister($config["organizations"]);

$app->get('/', function (Request $request, Response $response, $args) use ($config, $algorithmRegister) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'overzicht.twig', [
        'organization' => $algorithmRegister->getOrganizationName(),
        'items' => $algorithmRegister->getClient()->readApplications()
    ]);
});

$app->get('/details/{id}', function (Request $request, Response $response, $args) use ($config, $algorithmRegister) {
    $view = Twig::fromRequest($request);
    $id = $args['id'];
    $application = $algorithmRegister->getClient()->readApplication($id);
    $schema = json_decode(file_get_contents($application["schema"]), true);
    $grouped = [];
    foreach ($application as $key => $value) {
        $grouped[$schema["properties"][$key]["category"]][] = array_merge($schema["properties"][$key], ["value" => $value]);
    }
    //unset($grouped["METADATA"]);
    return $view->render($response, 'details.twig', [
        'id' => $id,
        'title' => $application["name"],
        'description' => $application["description"],
        'grouped' => $grouped
    ]);
});

$app->get('/details/{id}/log', function (Request $request, Response $response, $args) use ($config, $algorithmRegister) {
    $view = Twig::fromRequest($request);
    $id = $args['id'];
    $application = $algorithmRegister->getClient()->readApplication($id);
    try {
        $events = $algorithmRegister->getClient()->readEvents($id);
    } catch (\Exception $exception) {
        $events = []; //FIXME 
    }
    return $view->render($response, 'details-log.twig', [
        'id' => $id,
        'title' => $application["name"],
        'description' => $application["description"],
        'events' => $events
    ]);
});

$app->run();
