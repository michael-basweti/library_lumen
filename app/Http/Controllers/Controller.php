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
     *   ),
     * @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
     * )
     * @OA\SecurityScheme(
     * type="http",
*      securityScheme="bearerAuth",
*      in="header",
*      name="bearerAuth",
*      scheme="bearer",
*      bearerFormat="JWT",
* ),
     */

}
