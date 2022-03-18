<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Psr7\UploadedFile;
use WellspringTest\src\Helpers;
use WellspringTest\src\TrainLine;
use WellspringTest\src\TrainRoute;
use WellspringTest\src\TrainRun;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/checkHistory', function (Request $request, Response $response) {
	if (isset($_COOKIE['data']) && !empty($_COOKIE['data'])) {
		$response->getBody()->write($_COOKIE['data']);
	}
	return $response;
});

$app->post('/clear', function (Request $request, Response $response) {
	setcookie('data');
	$response->getBody()->write(json_encode(['status' => 'success']));
	return $response;
});

//read csv file
$app->post('/read', function (Request $request, Response $response) {
	// get file
	$uploadedFiles = $request->getUploadedFiles();
	if (empty($uploadedFiles)) {
		$response->getBody()->write('error: no file uploaded');
	}

	$directory = __DIR__ . DIRECTORY_SEPARATOR;
	if (!is_dir($directory)) {
		mkdir($directory);
	}

	// handle single input with single file upload
	$uploadedFile = $uploadedFiles['files'][0];

	if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
		$filename = moveUploadedFile($directory, $uploadedFile);
		if (!empty($filename)) {
			// parse file into json
			$json = Helpers::csvToJson($filename);
			// save updated json to cookie
			setcookie('data', $json);
			// return parsed file as json
			$response->getBody()->write($json);
			// save details to db for persistence
			$trains = json_decode($json, true);
			foreach ($trains as $routeSet) {
				$line = new TrainLine($routeSet['TRAIN_LINE']);
				$route = new TrainRoute($routeSet['ROUTE_NAME'], $line);
				new TrainRun($routeSet['RUN_NUMBER'], $routeSet['OPERATOR_ID'], $route);
			}
			return $response;
		}
		// return error if needed
		$response->getBody()->write('error parsing file');
		$response->setStatus(400);
		return $response;
	}
	$response->getBody()->write('upload failed' . $uploadedFile->getError());
	$response->setStatus(500);
	return $response;
});

/**
 * Moves the uploaded file to the upload directory and assigns it a unique name
 * to avoid overwriting an existing uploaded file.
 *
 * @param string $directory directory to which the file is moved
 * @param UploadedFile $uploadedFile file uploaded file to move
 * @return string filename of moved file
 */
function moveUploadedFile(string $directory, UploadedFile $uploadedFile): string
{
	$filename = $uploadedFile->getClientFilename();
	//check to make sure file does not already exist
	if (!is_file($directory . DIRECTORY_SEPARATOR . $filename)) {
		$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
	}

	return $directory . DIRECTORY_SEPARATOR . $filename;
}

$app->run();
