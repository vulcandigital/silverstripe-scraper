<?php

namespace Vulcan\Scraper\Engines;

use Psr\Http\Message\ResponseInterface;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use Sunra\PhpSimple\HtmlDomParser;

class ScraperEngine
{
    use Injectable, Configurable;

    private static $endpoint = false;

    private static $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36';

    /** @var  string */
    protected $html;

    /** @var ResponseInterface */
    protected $response;

    protected $guzzle;

    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client([
            'headers' => [
                'User-Agent'                => $this->config()->get('user_agent'),
                'Accept-Encoding'           => 'gzip, deflate',
                'Accept'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Connection'                => 'keep-alive',
                'Cache-Control'             => 'max-age=0',
                'Upgrade-Insecure-Requests' => '1'
            ],
            'verify'  => false
        ]);
    }

    public function fetch($query = [], $endpoint = null)
    {
        $endpoint = $endpoint ? $endpoint : $this->getEndpoint();

        $this->response = $this->guzzle->get($endpoint, [
            'query' => $query
        ]);

        if ($this->response->getStatusCode() !== 200) {
            throw new \RuntimeException(sprintf('%s: %s', $this->response->getStatusCode(), $this->response->getReasonPhrase()));
        }

        $this->parse();

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        $endpoint = $this->config()->get('endpoint');

        if (!$endpoint) {
            throw new \RuntimeException('You must define the endpoint config variable in ' . static::class);
        }

        return $endpoint;
    }

    /**
     * @return \simplehtmldom_1_5\simple_html_dom
     */
    public function getDomParser()
    {
        if (!$this->response) {
            throw new \RuntimeException('A response has not been fetched yet');
        }

        return HtmlDomParser::str_get_html((string)$this->response->getBody());
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function parse()
    {
        throw new \RuntimeException('You must implement a method to parse the response in ' . static::class);
    }
}
