<?php
return [
    "name" => "laradmin",

    /*
   |--------------------------------------------------------------------------
   | Set the http status code when the response fails
   |--------------------------------------------------------------------------
   |
   | the reference options are false, 200, 500
   |
   | false, stricter http status codes such as 404, 401, 403, 500, etc. will be returned
   | 200, All failed responses will also return a 200 status code
   | 500, All failed responses return a 500 status code
   */

    'error_code' => false,

    // You can use enumerations to define the code when the response is returned,
    // and set the response message according to the locale
    //
    // The following two enumeration packages are good choices

    'enum' => '',
    'exception' => [
        \Illuminate\Validation\ValidationException::class => [
            'code' => 422,
        ],
        \Illuminate\Auth\AuthenticationException::class => [

        ],
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => [
            'message' => '',
        ],
        \Illuminate\Database\Eloquent\ModelNotFoundException::class => [
            'message' => '',
        ],
    ],

    // Set the structure of the response data
    'format' => [
        'fields' => [
            'status' => ['alias' => 'status', 'show' => true],
            'code' => ['alias' => 'code', 'show' => true],
            'message' => ['alias' => 'message', 'show' => true],
            'error' => ['alias' => 'error', 'show' => true],
            'data' => [
                'alias' => 'data',
                'show' => true,

                'fields' => [
                    // When data is nested with data, such as returning paged data, you can also set an alias for the inner data
                    'data' => ['alias' => 'data', 'show' => true], // data/rows/list

                    'meta' => [
                        'alia' => 'meta',
                        'show' => true,

                        'fields' => [
                            'pagination' => [
                                'alias' => 'pagination',
                                'show' => true,

                                'fields' => [
                                    'total' => ['alias' => 'total', 'show' => true],
                                    'count' => ['alias' => 'count', 'show' => true],
                                    'per_page' => ['alias' => 'per_page', 'show' => true],
                                    'current_page' => ['alias' => 'current_page', 'show' => true],
                                    'total_pages' => ['alias' => 'total_pages', 'show' => true],
                                    'links' => [
                                        'alias' => 'links',
                                        'show' => true,

                                        'fields' => [
                                            'previous' => ['alias' => 'previous', 'show' => true],
                                            'next' => ['alias' => 'next', 'show' => true],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
