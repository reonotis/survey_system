<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Http\Requests\User\Plan\PlanStoreRequest;
use App\Service\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Cashier\Checkout;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Laravel\Cashier\Exceptions\SubscriptionUpdateFailure;

class SubscriptionController extends UserController
{
    private UserService $user_service;

    /**
     * コンストラクタ
     */
    public function __construct(UserService $user_service)
    {
        parent::__construct();

        $this->user_service = $user_service;
    }

    /**
     * プラン確認画面を表示する
     * @return View
     */
    public function index(): View
    {
        // 現在のプランを取得
        $subscription = $this->getSubscription();

        return view('user.subscription.index', [
            'plan' => $this->my_plan,
            'my_user' => $this->my_user,
            'subscription' => $subscription,
        ]);
    }

    /**
     * サブスクの決済画面を表示する
     * @param PlanStoreRequest $request
     * @return RedirectResponse|Checkout
     * @throws Exception
     */
    public function create(PlanStoreRequest $request): Checkout|RedirectResponse
    {
        $subscription = $this->getSubscription();
        if ($subscription) {
            return redirect()->back();
        }

        return $this->my_user->newSubscription('default', $request->price_id)
            ->checkout([
                'success_url' => route('user_subscription_success'),
                'cancel_url' => route('user_subscription_index'),
            ]);
    }

    /**
     * @return RedirectResponse
     */
    public function success(): RedirectResponse
    {
        // プラン変更日時を保存
        $this->user_service->update($this->my_user, [
            'last_plan_changed_at' => Carbon::now(),
        ]);

        return redirect()->route('user_subscription_index')->with('success', ['プランを登録しました']);
    }

    /**
     * プラン変更
     * @param PlanStoreRequest $request
     * @return RedirectResponse
     * @throws IncompletePayment
     * @throws SubscriptionUpdateFailure
     */
    public function change(PlanStoreRequest $request): RedirectResponse
    {
        $subscription = $this->getSubscription();
        // まだサブスクのプランが登録されていなければ変更は出来ないので戻す
        if (is_null($subscription)) {
            return redirect()->back()->with('error', ['プランが作成されていません']);
        }

        // 最近変更していたら戻す
        $three_days_ago = now()->subDays(2);
        if($this->my_user->last_plan_changed_at >= $three_days_ago){
            return redirect()->back()->with('error', ['前回プランを変更してから2日以上経過していない為変更できません。']);
        }

        // プラン変更
        $subscription->swap($request->price_id);

        // プラン変更日時を保存
        $this->user_service->update($this->my_user, [
            'last_plan_changed_at' => Carbon::now(),
        ]);
        return redirect()->back();
    }

    /**
     * サブスクの解約
     * @return RedirectResponse
     */
    public function unsubscribe(): RedirectResponse
    {
        $subscription = $this->getSubscription();
        if (is_null($subscription)) {
            return redirect()->back();
        }

        $subscription->cancel();

        return redirect()->route('user_subscription_index');
    }

    /**
     * Stripeの請求画面に遷移する
     * @return RedirectResponse
     */
    public function billing(): RedirectResponse
    {
        // 戻るボタンを押下した時の遷移先を指定
        return $this->my_user->redirectToBillingPortal(route('user_subscription_index'));
    }

}
