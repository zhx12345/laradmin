<?php

namespace Zhxlan\Laradmin\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

class ApiResponse
{

    /**
     *  Respond with an accepted response and associate a location and/or content if provided.
     *
     * @param array $data
     * @param string $message
     * @param string $location
     * @return JsonResponse|JsonResource
     */
    public static function accepted($data = [], string $message = '', string $location = '')
    {
        $response = self::success($data, $message, 202);
        if ($location) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with a created response and associate a location if provided.
     *
     * @param null $data
     * @param string $message
     * @param string $location
     * @return JsonResponse|JsonResource
     */
    public static function created($data = [], string $message = '', string $location = '')
    {
        $response = self::success($data, $message, 201);
        if ($location) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with a no content response.
     *
     * @param string $message
     * @return JsonResponse|JsonResource
     */
    public static function noContent(string $message = '')
    {
        return self::success([], $message, 204);
    }

    /**
     * Alias of success method, no need to specify data parameter.
     *
     * @param string $message
     * @param int $code
     * @param array $headers
     * @param int $option
     * @return JsonResponse|JsonResource
     */
    public static function ok(string $message = '操作成功', int $code = 200, array $headers = [], int $option = 0)
    {
        return self::success([], $message, $code, $headers, $option);
    }

    /**
     * Alias of the successful method, no need to specify the message and data parameters.
     * You can use ResponseCodeEnum to localize the message.
     *
     * @param int $code
     * @param array $headers
     * @param int $option
     * @return JsonResponse|JsonResource
     */
    public static function localize(int $code = 200, array $headers = [], int $option = 0)
    {
        return self::ok('', $code, $headers, $option);
    }

    /**
     * Return a 400 bad request error.
     *
     * @param string|null $message
     */
    public static function errorBadRequest(string $message = '')
    {
        self::fail($message, 400);
    }

    /**
     * Return a 401 unauthorized error.
     *
     * @param string $message
     */
    public static function errorUnauthorized(string $message = '')
    {
        self::fail($message, 401);
    }

    /**
     * Return a 403 forbidden error.
     *
     * @param string $message
     */
    public static function errorForbidden(string $message = '')
    {
        self::fail($message, 403);
    }

    /**
     * Return a 404 not found error.
     *
     * @param string $message
     */
    public static function errorNotFound(string $message = '')
    {
        self::fail($message, 404);
    }

    /**
     * Return a 405 method not allowed error.
     *
     * @param string $message
     */
    public static function errorMethodNotAllowed(string $message = '')
    {
        self::fail($message, 405);
    }

    /**
     * Return a 500 internal server error.
     *
     * @param string $message
     */
    public static function errorInternal(string $message = '')
    {
        self::fail($message);
    }


    /**
     * Return a success response.
     *
     * @param JsonResource|array|mixed $data
     * @param string $message
     * @param int $code
     * @param array $headers
     * @param int $option
     * @return JsonResponse|JsonResource
     */
    public static function success($data = [], string $message = '', int $code = 200, array $headers = [], int $option = 0)
    {
        if ($data instanceof ResourceCollection) {
            return tap(
                self::response(data: $data, status: $code, headers: $headers, options: $option),
                function ($response) use ($data) {
                    $response->original = $data->resource->map(
                        function ($item) {
                            return is_array($item) ? Arr::get($item, 'resource') : $item->resource;
                        }
                    );

                    $data->withResponse(request(), $response);
                }
            );
        }

        if ($data instanceof JsonResource) {
            return tap(
                self::response(data: $data, status: $code, headers: $headers, options: $option),
                function ($response) use ($data) {
                    $response->original = $data->resource;

                    $data->withResponse(request(), $response);
                }
            );
        }

        if ($data instanceof AbstractPaginator) {
            self::response(data: $data, status: $code, headers: $headers, options: $option);
        }

        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }
        return self::response(data: self::data($data, 'success', $code, $message), status: $code, headers: $headers, options: $option);
    }


    /**
     * Return an fail response.
     *
     * @param string $message
     * @param int $code
     * @param array|null $errors
     * @param array $header
     * @param int $options
     * @return JsonResponse
     *
     * @throws HttpResponseException
     */
    public static function fail(string $message = '', int $code = 500, $errors = null, array $header = [], int $options = 0)
    {
        $response = self::response(
            data: self::data(null, 'fail', $code, $message, $errors),
            status: Config::get('laradmin.error_code') ?: $code,
            headers: $header,
            options: $options
        );
        if (is_null($errors)) {
            $response->throwResponse();
        }
        return $response;
    }


    /**
     * Return a new JSON response from the application.
     *
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return JsonResponse
     */
    protected static function response($data = [], int $status = 200, array $headers = [], int $options = 0): JsonResponse
    {
        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Format return data structure.
     *
     * @param array|null $data
     * @param string|null $message
     * @param int $code
     * @param null $errors
     * @return array
     */
    private static function data(?array $data, string $status, int $code, $message = null, $errors = null): array
    {
        return ['data' => $data, 'status' => $status, 'message' => $message, 'code' => $code, 'errors' => $errors];
    }

    /**
     * Http status code.
     *
     * @param $code
     * @return int
     */

    private static function statusCode($code): int
    {
        return $code;
    }


}
