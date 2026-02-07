<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/mail_setting.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        <div class="flex-between-center gap-2">
            <div class="page-name">{{ $form_setting->title }} - メール設定 </div>
            @include('layouts.user.form.form-navigation', ['number' => \App\Consts\UserConst::FORM_NAV_BASIC_SETTING])
        </div>
    </x-slot>

    {{-- ぱんくず --}}
    <x-slot name="breadcrumbs">
        <ol class="custom-container">
            <li><a href="{{ route('user_dashboard') }}" class="anchor-link">ダッシュボード</a></li>
            <li><a href="{{ route('user_form_basic_setting', ['form_setting' => $form_setting->id]) }}"
                   class="anchor-link">{{ $form_setting->title }} - 基本設定</a></li>
            <li><a href="" class="anchor-link">メール設定</a></li>
        </ol>
    </x-slot>


    <div class="custom-container py-4">
        @include('layouts.user.form.form-setting-navigation', ['number' => \App\Consts\UserConst::NAV_MANU_MAIL_SETTING])

        <div class="mx-auto py-8" style="width: 800px;">

            <form method="POST" action="">
                @csrf

                <div class="item-row">
                    <div class="item-title">通知メール</div>
                    <div class="item-contents flex-start-center">
                        <x-input-radio
                            name="notification_mail_flg"
                            :options="\App\Consts\CommonConst::USE_TYPE_LIST"
                            :checked="old('notification_mail_flg', $form_setting->mailSetting->notification_mail_flg?? 0)"
                        />
                    </div>
                </div>
                <div class="item-row" id="notification_mail_address_row"
                     @if(is_null($form_setting->mailSetting) ||  $form_setting->mailSetting->notification_mail_flg <> 1) style="display:none;" @endif>
                    <div class="item-title">通知メール送信先</div>
                    <div class="item-contents flex-start-center">
                        <x-input-text name="notification_mail_address" class="w-full"
                                      :error="$errors->has('notification_mail_address')"
                                      :value="old('notification_mail_address', $form_setting->mailSetting->notification_mail_address?? '')"
                                      placeholder="応募がありました"/>
                    </div>
                </div>
                <div class="item-row" id="notification_mail_title_row"
                     @if(is_null($form_setting->mailSetting) || $form_setting->mailSetting->notification_mail_flg <> 1) style="display:none;" @endif>
                    <div class="item-title">通知メール題名</div>
                    <div class="item-contents flex-start-center">
                        <x-input-text name="notification_mail_title" class="w-full"
                                      :error="$errors->has('notification_mail_title')"
                                      :value="old('notification_mail_title', $form_setting->mailSetting->notification_mail_title?? '')"
                                      placeholder="応募がありました"/>
                    </div>
                </div>
                <div class="item-row" id="notification_mail_message_row"
                     @if(is_null($form_setting->mailSetting) ||  $form_setting->mailSetting->notification_mail_flg <> 1) style="display:none;" @endif>
                    <div class="item-title">通知メール文言</div>
                    <div class="item-contents flex-start-center">
                        <textarea name="notification_mail_message" id="" class="input-box w-full"
                                  rows="15">{{ $form_setting->mailSetting->notification_mail_message?? '' }}</textarea>
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-title">自動返信メール</div>
                    <div class="item-contents flex-start-center">
                        <x-input-radio
                            name="auto_reply_mail_flg"
                            :options="\App\Consts\CommonConst::USE_TYPE_LIST"
                            :checked="old('auto_reply_mail_flg', $form_setting->mailSetting->auto_reply_mail_flg?? 0)"
                        />
                    </div>
                </div>
                <div class="item-row" id="auto_reply_mail_title_row"
                     @if(is_null($form_setting->mailSetting) || $form_setting->mailSetting->auto_reply_mail_flg == 0) style="display:none;" @endif>
                    <div class="item-title">自動返信メール題名</div>
                    <div class="item-contents flex-start-center">
                        <x-input-text name="auto_reply_mail_title" class="w-full"
                                      :error="$errors->has('auto_reply_mail_title')"
                                      :value="old('auto_reply_mail_title', $form_setting->mailSetting->auto_reply_mail_title?? '')"
                                      placeholder="ご応募頂きありがとうございます"/>
                    </div>
                </div>
                <div class="item-row" id="auto_reply_mail_message_row"
                     @if(is_null($form_setting->mailSetting) || $form_setting->mailSetting->auto_reply_mail_flg == 0) style="display:none;" @endif>
                    <div class="item-title">自動返信メール文言</div>
                    <div class="item-contents flex-start-center">
                        <textarea name="auto_reply_mail_message" id="" class="input-box w-full"
                                  rows="15">{{ $form_setting->mailSetting->auto_reply_mail_message?? '' }}</textarea>
                    </div>
                </div>

                <div class="item-row flex-center-center">
                    <input type="submit" class="btn" value="更新"/>
                </div>
            </form>

        </div>
    </div>

</x-user-app-layout>




