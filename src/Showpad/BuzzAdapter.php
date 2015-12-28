<?php

namespace Showpad;

use Buzz\Browser;
use Buzz\Message\Form\FormUpload;

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
        // add query parameters to the url if needed
        if (isset($parameters['query']) && is_array($parameters['query'])) {
            $query = parse_url($url, PHP_URL_QUERY);

            $url .= ($query === null ? '?' : '&') . http_build_query($parameters['query']);
        }

        // make sure files are added as a form upload
        if ($method === 'POST') {
            foreach ($parameters as $key => $value) {
                if (is_string($value) && substr($value, 0, 1) == '@') {
                    $value = ltrim($value, '@');
                    $parameters[$key] = new FormUpload($value);
                }
            }
        }

        $response = $this->browser->submit($url, $parameters, $method, $headers);

        return json_decode($response->getContent(), true);
    }
}
