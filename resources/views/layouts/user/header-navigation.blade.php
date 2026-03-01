
@php
    $route_name = Route::currentRouteName();
@endphp

<div class="flex-center-center gap-2">
    <a href="{{ route('user_dashboard') }}" class="btn @if($route_name === 'user_dashboard') active @endif">フォーム一覧</a>
    <a href="{{ route('user_mail_template_list') }}" class="btn @if($route_name === 'user_mail_template_list') active @endif">メールテンプレート</a>
    <a href="{{ route('user_contact') }}" class="btn @if($route_name === 'user_contact') active @endif">問い合わせ</a>
</div>
