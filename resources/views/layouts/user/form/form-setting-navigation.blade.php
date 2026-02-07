
<div class="flex-between-center gap-4 mb-4">

    <div class="form-navigation">
        <a href="{{ route('user_form_basic_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_BASIC_SETTING) active @endif">基本設定</a>

        <a href="{{ route('user_form_message_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_MESSAGE_SETTING) active @endif">メッセージ設定</a>

        <a href="{{ route('user_form_mail_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_MAIL_SETTING) active @endif">メール設定</a>

        <a href="{{ route('user_form_design_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_DESIGN_SETTING) active @endif">デザイン設定</a>
    </div>

</div>















