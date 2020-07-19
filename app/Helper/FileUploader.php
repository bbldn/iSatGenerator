<?php

namespace App\Helper;

use App\Exceptions\FileUploaderException;
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
     * @throws FileUploaderException
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

        $this->validate($response);
    }

    /**
     * @param ResponseInterface $response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws FileUploaderException
     */
    protected function validate(ResponseInterface $response): void
    {
        if (200 !== $response->getStatusCode()) {
            throw new FileUploaderException('Response is not 200');
        }

        $json = json_decode($response->getContent(), true);
        if (false === $json) {
            throw new FileUploaderException('Response is not JSON');
        }

        if (false === key_exists('ok', $json)) {
            throw new FileUploaderException('Response is not include `ok` field');
        }

        if (false === $json['ok']) {
            if (false === key_exists('errors', $json)) {
                throw new FileUploaderException('Response is not include `errors` field');
            }

            throw new FileUploaderException('Error:' . implode('', $json['errors']));
        }
    }
}
