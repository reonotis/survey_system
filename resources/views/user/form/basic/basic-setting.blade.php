<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/mail_setting.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        <div class="flex-between-center gap-2">
            <div class="page-name">{{ $form_setting->title }} - 基本設定 </div>
            @include('layouts.user.form.form-navigation', ['number' => \App\Consts\UserConst::FORM_NAV_BASIC_SETTING])
        </div>
    </x-slot>

    <div class="custom-container py-4">
        @include('layouts.user.form.form-setting-navigation', ['number' => \App\Consts\UserConst::NAV_MANU_BASIC_SETTING])

        <div class="contents-area mx-auto p-4" style="width: 800px;">

            <form method="POST" action="">
                @csrf

                <div class="item-row">
                    <div class="item-title">フォームタイトル</div>
                    <div class="item-contents flex-start-center">
                        <x-input-text name="title" class="w-full"
                                      :error="$errors->has('title')"
                                      :value="old('title', $form_setting->title)"
                                      placeholder="title"/>
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-title">申込期間</div>
                    <div class="item-contents flex-start-center">
                        <div class="flex-start-center gap-2">
                            <x-input-text type="datetime-local" name="start_date"
                                          :error="$errors->has('start_date')"
                                          :value="old('start_date', $form_setting->start_date)"
                                          placeholder="start_date"/>
                            ～
                            <x-input-text type="datetime-local" name="end_date"
                                          :error="$errors->has('end_date')"
                                          :value="old('end_date', $form_setting->end_date)"
                                          placeholder="end_date"/>
                        </div>
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-title">状態</div>
                    <div class="item-contents flex-start-center">
                        <x-input-radio
                            name="publication_status"
                            :options="\App\Models\FormSetting::PUBLICATION_STATUS_LIST"
                            :checked="$form_setting->publication_status"
                        />
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-title">申込上限</div>
                    <div class="item-contents flex-start-center">
                        <x-input-text type="number" name="max_applications"
                                      :error="$errors->has('max_applications')"
                                      :value="old('max_applications', $form_setting->max_applications)"
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
