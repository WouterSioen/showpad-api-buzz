<?php

namespace Showpad;

use Buzz\Browser;

final class BuzzAdapter implements Adapter
{
    /**
     * @var Browser
     */
    private $browser;

    /**
     * Constructor
     *
     * @param Browser $browser A Buzz browser instance
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * Send an http request
     *
     * @param string $method     The HTTP method
     * @param string $url        The url to send the request to
     * @param array  $parameters The parameters for the request (assoc array)
     * @param array  $headers    The headers for the request (assoc array)
     *
     * return mixed
     */
    public function request($method, $url, array $parameters = null, array $headers = null)
    {
        $response = $this->browser->submit($url, $parameters, $method, $headers);

        return json_decode($response->getContent(), true);
    }
}
