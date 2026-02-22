<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/mail_setting.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        <div class="flex-between-center gap-2">
            <div class="page-name">{{ $form_setting->title }} - デザイン設定 </div>
            @include('layouts.user.form.form-navigation', ['number' => \App\Consts\UserConst::FORM_NAV_BASIC_SETTING])
        </div>
    </x-slot>


    <div class="custom-container py-4">
        @include('layouts.user.form.form-setting-navigation', ['number' => \App\Consts\UserConst::NAV_MANU_DESIGN_SETTING])

        <div class="contents-area mx-auto p-4" style="width: 900px;">
            <form method="POST" action="">
                @csrf
                <div class="item-row">
                    <div class="item-title">デザインタイプ</div>
                    <div class="item-contents flex-start-center">
                        <x-input-radio
                            name="design_type"
                            :options="\App\Consts\CommonConst::DESIGN_TYPE_LIST"
                            :checked="$form_setting->design_type"
                        />
                    </div>
                </div>

                <div class="item-row flex-center-center">
                    <input type="submit" class="btn" value="更新"/>
                </div>
            </form>
        </div>
    </div>

</x-user-app-layout>




