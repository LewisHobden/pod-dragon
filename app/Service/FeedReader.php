<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class FeedReader
{
    private Client $client;
    private string $feed_url;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run()
    {
        // Query the URL we were provided.
        $rss = $this->client->get(
            $this->feed_url,
            [
                RequestOptions::ALLOW_REDIRECTS => true,
                RequestOptions::HEADERS => [
                    "User-Agent" => "Pod-Dragon/v1"
                ]
            ]
        );

        // Decode the XML that came back.
        $body = (string)$rss->getBody();
        $xml = simplexml_load_string($body);

        // Convert it to a known model.

        // Insert the model into the database.

        // Return useful data on the new feed to the user.

        return json_encode($xml,true);
    }

    public function setFeedUrl(string $feed_url) : static
    {
        $this->feed_url = $feed_url;

        return $this;
    }
}
