<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *   title="Lumen Library API",
     *   version="1.0",
     *   @OA\Contact(
     *     email="michael.basweti@andel.com",
     *     name="Michael Basweti"
     *   )
     * )
     * schemes": "["https"]"
     * @OA\SecurityScheme(
     * schemes": "["https"]"
*      securityScheme="bearerAuth",
*      in="header",
*      name="bearerAuth",
*      scheme="bearer",
*      bearerFormat="JWT",
* ),
     */
}
