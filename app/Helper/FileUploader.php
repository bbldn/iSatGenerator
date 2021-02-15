<?php

namespace App\Helper;

use App\Exceptions\FileUploaderException;
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
    private string $url;

    private string $token;

    private HttpClientInterface $httpClient;

    /**
     * FileUploader constructor.
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $url
     * @return FileUploader
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param string $token
     * @return FileUploader
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
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
            'body' => $formData->bodyToIterable(),
            'headers' => $formData->getPreparedHeaders()->toArray(),
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
    private function validate(ResponseInterface $response): void
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
