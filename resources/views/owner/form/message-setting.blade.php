<x-owner-app-layout>

    @push('scripts')
        @vite('resources/js/owner/form/message_setting.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        {{ $form_setting->title }} - メッセージ設定
    </x-slot>

    {{-- ぱんくず --}}
    <x-slot name="breadcrumbs">
        <ol class="custom-container">
            <li><a href="{{ route('owner_dashboard') }}" class="anchor-link">ダッシュボード</a></li>
            <li><a href="{{ route('owner_form_index') }}" class="anchor-link">応募フォーム一覧</a></li>
            <li><a href="{{ route('owner_form_application_list', ['form_setting' => $form_setting->id]) }}"
                   class="anchor-link">{{ $form_setting->title }}</a></li>
            <li><a href="" class="anchor-link">メッセージ設定</a></li>
        </ol>
    </x-slot>


    <div class="custom-container py-4">
        @include('layouts.owner.form.navigation', ['number' => \App\Consts\OwnerConst::NAV_MANU_MESSAGE_SETTING])

        <div class="mx-auto py-8" style="width: 900px;">

            <form method="POST" action="">
                @csrf

                <div class="item-row">
                    <div class="item-title">申込期間外メッセージ</div>
                    <div class="item-contents flex-start-center">
                        <textarea name="outside_period_message" class="input-box h-36 w-full">{{ $form_setting->message->outside_period_message?? '' }}</textarea>
                    </div>
                </div>
                <div class="item-row">
                    <div class="item-title">申込完了後メッセージ</div>
                    <div class="item-contents flex-start-center">
                        <textarea name="complete_message" class="input-box h-36 w-full ">{{ $form_setting->message->complete_message?? '' }}</textarea>
                    </div>
                </div>
                <div class="item-row flex-center-center">
                    <input type="submit" class="btn" value="登録"/>
                </div>
            </form>

        </div>
    </div>

</x-owner-app-layout>




