<?php

namespace NeonDigital\OpenApiValidator;

use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\PSR7\OperationAddress;

trait ValidatesWithOpenApi
{
    /**
     * The open API validator
     *
     * @var array
     */
    protected $validator;

    public function buildOpenApiValidator($file)
    {
        $this->validator = (new ValidatorBuilder)->fromYaml($file);
        return $this;
    }

    public function assertResponseValid($response, $path, $method = 'get')
    {
        $validator = $this->validator->getResponseValidator();
        $operation = new OperationAddress($path, $method);
        return $this->assertNull(
            $validator->validate($operation, $this->convertToPsr($response))
        );
    }
    /**
     * Create the test response instance from the given response.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function convertToPsr($response)
    {
        return new TestResponse(
            $response->getStatusCode(),
            $response->headers->all(),
            $response->getContent()
        );
    }
}
