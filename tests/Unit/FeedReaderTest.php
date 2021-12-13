<?php

namespace Tests\Unit;

use App\Service\FeedReader;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class FeedReaderTest extends TestCase
{
    private function getTestRss() : string
    {
        // Todo: Figure out Fixture directory for test files.
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">
  <channel>
    <atom:link href="https://feeds.simplecast.com/Urk3897_" rel="self" title="MP3 Audio" type="application/atom+xml"/>
    <atom:link href="https://simplecast.superfeedr.com/" rel="hub" xmlns="http://www.w3.org/2005/Atom"/>
    <generator>https://simplecast.com</generator>
    <title>The Besties</title>
    <description>It&apos;s Game of the Year meets King of the Hill as four of Earth&apos;s best friends – Griffin McElroy, Justin McElroy, Chris Plante, and Russ Frushtick – rank and review their favorite video games. Because shouldn&apos;t the world&apos;s best friends pick the world&apos;s best games?</description>
    <copyright>2021</copyright>
    <language>en</language>
    <pubDate>Fri, 10 Dec 2021 07:00:00 +0000</pubDate>
    <lastBuildDate>Mon, 13 Dec 2021 11:38:35 +0000</lastBuildDate>
    <image>
      <link>http://besties.fan/</link>
      <title>The Besties</title>
      <url>https://image.simplecastcdn.com/images/c707e48c-0e5b-4dcd-a183-3a248de67b6e/7d2885c6-c8b8-4219-86b2-af9baf9846e9/3000x3000/uploads-2f1591280888546-ig2f96xefmo-c923c56fc6d3137dfa2f3f8b5ffc7a49-2fbesties-art-itunes.jpg?aid=rss_feed</url>
    </image>
    <link>http://besties.fan/</link>
    <itunes:type>episodic</itunes:type>
    <itunes:summary>It&apos;s Game of the Year meets King of the Hill as four of Earth&apos;s best friends – Griffin McElroy, Justin McElroy, Chris Plante, and Russ Frushtick – rank and review their favorite video games. Because shouldn&apos;t the world&apos;s best friends pick the world&apos;s best games?</itunes:summary>
    <itunes:author>Justin McElroy, Griffin McElroy, Chris Plante, Russ Frushtick</itunes:author>
    <itunes:explicit>yes</itunes:explicit>
    <itunes:image href="https://image.simplecastcdn.com/images/c707e48c-0e5b-4dcd-a183-3a248de67b6e/7d2885c6-c8b8-4219-86b2-af9baf9846e9/3000x3000/uploads-2f1591280888546-ig2f96xefmo-c923c56fc6d3137dfa2f3f8b5ffc7a49-2fbesties-art-itunes.jpg?aid=rss_feed"/>
    <itunes:new-feed-url>https://feeds.simplecast.com/Urk3897_</itunes:new-feed-url>
    <itunes:owner>
      <itunes:name>The Besties</itunes:name>
      <itunes:email>thebestiesbizemail@gmail.com</itunes:email>
    </itunes:owner>
    <itunes:category text="Leisure">
      <itunes:category text="Video Games"/>
    </itunes:category>
    <item>
      <guid isPermaLink="false">0d75c4eb-880b-4d2c-8af5-31f1fac4a4c2</guid>
      <title>The Grappling Hook Brings You Back to Halo Infinite</title>
      <description><![CDATA[<p>How much can a single device — one that's been present in video games as long as there's been video games — renovate the Halo formula? As it turns out: A whole bunch. Join us as we discuss Halo Infinite's campaign and multiplayer modes, and talk at length about where Chief's poop goes.</p><p>Games discussed: Halo Infinite, Loop Hero on Switch, Binding of Isaac (again), Final Fantasy 14: Endwalker (briefly)</p>
]]></description>
      <pubDate>Fri, 10 Dec 2021 07:00:00 +0000</pubDate>
      <author>thebestiesbizemail@gmail.com (Russ Frushtick, Griffin McElroy, Chris Plante)</author>
      <link>http://besties.fan/</link>
      <content:encoded><![CDATA[<p>How much can a single device — one that's been present in video games as long as there's been video games — renovate the Halo formula? As it turns out: A whole bunch. Join us as we discuss Halo Infinite's campaign and multiplayer modes, and talk at length about where Chief's poop goes.</p><p>Games discussed: Halo Infinite, Loop Hero on Switch, Binding of Isaac (again), Final Fantasy 14: Endwalker (briefly)</p>
]]></content:encoded>
      <enclosure length="42847352" type="audio/mpeg" url="https://cdn.simplecast.com/audio/c707e48c-0e5b-4dcd-a183-3a248de67b6e/episodes/a00429a6-f060-4550-9ce9-c6c5dc79e8a5/audio/3302d927-37d3-489d-8558-604a60dbeee4/default_tc.mp3?aid=rss_feed&amp;feed=Urk3897_"/>
      <itunes:title>The Grappling Hook Brings You Back to Halo Infinite</itunes:title>
      <itunes:author>Russ Frushtick, Griffin McElroy, Chris Plante</itunes:author>
      <itunes:duration>00:43:26</itunes:duration>
      <itunes:summary>How much can a single device — one that&apos;s been present in video games as long as there&apos;s been video games — renovate the Halo formula? As it turns out: A whole bunch. Join us as we discuss Halo Infinite&apos;s campaign and multiplayer modes, and talk at length about where Chief&apos;s poop goes.

Games discussed: Halo Infinite, Loop Hero on Switch, Binding of Isaac (again), Final Fantasy 14: Endwalker (briefly)</itunes:summary>
      <itunes:subtitle>How much can a single device — one that&apos;s been present in video games as long as there&apos;s been video games — renovate the Halo formula? As it turns out: A whole bunch. Join us as we discuss Halo Infinite&apos;s campaign and multiplayer modes, and talk at length about where Chief&apos;s poop goes.

Games discussed: Halo Infinite, Loop Hero on Switch, Binding of Isaac (again), Final Fantasy 14: Endwalker (briefly)</itunes:subtitle>
      <itunes:explicit>yes</itunes:explicit>
      <itunes:episodeType>full</itunes:episodeType>
      <itunes:episode>218</itunes:episode>
    </item>
  </channel>
</rss>
XML;
    }

    public function testRun()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $this->getTestRss()),
        ]);

        $handler_stack = HandlerStack::create($mock);

        $client = new Client(["handler" => $handler_stack]);

        $reader = new FeedReader($client);
        $result = $reader->setFeedUrl("/")->run();
        $decoded = json_decode($result,true);

        $this->assertTrue(json_last_error() === JSON_ERROR_NONE);
    }
}
