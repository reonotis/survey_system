
<div class="flex-between-center gap-4">
    <div class="form-navigation">

        <a href="{{ route('user_form_basic_setting', ['form_setting' =>  $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::FORM_NAV_BASIC_SETTING) active @endif">基本設定</a>

        <a href="{{ route('user_form_item_setting', ['form_setting' => $form_setting->id]) }}"
           class="@if($number === \App\Consts\UserConst::FORM_NAV_ITEM_SETTING) active @endif">項目編集</a>

        <a href="" class="">ユーザー</a>

        <a href="{{ route('user_form_application_list', ['form_setting' => $form_setting->id]) }}"
           class="@if($number === 4) active @endif">回答</a>

    </div>
</div>
<div class="preview-btn-area">
    <a href="{{ route('user_form_preview', ['form_setting' => $form_setting->id]) }}" class="btn" target="_blank">プレビュー</a>
    <a href="{{ route('form_index', ['route_name' => $form_setting->route_name]) }}" class="btn" target="_blank">実際のフォームを確認する</a>
</div>
