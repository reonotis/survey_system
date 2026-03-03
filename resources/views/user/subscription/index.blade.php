<x-user-app-layout>

    @push('scripts')
    @endpush

    <div class="custom-container py-4">
        <div class="contents-area mx-auto p-4" style="width: 800px;">

            <div>現在は{{ $plan }}プランです</div>
            <div>
                @if($subscription && $subscription->ends_at)
                    {{ $subscription->ends_at->format('Y/m/d') }} までこのプランで利用可能です
                @endif
            </div>

            @if($plan == \App\Consts\PlanConst::FREE_PLAN )
                <div class="flex-center-center gap-4 my-4">
                    <form method="POST" action="{{ route('user_subscription_create') }}">
                        @csrf
                        <input type="hidden" name="price_id" value="price_1T6CBR4jtTtelMpe0LtkEhP4">
                        <button type="submit" class="btn">{{ \App\Consts\PlanConst::LITE_PLAN }}プラン申込</button>
                    </form>

                    <form method="POST" action="{{ route('user_subscription_create') }}">
                        @csrf
                        <input type="hidden" name="price_id" value="price_1T6CCV4jtTtelMpeblzVIlzH">
                        <button type="submit" class="btn">{{ \App\Consts\PlanConst::FULL_PLAN }}プラン申込</button>
                    </form>
                </div>
            @endif

            <div class="flex-center-center gap-4 my-4">
                @if($plan == \App\Consts\PlanConst::LITE_PLAN )
                    <form method="POST" action="{{ route('user_subscription_change') }}">
                        @csrf
                        <input type="hidden" name="price_id" value="price_1T6CCV4jtTtelMpeblzVIlzH">
                        <button type="submit" class="btn">{{ \App\Consts\PlanConst::FULL_PLAN }}プランに変更</button>
                    </form>
                @endif

                @if($plan == \App\Consts\PlanConst::FULL_PLAN )
                    <form method="POST" action="{{ route('user_subscription_change') }}">
                        @csrf
                        <input type="hidden" name="price_id" value="price_1T6CBR4jtTtelMpe0LtkEhP4">
                        <button type="submit" class="btn">{{ \App\Consts\PlanConst::LITE_PLAN }}プランに変更</button>
                    </form>
                @endif
            </div>

            @if($plan !==  \App\Consts\PlanConst::FREE_PLAN )
                <div>
                    <form method="POST" action="{{ route('user_subscription_unsubscribe') }}">
                        @csrf
                        <button type="submit" class="btn">サブスクを解約する</button>
                    </form>
                </div>
            @endif

            @if($my_user->stripe_id)
                <div class="mt-4 flex-end-center">
                    <a href="{{ route('user_subscription_billing') }}" class="anchor-link">
                        支払い情報/請求履歴 を確認する
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-user-app-layout>
