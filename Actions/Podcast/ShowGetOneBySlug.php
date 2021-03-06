<?php

namespace App\Actions\Podcast;

use App\Lib\Slime\RestAction\ApiAction;
use App\Models\Podcasts\RadioShow;

class ShowGetOneBySlug extends ApiAction
{
    protected function performAction()
    {
        $this->payload = RadioShow::info()
            ->where('slug', $this->args['slug'])
            ->first();
    }
}