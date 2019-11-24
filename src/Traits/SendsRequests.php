<?php

declare(strict_types=1);

namespace Grocelivery\Testing\Laravel\Traits;

use Behat\Gherkin\Node\TableNode;
use Exception;
use Grocelivery\Utils\Responses\JsonResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use PHPUnit\Framework\Assert;

/**
 * Trait SendsRequests
 * @package Grocelivery\Testing\Laravel\Traits
 * @property Application $app
 */
trait SendsRequests
{
    /** @var Request */
    protected $request;
    /** @var JsonResponse */
    protected $response;

    /**
     * @Given :method request to :route route
     * @param string $method
     * @param string $route
     */
    public function requestToRoute(string $method, string $route): void
    {
        $this->request = Request::create($route, $method);
    }

    /**
     * @Given request body is:
     * @param TableNode $table
     */
    public function requestBodyIs(TableNode $table): void
    {
        foreach ($table as $field) {
            $this->request->offsetSet($field['key'], $field['value']);
        }
    }

    /**
     * @Given bearer token header is set to :token
     * @param $token
     */
    public function bearerTokenHeaderIsSetTo(string $token): void
    {
        $this->request->headers->set('Authorization', 'Bearer ' . $token);
    }

    /**
     * @When request is sent
     * @throws Exception
     */
    public function requestIsSent(): void
    {
        $this->response = $this->app->handle($this->request);
    }

    /**
     * @Then response should exist
     */
    public function responseShouldExist(): void
    {
        Assert::assertNotNull($this->response);
    }

    /**
     * @Then response should contain:
     * @param TableNode $keys
     */
    public function responseShouldContain(TableNode $keys): void
    {
        foreach ($keys as $key) {
            $value = data_get($this->response->all(), $key["key"], null);
            Assert::assertNotNull($value);
        }
    }

    /**
     * @Then response should have :status status
     * @param int $status
     */
    public function responseShouldHaveStatus(int $status): void
    {
        Assert::assertEquals($status, $this->response->getStatusCode());
    }

    /**
     * @Then response should have :errors errors
     * @param int $errors
     */
    public function responseShouldHaveErrors(int $errors): void
    {
        Assert::assertEquals($errors, $this->response->countErrors());
    }

    /**
     * @Then response should have error messages:
     * @param TableNode $errorMessages
     */
    public function responseShouldHaveErrorMessages(TableNode $errorMessages): void
    {
        foreach ($errorMessages as $error) {
            Assert::assertContains($error['message'], $this->response->getErrors());
        }
    }
}
