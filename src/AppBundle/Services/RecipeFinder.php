<?php

namespace AppBundle\Services;

use Endroid\Twitter\Twitter;

class RecipeFinder
{
    /** @var  Twitter */
    private $twitter;

    public function __construct(Twitter $twitter)
    {
        $this->twitter = $twitter;
    }

    public function getRandom()
    {
        $timeline = $this->twitter->getTimeline([
            'screen_name' => 'sintagespareas',
            'count' => 200,
            'trim_user' => true,
            'exclude_replies' => true
        ]);
        $link = '';
        $title = '';

        if (is_array($timeline)) {
            $random = $timeline[rand(0, 200)];
            $url = $this->grabUrl('http://twitter.com/i/web/status/' . $random->id);
            if ($url) {
                $link = $this->expandUrl('http://' . $url);
                $title = $this->getTitle($link);
            }
        }

        return ['title' => $title, 'link' => $link];
    }

    private function expandUrl($url)
    {
        $response = get_headers($url, 1);
        if (array_key_exists('Location', $response)) {
            $location = $response['Location'];
            if (is_array($location)) {
                return $this->expandUrl($location[count($location) - 1]);
            } else {
                return $this->expandUrl($location);
            }
        }
        return $url;
    }

    private function getTitle($url)
    {
        $str = file_get_contents($url);
        if (strlen($str) > 0) {
            $str = trim(preg_replace('/\s+/', ' ', $str));
            preg_match('/\<title\>(.*)\<\/title\>/i', $str, $title);
            return $title[1];
        }
    }

    private function grabUrl($url)
    {
        $page = file_get_contents($url);
        preg_match('/display-url">(buff.ly\/[a-zA-Z0-9]*?)</', $page, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return null;
    }
}