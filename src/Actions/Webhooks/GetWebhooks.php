<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\Webhooks;

use BernskioldMedia\LaravelCampaignMonitor\Enum\WebhookEvent;
use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Collection;

class GetWebhooks
{
    public function execute(string $listId): Collection
    {
        $response = CampaignMonitor::lists($listId)->get_webhooks();

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return collect($response->response)
            ->map(function (object $webhook) {
                return [
                    'id' => $webhook->WebhookID,
                    'url' => $webhook->Url,
                    'events' => collect($webhook->Events)
                        ->map(fn (string $event) => WebhookEvent::tryFrom($event)),
                    'status' => $webhook->Status,
                    'format' => $webhook->PayloadFormat,
                ];
            });
    }
}