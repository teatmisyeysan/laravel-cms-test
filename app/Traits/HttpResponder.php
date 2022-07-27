<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait HttpResponder {

    protected function parseResponseData($responseData = [], $statusCode = 200, $headers = []): array {

        $responseStructure = [
            "success" => $responseData["success"], //if error, false will be by default, else true
            "statusCode" => $statusCode, //if not passed, will be 200 as default
            "message" => $responseData["message"] ?? "", #use empty string instead of null
            "property" => $responseData["data"] ?? null
        ];

        if($responseData["success"] === false) { //In case that API is being error
            $responseStructure["customErrors"] = (object)[];

            if(isset($responseData["errors"])) {
                $responseStructure["customErrors"]->errors = $responseData["errors"];
            }

            if(isset($responseData["error_code"])) {
                $responseStructure["customErrors"]->error_code = $responseData["error_code"];

            } else {
                $responseStructure["customErrors"]->error_code = 1;
            }

            if(isset($responseData["description"])) {
                $responseStructure["customErrors"]->description = $responseData["description"];
            }
        }

        return ["responseContent" => $responseStructure, "statusCode" => $statusCode, "headers" => $headers];
    }

    protected function handleAPIResponse($responseData = [], $statusCode = 200, $headers = []): JsonResponse {
        $result = $this->parseResponseData($responseData, $statusCode, $headers);

        return response()->json(
            $result["responseContent"],
            $result["statusCode"],
            $result["headers"]
        );
    }

    protected function responseWithResource(JsonResource $resource, $message = "Ok", $statusCode = 200, $headers = []): JsonResponse {
        return $this->handleAPIResponse([
            "success" => true,
            "message" => $message,
            "data" => (object)['data' => $resource],
        ], $statusCode, $headers);
    }

    protected function responseWithResourceCollection(ResourceCollection $resourceCollection, $message = "Ok", $statusCode = 200, $headers = []): JsonResponse {
        $resourceCollections = $resourceCollection->response()->getData();
        return $this->handleAPIResponse([
            "success" => true,
            "message" => $message,
            "data" => $resourceCollections
        ], $statusCode, $headers);
    }

    protected function respondCreated($data, $message = "Create resource successfully."): JsonResponse {
        return $this->handleAPIResponse([
            "success" => true,
            "message" => $message,
            "data" => (object)['data' => $data],
        ], ResponseAlias::HTTP_CREATED);
    }

    protected function respondSuccess($data, $message = "Getting resource successfully."): JsonResponse {
        return $this->handleAPIResponse([
            "success" => true,
            "message" => $message,
            "data" => (object)['data' => $data],
        ]);
    }

    protected function respondDelete($data, $message = "Delete resource successfully."): JsonResponse {
        return $this->handleAPIResponse([
            "success" => true,
            "message" => $message,
            "data" => (object)['data' => $data],
        ]);
    }

    protected function respondUpdate($data, $message = "Update resource successfully."): JsonResponse {
        return $this->handleAPIResponse([
            "success" => true,
            "message" => $message,
            "data" => (object)['data' => $data],
        ]);
    }

    protected function responseError($message, int $statusCode = 400, Exception $exception = null, int $error_code = 1): JsonResponse {
        return $this->handleAPIResponse([
            "success" => false,
            "message" => $message ?? "There was an internal error, Please try again later",
            "exception" => $exception,
            "error_code" => $error_code
        ], $statusCode);
    }

    protected function responseForbidden($message =  "Forbidden"): JsonResponse {
        return $this->responseError($message, ResponseAlias::HTTP_FORBIDDEN);
    }

    protected function responseNotFound($message = "Not Found"): JsonResponse {
        return $this->responseError($message, ResponseAlias::HTTP_NOT_FOUND);
    }

    protected function respondNoContent(string $message = "No Content Found"): JsonResponse {
        return $this->handleAPIResponse([
            "success" => false,
            "message" => $message
        ]);
    }

    protected function responseUnAuthorized($message = "Unauthorized"): JsonResponse {
        return $this->responseError($message, ResponseAlias::HTTP_UNAUTHORIZED);
    }

    protected function responseInternalError($message = "Not Found"): JsonResponse {
        return $this->responseError($message, ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function responseValidationErrors(ValidationException $exception): JsonResponse {
        return $this->handleAPIResponse([
            "success" => false,
            "message" => $exception->getMessage(),
            "errors" => $exception->errors()
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }

    protected function responseQueryError($message = "Resource cannot be deleted due to existence of related resources."): JsonResponse {
        return $this->handleAPIResponse([
            "success" => false,
            "message" => "Error query exception!",
            'error_code' => 12,
            'description' => $message // will be changed later
        ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

}


