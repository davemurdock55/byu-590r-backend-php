<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Aws\Sdk;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'result'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 418)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }


    public function getS3Url($path, $minutes = 10)
    {
        if (!$path) {
            return null;
        }

        // alternate way of doing all the code in the controller below this:
        // $url = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes($minutes));
        // return $url;

        $s3 = Storage::disk('s3');
        if ($minutes === null) {
            $s3->setVisibility($path, "public");
            return $s3->url($path);
        }

        return $s3->temporaryUrl($path, now()->addMinutes($minutes));
    }
}
