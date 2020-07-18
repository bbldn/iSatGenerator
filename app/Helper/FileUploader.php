<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FileUploader
{
    /** @var HttpClientInterface $httpClient */
    protected $httpClient;

    /** @var string $url */
    protected $url;

    /** @var string $token */
    protected $token;

    /**
     * FileUploader constructor.
     * @param HttpClientInterface $httpClient
     * @param string $url
     * @param string $token
     */
    public function __construct(HttpClientInterface $httpClient, string $url, string $token)
    {
        $this->httpClient = $httpClient;
        $this->url = $url;
        $this->token = $token;
    }

    /**
     * @param string $fileName
     * @param string $destination
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function send(string $fileName, string $destination): void
    {
        $formData = new FormDataPart([
            'auth' => $this->token,
            'action' => 'save-file',
            'destination' => $destination,
            'source' => DataPart::fromPath($fileName),
        ]);

        $response = $this->httpClient->request('POST', $this->url, [
            'headers' => $formData->getPreparedHeaders()->toArray(),
            'body' => $formData->bodyToIterable(),
        ]);

        if (200 !== $response->getStatusCode()) {
            Log::error(ExceptionFormatter::f('Response is not 200'));

            return;
        }

        $this->validate($response);
    }

    /**
     * @param ResponseInterface $response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function validate(ResponseInterface $response): void
    {
        $json = json_decode($response->getContent(), true);
        if (false === $json) {
            Log::error(ExceptionFormatter::f('Response is not JSON'));

            return;
        }

        if (false === key_exists('ok', $json)) {
            Log::error(ExceptionFormatter::f('Response is not include `ok` field'));

            return;
        }

        if (false === $json['ok']) {
            if (false === key_exists('errors', $json)) {
                Log::error(ExceptionFormatter::f('Response is not include `errors` field'));

                return;
            }
            Log::error(ExceptionFormatter::f('Error:' . implode(PHP_EOL, $json['errors'])));

            return;
        }
    }
}
