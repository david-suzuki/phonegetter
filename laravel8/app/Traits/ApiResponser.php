<?php

namespace App\Traits;

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
	protected function success($token, string $data = null, int $code = 200)
	{
		return response()->json([
			'status' => 'Success',
			'data' => $data,
			'token' => $token
		], $code);
	}
	protected function error(string $data = null, int $code, $token = null)
	{
		return response()->json([
			'status' => 'Error',
			'data' => $data,
			'token' => $token
		], $code);
	}
}