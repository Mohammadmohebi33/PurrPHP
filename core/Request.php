<?php

namespace App\core;

class Request
{
    /**
     * Get the HTTP method in lowercase.
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
    }

    /**
     * Check if the request method is GET.
     */
    public function isGet(): bool
    {
        return $this->getMethod() === 'get';
    }


    /**
     * Check if the request method is POST.
     */
    public function isPost(): bool
    {
        return $this->getMethod() === 'post';
    }

    /**
     * Retrieve the request body with proper sanitization.
     */
    public function getBody(): array
    {
        $data = [];

        if ($this->isGet()) {
            $data = $this->sanitizeInputArray($_GET);
        } elseif ($this->isPost()) {
            if ($this->isJsonRequest()) {
                $data = $this->getJsonBody();
            } else {
                $data = $this->sanitizeInputArray($_POST);
            }
        }

        return $data;
    }


    /**
     * Get the URL path without query parameters.
     */
    public function getUrl(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position !== false) {
            $path = substr($path, 0, $position);
        }

        return rtrim($path, '/') ?: '/';
    }

    /**
     * Get all headers as an associative array.
     */
    public function getHeaders(): array
    {
        return getallheaders() ?: [];
    }

    /**
     * Get specific header value.
     */
    public function getHeader(string $name): ?string
    {
        $headers = $this->getHeaders();
        return $headers[$name] ?? null;
    }


    /**
     * Check if the request is a JSON request.
     */
    public function isJsonRequest(): bool
    {
        $contentType = $this->getHeader('Content-Type');
        return strpos($contentType ?? '', 'application/json') !== false;
    }

    /**
     * Parse JSON body if present.
     */
    private function getJsonBody(): array
    {
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);

        return is_array($data) ? $data : [];
    }


    /**
     * Get query parameters from the URL.
     */
    public function getQueryParams(): array
    {
        return $this->sanitizeInputArray($_GET);
    }


    /**
     * Sanitize an array of inputs recursively.
     */
    private function sanitizeInputArray(array $input): array
    {
        $sanitized = [];
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeInputArray($value);
            } else {
                $sanitized[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $sanitized;
    }

}