
<div class="flex-between-center gap-4 mb-4">

    <div class="form-navigation">
        <a href="{{ route('user_form_application_list', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_APPLICATION_LIST) active @endif">応募者一覧</a>

        <a href="{{ route('user_form_analytics', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_ANALYTICS) active @endif">応募分析</a>

        <a href="{{ route('user_form_basic_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_BASIC_SETTING) active @endif">基本設定</a>

        <a href="{{ route('user_form_item_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_FORM_ITEM_SETTING) active @endif">項目設定</a>

        <a href="{{ route('user_form_message_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_MESSAGE_SETTING) active @endif">メッセージ設定</a>

        <a href="{{ route('user_form_mail_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_MAIL_SETTING) active @endif">メール設定</a>

        <a href="{{ route('user_form_winning_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_WINNING_SETTING) active @endif">当選設定</a>

        <a href="{{ route('user_form_design_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_DESIGN_SETTING) active @endif">デザイン設定</a>
    </div>

    <div class="">
        <a href="{{ route('form_index', ['route_name' => $form_setting->route_name]) }}" class="btn" target="_blank">申込フォームの画面を確認する</a>
        <a href="{{ route('form_index', ['route_name' => $form_setting->route_name]) }}" class="btn" target="_blank">実際の申込フォームに移動する</a>
    </div>
</div>















