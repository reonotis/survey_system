<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/mail_setting.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        {{ $form_setting->title }} - 当選設定
    </x-slot>

    {{-- ぱんくず --}}
    <x-slot name="breadcrumbs">
        <ol class="custom-container">
            <li><a href="{{ route('user_dashboard') }}" class="anchor-link">ダッシュボード</a></li>
            <li><a href="{{ route('user_form_index') }}" class="anchor-link">応募フォーム一覧</a></li>
            <li><a href="{{ route('user_form_application_list', ['form_setting' => $form_setting->id]) }}"
                   class="anchor-link">{{ $form_setting->title }} - 応募者一覧</a></li>
            <li><a href="" class="anchor-link">当選設定</a></li>
        </ol>
    </x-slot>


    <div class="custom-container py-4">
        @include('layouts.user.form.navigation', ['number' => \App\Consts\UserConst::NAV_MANU_WINNING_SETTING])

        <div class="mx-auto py-8" style="width: 800px;">
            この機能は現在作成中です

        </div>
    </div>

</x-user-app-layout>




