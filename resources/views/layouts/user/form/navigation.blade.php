
<div class="flex-between-center gap-4 mb-4">

    <div class="form-navigation">
        <a href="{{ route('user_form_application_list', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_APPLICATION_LIST) active @endif">応募者一覧</a>

        <a href="{{ route('user_form_analytics', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_ANALYTICS) active @endif">応募分析</a>

        <a href="{{ route('user_form_winning_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::NAV_MANU_WINNING_SETTING) active @endif">当選設定</a>

    </div>

</div>

