<?php

namespace App\Helpers;

class ResponseHelper
{
    // Predefined error codes and messages
    private static $errorMessages = [
        200     =>  'success',
        204     =>  'No content',
        401     =>  'Unauthenticated!',
        403     =>  'Not allowed!',
        404     =>  'Resource Not Found!',
        422     =>  'Validation Errors',
        500     =>  'Internal Server Error!',
    ];

    /**
     * Global function for API response formatting.
     *
     * @param mixed $responseData The data to be returned in the response
     * @param int $responseCode The HTTP response code
     * @param string|null $responseMessage The message to be returned in the response
     * @param bool $isError Whether the response is an error or not
     * @param array $validationErrors Any validation errors to be returned in the response
     *
     * @return json response
    */

    public static function sendResponse($responseData, $responseCode, $responseMessage = null, $isError = false , $validationErrors = []) {
        // Use predefined error message if no message exists.
        $responseMessage = $responseMessage ?? self::$errorMessages[$responseCode];
        return response()->json([
            'status'=>[
                'code'              => $responseCode,
                'message'           => $responseMessage,
                'error'             => $isError,
                'validation_errors' => $validationErrors,
            ],
            'data'  => $responseData
        ]
        , $responseCode, [], JSON_UNESCAPED_UNICODE);
    }

}
