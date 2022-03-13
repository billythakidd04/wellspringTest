<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Psr7\UploadedFile;
use WellspringTest\Helpers;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//home page probably never getting used
$app->get('/', function (Request $request, Response $response, $args) {
	$body = <<<HTML
		<!-- form to ingest file -->
		<form method="post" enctype="multipart/form-data">
		<!-- upload of a single file -->
		<p>
			<label>Attach CSV: </label><br />
			<input type="file" name="csv" />
		</p>

		<p>
			<input type="submit" />
		</p>
	</form>
	HTML;
	$response->getBody()->write($body);
	return $response;
});

//read csv file
$app->post('/', function (Request $request, Response $response) {
	// get file
	$uploadedFiles = $request->getUploadedFiles();
	var_dump($uploadedFiles);
	$directory = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
	if (!is_dir($directory)) {
		mkdir($directory);
	}

	// handle single input with single file upload
	$uploadedFile = $uploadedFiles['csv'];

	if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
		$output = [];
		$filename = moveUploadedFile($directory, $uploadedFile);
		if (!empty($filename)) {
			// parse file into json
			$json = Helpers::csvToJson($filename);
			// return parsed file as json
			$response->getBody()->write($json);
			return $response;
		}
	}
	$response->getBody()->write('upload failed' . $uploadedFile->getError());
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
function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
	$filename = $uploadedFile->getClientFilename();
	//check to make sure file does not already exist
	if (!is_file($directory . DIRECTORY_SEPARATOR . $filename)) {
		$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
	}

	return $directory . DIRECTORY_SEPARATOR . $filename;
}

//update current csv
$app->get('/update', function (Request $request, Response $response, $args) {
	// check file exists
	// parse update
	// which row and col are we updating
	// validation?
	// return result code
	$response->getBody()->write("Hello world!");
	return $response;
});

// //output new csv from subset of data
// $app->get('/returnNew', function (Request $request, Response $response, $args) {
// 	// filter file for only results that match
// 	// build new file from filtered results
// 	// return resulting file for download
// 	$response->getBody()->write("Hello world!");
// 	return $response;
// });

// //clear data (don't erase file just clear what we have stored)
// $app->get('/returnNew', function (Request $request, Response $response, $args) {
// 	//clear cache
// 	//return empty homepage
// 	$response->getBody()->write("Empty Form aka homepage");
// 	return $response;
// });

// //show list of previous files (or whatever we currently have on disk)
// $app->get('/returnNew', function (Request $request, Response $response, $args) {
// 	//find all files in storage
// 	//return file names and # of rows
// 	$response->getBody()->write("");
// 	return $response;
// });

$app->run();
