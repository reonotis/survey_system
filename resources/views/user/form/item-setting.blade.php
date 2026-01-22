<x-user-app-layout>

    @push('scripts')
        @viteReactRefresh
        @vite([
            'resources/scss/user/form/item_setting.scss',
            'resources/js/user/form/item_setting_react/ItemSettingReact.jsx'
        ])
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        {{ $form_setting->title }} - 項目設定
    </x-slot>

    {{-- ぱんくず --}}
    <x-slot name="breadcrumbs">
        <ol class="custom-container">
            <li><a href="{{ route('user_dashboard') }}" class="anchor-link">ダッシュボード</a></li>
            <li><a href="{{ route('user_form_index') }}" class="anchor-link">応募フォーム一覧</a></li>
            <li><a href="{{ route('user_form_basic_setting', ['form_setting' => $form_setting->id]) }}"
                   class="anchor-link">{{ $form_setting->title }} - 基本設定</a></li>
            <li><a href="" class="anchor-link">項目設定</a></li>
        </ol>
    </x-slot>


    <div class="custom-container py-4">
        @include('layouts.user.form.form-setting-navigation', ['number' => \App\Consts\UserConst::NAV_MANU_FORM_ITEM_SETTING])

        <div class="mx-auto py-8">

            <div class="flex-center-center mb-4 gap-4">
                <form method="POST"
                    action="{{ route('user_form_all_draft_delete', ['form_setting' => $form_setting->id]) }}">
                    @csrf
                    <input type="submit" class="btn" value="全ての項目を削除">
                </form>
                <form method="POST"
                      action="{{ route('user_form_reset_draft_item', ['form_setting' => $form_setting->id]) }}">
                    @csrf
                    <input type="submit" class="btn" value="編集内容をリセット">
                </form>
            </div>

            {{-- Reactコンポーネント用のコンテナ --}}
            <div id="react-item-setting-container"></div>

            <div class="flex-center-center mt-4 gap-4">
                <form method="POST" action="{{ route('user_form_save_form_items', ['form_setting' => $form_setting->id]) }}">
                    @csrf
                    <input type="submit" class="btn" value="編集した内容で更新する">
                </form>
            </div>

            <script>

                window.allFormItemList = @json($all_form_item_list);
                window.draftFormItems = @json($draft_form_items);
                window.upperLimitItemType = @json($upper_limit_item_type);
                window.itemTypeList = @json(\App\Models\FormItem::ITEM_TYPE_LIST);

                window.formSettingId = {{ $form_setting->id }};
                window.csrfToken = @json(csrf_token());

                window.commonConst = {
                    NAME_SEPARATE_LIST: @json(\App\Consts\CommonConst::NAME_SEPARATE_LIST),
                    KANA_SEPARATE_LIST: @json(\App\Consts\CommonConst::KANA_SEPARATE_LIST),
                    EMAIL_CONFIRM_LIST: @json(\App\Consts\CommonConst::EMAIL_CONFIRM_LIST),
                    TEL_HYPHEN_LIST: @json(\App\Consts\CommonConst::TEL_HYPHEN_LIST),
                    GENDER_LIST: @json(\App\Consts\CommonConst::GENDER_LIST),
                    POST_CODE_USE_LIST: @json(\App\Consts\CommonConst::POST_CODE_USE_LIST),
                    ADDRESS_SEPARATE_LIST: @json(\App\Consts\CommonConst::ADDRESS_SEPARATE_LIST),
                };

                window.draftAddItemUrl = @json(route('user_form_draft_add_item', ['form_setting' => $form_setting->id]));
                window.draftSortChangeUrl = @json(route('user_form_draft_sort_change', ['form_setting' => $form_setting->id]));
                window.draftItemSaveUrl = @json(route('user_form_draft_item_save', ['form_setting' => $form_setting->id]));
                window.draftItemDeleteUrl = @json(route('user_form_draft_item_delete', ['form_setting' => $form_setting->id]));

            </script>
        </div>
    </div>

</x-user-app-layout>
