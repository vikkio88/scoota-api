<?php


namespace App\Lib\Helpers;

use SimpleXMLElement;


/**
 * Class RadioFeedGateway
 * @package App\Lib\Helpers
 */
class RadioFeedGateway
{
    /**
     * @var int
     */
    protected $maxPodcast = 15;


    /**
     * @param $url
     * @return string
     */
    private function getRemoteFeedXml($url)
    {
        return file_get_contents($url);
    }

    /**
     * @param $xml
     * @return string
     */
    private function xmlToJson($xml)
    {
        $xml = simplexml_load_string($xml);
        return json_encode(new SimpleXMLElement($xml->asXML(), LIBXML_NOCDATA));
    }

    /**
     * @param $url
     * @return string
     * @internal param $args
     */
    public function getPodcastJsonFromFeed($url)
    {
        $feedXml = $this->getRemoteFeedXml($url);
        return $this->xmlToJson($feedXml);
    }

    /**
     * @param $url
     * @return string
     * @internal param $args
     */
    public function getPodcastArrayFromFeed($url)
    {
        $feedXml = $this->getRemoteFeedXml($url);
        return $this->xmlToArray($feedXml);
    }

    private function xmlToArray($feedXml)
    {
        $json = $this->xmlToJson($feedXml);
        return json_decode($json, true);
    }

}