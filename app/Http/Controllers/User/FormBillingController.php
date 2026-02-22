<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Models\FormSetting;
use Illuminate\Http\Request;

class FormBillingController extends UserController
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request
     * @return mixed
     */
    public function checkout(FormSetting $form_setting, Request $request)
    {
        $user = $this->my_user;

        return $user->newSubscription(
                'form_' . $form_setting->id,
                config('services.stripe.form_price_id')
            )
            ->checkout([
                'success_url' => route('user_form_billing_checkout_success', ['form_setting' => $form_setting->id]),
                'cancel_url' => route('user_form_billing_checkout_cancel', ['form_setting' => $form_setting->id]),
                'subscription_data' => [
                    'metadata' => [
                        'user_id' => $user->id,
                        'form_setting_id' => $form_setting->id,
                    ],
                ],
            ]);
    }

    public function success(FormSetting $form_setting, Request $request)
    {
        return '決済を確認中です。数秒後に反映されます。';
    }

}
