<x-owner-app-layout>

    @push('scripts')
        @vite('resources/js/owner/form/item_setting.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        {{ $form_setting->title }} - 項目設定
    </x-slot>

    {{-- ぱんくず --}}
    <x-slot name="breadcrumbs">
        <ol class="custom-container">
            <li><a href="{{ route('owner_dashboard') }}" class="anchor-link">ダッシュボード</a></li>
            <li><a href="{{ route('owner_form_index') }}" class="anchor-link">応募フォーム一覧</a></li>
            <li><a href="{{ route('owner_form_application_list', ['form_setting' => $form_setting->id]) }}" class="anchor-link">{{ $form_setting->title }}</a></li>
            <li><a href="" class="anchor-link">項目設定</a></li>
        </ol>
    </x-slot>


    <div class="custom-container py-4">
        @include('layouts.owner.form.navigation', ['number' => \App\Consts\OwnerConst::NAV_MANU_FORM_ITEM_SETTING])

        <div class="mx-auto py-8">

            @if($selectable_item_list)
                <div class="flex-center-center mb-4">
                    <button type="button" id="item_add_btn" class="btn" >項目追加</button>
                </div>
            @endif

            <table class="list-tbl" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="sort-title">
                            <span>↑</span><span>↓</span>
                        </th>
                        <th style="width: 100px;">タイプ</th>
                        <th style="width: 50px;">必須</th>
                        <th style="width: 300px;">項目名</th>
                        <th style="flex: 1;">詳細設定</th>
                        <th style="flex: 1;">注釈文</th>
                        <th style="width: 60px;">更新</th>
                        <th style="width: 60px;">削除</th>
                    </tr>
                </thead>
                <tbody id="form-items-sortable">
                    @foreach($form_items as $form_item)
                        <tr>
                            <td>
                                <div class="flex-center-center">
                                    <span class="sort-handle"></span>
                                </div>
                                <input type="hidden" name="item_id[]" value="{{ $form_item->id }}">
                            </td>
                            <form method="POST" action="{{ route('owner_form_update_form_item', ['form_setting' => $form_setting->id, 'form_item' => $form_item->id]) }}">
                                @csrf
                                <td>
                                    <input type="hidden" name="item_type" value="{{ $form_item->item_type }}">
                                    {{ App\Models\FormItem::ITEM_TYPE_LIST[$form_item->item_type] }}
                                </td>
                                <td class="text-center">

                                    <div class="flex-center-center">
                                        <x-input-checkbox
                                            name="field_required"
                                            id="required"
                                            value="1"
                                            :checked="$form_item->field_required"
                                        />
                                    </div>

                                </td>
                                <td class="text-center">
                                    <input type="text" name="item_title" value="{{ $form_item->item_title }}" class="input-box w-full" >
                                </td>
                                <td>
                                    @switch($form_item->item_type)
                                        @case(App\Models\FormItem::ITEM_TYPE_NAME)
                                            @include('owner.form.components.name')
                                            @break
                                        @case(App\Models\FormItem::ITEM_TYPE_KANA)
                                            @include('owner.form.components.kana')
                                            @break
                                        @case(App\Models\FormItem::ITEM_TYPE_EMAIL)
                                            @include('owner.form.components.email')
                                            @break
                                        @case(App\Models\FormItem::ITEM_TYPE_TEL)
                                            @include('owner.form.components.tel')
                                            @break
                                        @case(App\Models\FormItem::ITEM_TYPE_GENDER)
                                            @include('owner.form.components.gender')
                                            @break
                                        @case(App\Models\FormItem::ITEM_TYPE_ADDRESS)
                                            @include('owner.form.components.address')
                                            @break
                                        @case(App\Models\FormItem::ITEM_TYPE_CHECKBOX)
                                            @include('owner.form.components.checkbox')
                                            @break
                                        @case(App\Models\FormItem::ITEM_TYPE_TERMS)
                                            @include('owner.form.components.terms')
                                            @break
                                        @default
                                            {{ $form_item->item_type }}
                                            @dump($errors)
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <textarea name="annotation_text" class="input-box w-full h-20">{{ $form_item->annotation_text }}</textarea>
                                </td>
                                <td class="text-center"><input type="submit" class="btn min" value="更新"></td>
                            </form>
                            <td>
                                <form action="{{ route('owner_form_delete_form_item', ['form_setting' => $form_setting->id, 'form_item' => $form_item->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="flex-center-center">
                                        <button type="submit" class="delete-btn">
                                            <img src="{{ asset('icon/delete.svg') }}" alt="削除"
                                                class="delete-icon mx-auto"
                                                style="cursor: pointer; width: 30px; height: 30px;"
                                                onclick="return confirm('削除しますか？')">
                                    </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div id="update_item_order_form" data-item-order-url="{{ route('owner_api_forms_update_item_order', ['form_setting' => $form_setting->id]) }}"></div>
        </div>
    </div>

    {{-- 項目追加時のモーダル --}}
    @include('modal.owner.form.item_add')
</x-owner-app-layout>
