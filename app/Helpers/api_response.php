<?php

function api_response($message, $status, $data = null)
{
    $response = [
        'message' => $message,
        'status' => $status,
    ];

    if ($data) {
        $response['data'] = $data;
    }
    return response()->json($response, $status);
}
