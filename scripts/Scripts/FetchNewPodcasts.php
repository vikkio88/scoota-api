<?php


namespace App\Scripts;


use App\Lib\Helpers\PodcastFeedImporter;
use App\Lib\Helpers\RadioFeedGateway;
use App\Lib\Parsers\Exceptions\InvalidFeedFormatException;
use App\Models\Podcasts\Podcast;
use App\Models\Podcasts\RadioShow;
use Mashtru\Libs\Interfaces\Job;

class FetchNewPodcasts implements Job
{

    public function fire(array $parameters = [])
    {
        $radioShows = RadioShow::all();
        $radioFeedGateway = new RadioFeedGateway();
        foreach ($radioShows as $radioShow) {
            $latestPodcast = Podcast::latestByShowId($radioShow->id)->first();
            $parsed = [];
            try {
                $parsed = $radioFeedGateway->getFullPodcastArrayFromFeed($radioShow->feed_url);
            } catch (InvalidFeedFormatException $e) {
                continue;
            }
            $feed = new PodcastFeedImporter($parsed);
            $podcasts = $feed->getPodcastsInfo($latestPodcast);
            foreach ($podcasts as $podcast) {
                $podcast->radio_show_id = $radioShow->id;
                $podcast->save();
            }
        }
        return true;
    }

    public function getName()
    {
        return "fetchNewPodcasts";
    }
}