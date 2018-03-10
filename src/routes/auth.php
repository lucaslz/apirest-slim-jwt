<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Firebase\JWT\JWT;

use App\Models\Users;

$app->post('/auth/token', function(Request $request, Response $response, array $args){
	$data = $request->getParsedBody();

	$email = $data['email'] ?? null;
	$password = $data['password'] ?? null;

	$user = Users::where('email', $email)->first();

	if(!is_null($user) and password_verify($password, $user->password)) {
		$key = $this->get('settings')['secretKey'];

		return $response->withJson([
			'token' => JWT::encode($user, $key)
		]);
	}

	return $response->withJson(['status' => 'error'], 401);
});