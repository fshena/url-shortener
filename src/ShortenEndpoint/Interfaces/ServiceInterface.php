<?php

namespace ShortenEndpoint\Interfaces;

interface ServiceInterface
{
    /**
     * Create a short version of the provided url using a third party service.
     *
     * @param $url      string
     *
     * @return mixed
     */
    public function shortenUrl(string $url): array;
}
