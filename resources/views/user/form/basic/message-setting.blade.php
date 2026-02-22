<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/message_setting.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        <div class="flex-between-center gap-2">
            <div class="page-name">{{ $form_setting->title }} - メッセージ設定 </div>
            @include('layouts.user.form.form-navigation', ['number' => \App\Consts\UserConst::FORM_NAV_BASIC_SETTING])
        </div>
    </x-slot>


    <div class="custom-container py-4">
        @include('layouts.user.form.form-setting-navigation', ['number' => \App\Consts\UserConst::NAV_MANU_MESSAGE_SETTING])

        <div class="contents-area mx-auto py-8" style="width: 900px;">

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

</x-user-app-layout>




