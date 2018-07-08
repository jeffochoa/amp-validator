<?php

namespace App;

use Zttp\Zttp;
use Illuminate\Support\Arr;

class Validator
{
    private $errors = [];
    protected $url;

    public static function make() : self
    {
        return new self();
    }

    public function errors() : ErrorsCollection
    {
        return new ErrorsCollection($this->errors);
    }

    public function recordErrors($errors)
    {
        foreach ($errors as $error) {
            $this->errors[] = new ValidationError($error);
        }
    }

    public function getErrorsFromResponse($response)
    {
        return Arr::wrap(Arr::get($response, 'errors'));
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function validate(string $url) : self
    {
        $this->url = $url;

        try {
            $response = Zttp::withOptions(['http_errors' => true])->get('https://amp.cloudflare.com/q/'.$this->sanitizeUrl($url));
        } catch (\Throwable $e) {
            $this->errors[] = new ValidationError(['code' => 'REQUEST_ERROR', 'error' => $e->getMessage()]);
            return $this;
        }
        $response = $response->json();
        if (!empty($response['errors'])) {
            $this->recordErrors($this->getErrorsFromResponse($response));
        }
        return $this;
    }

    public function hasErrors() : bool
    {
        return ! empty($this->errors);
    }

    public function hasError()
    {
    }

    protected function sanitizeUrl(string $url) : string
    {
        return preg_replace('(https?:\/\/)', '', $url);
    }
}
