<?php

namespace App\Listeners;

use App\Models\FormSubscription;
use Carbon\Carbon;
use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class SyncFormSubscriptionFromStripe
{
    public function handle(WebhookReceived $event)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $event->payload;

        // デバッグ用（重要）
        Log::debug('Stripe Webhook Received', $payload);

        $type = $payload['type'] ?? null;


        if (!in_array($type, [
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted',
        ])) {
            return;
        }

        $sub = $payload['data']['object'];

        $stripe_sub_id = $sub['id'] ?? null;

        if (!$stripe_sub_id) {
            return;
        }

        $status = $sub['status'] ?? null;

        $current_period_end = null;

        if (!empty($sub['current_period_end'])) {
            $current_period_end = Carbon::createFromTimestamp(
                $sub['current_period_end']
            );
        } elseif (!empty($sub['items']['data'][0]['current_period_end'])) {
            // ② items 側にある場合の保険
            $current_period_end = Carbon::createFromTimestamp(
                $sub['items']['data'][0]['current_period_end']
            );
        }

        if (!empty($sub['latest_invoice'])) {
            $invoice = \Stripe\Invoice::retrieve($sub['latest_invoice']);

            if (!empty($invoice->lines->data[0]->period->end)) {
                $current_period_end = Carbon::createFromTimestamp(
                    $invoice->lines->data[0]->period->end
                );
            }
        }

        // user_id / form_setting_id は metadata から取得する
        $metadata = $sub['metadata'] ?? [];

        if (empty($metadata['user_id']) || empty($metadata['form_setting_id'])) {
            Log::warning('Stripe metadata missing', $metadata);
            return;
        }

        FormSubscription::updateOrCreate(
            [
                'stripe_subscription_id' => $stripe_sub_id,
            ],
            [
                'user_id' => $metadata['user_id'],
                'form_setting_id' => $metadata['form_setting_id'],
                'status' => $status,
                'current_period_end' => $current_period_end,
            ]
        );
    }
}
