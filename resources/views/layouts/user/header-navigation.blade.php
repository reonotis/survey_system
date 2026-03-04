
@php

    $number = match(Route::currentRouteName()) {
        'user_dashboard' => 1,
        'user_mail_template_list',
        'user_mail_template_upsert' => 2,
        'user_contact' => 3,
        'user_subscription_index' => 4,
        default => 0,
    };

@endphp

<div class="flex-center-center gap-2">
    <a href="{{ route('user_dashboard') }}" class="btn @if($number === 1) active @endif">フォーム一覧</a>
    <a href="{{ route('user_mail_template_list') }}" class="btn @if($number === 2) active @endif">メールテンプレート</a>
    <a href="{{ route('user_contact') }}" class="btn @if($number === 3) active @endif">問い合わせ</a>
    <a href="{{ route('user_subscription_index') }}" class="btn @if($number === 4) active @endif">プラン確認</a>
</div>
