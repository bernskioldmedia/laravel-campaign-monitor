<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\Lists;

use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;

use function config;

class CreateList
{
    public function execute(array $data): string
    {
        $response = CampaignMonitor::lists()->create(
            config('campaign-monitor.clientId'),
            $data
        );

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        // Return the list ID.
        return $response->response;
    }
}
