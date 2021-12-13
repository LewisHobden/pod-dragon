<?php

namespace App\Http\Controllers;

use App\Service\FeedReader;
use Illuminate\Http\Request;

class FeedController extends \Illuminate\Routing\Controller
{
    private FeedReader $feed_reader;

    public function __construct(FeedReader $feed_reader)
    {
        $this->feed_reader = $feed_reader;
    }

    public function addFeed(Request $request)
    {
        $feed_url = $request->input("feedUrl");
        return $this->feed_reader->setFeedUrl($feed_url)->run();
    }
}
