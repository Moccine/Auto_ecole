<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseAjaxController extends AbstractController
{
    /**
     * @param null $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function returnJsonResponse($data = null, $message = '', $statusCode = Response::HTTP_OK)
    {
        $response = new JsonResponse();
        $response
            ->setStatusCode($statusCode)
            ->setData(
                [
                    'message' => $message,
                    'data' => $data,
                ]
            );

        return $response;
    }
}
