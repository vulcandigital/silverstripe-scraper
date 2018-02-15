<?php

namespace Vulcan\Scraper\Traits;

use SilverStripe\Control\Director;

trait CliFriendlyOutput
{
    public function output($string, $paddingTop = 0, $paddingBottom = 0)
    {
        $breaker = (Director::is_cli()) ? PHP_EOL : "<br/>";
        $string = (Director::is_cli()) ? strip_tags($string) : $string;
        for ($i = 0; $i < $paddingTop; $i++) {
            echo $breaker;
        }

        echo $string . $breaker;

        for ($i = 0; $i < $paddingBottom; $i++) {
            echo $breaker;
        }

    }
}