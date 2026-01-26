<x-form-layout :form_setting="$form_setting">

    @push('scripts')
        @if($form_setting->design_type === 1)
            @vite('resources/js/form/type_a.js')
        @elseif($form_setting->design_type === 2)
            @vite('resources/js/form/type_b.js')
        @elseif($form_setting->design_type === 3)
            @vite('resources/js/form/type_c.js')
        @endif
    @endpush

    <div class="custom-container py-8 mx-auto" style="width: 95%;max-width: 600px;">

        <div class="form-title">{{ $form_setting->title }}</div>

        <form method="post" action="">
            @csrf

            @foreach($form_setting->formItems as $form_item)

                <div class="item-row">
                    <div class="item-title @if($form_item->field_required) required @endif" >
                        {{-- 項目名が設定されていれば利用し、なければ基本の項目名を利用する --}}
                        @if($form_item->item_title)
                            {{ $form_item->item_title }}
                        @else
                            {{ App\Models\FormItem::ITEM_TYPE_LIST[$form_item->item_type] }}
                        @endif
                    </div>

                    <div class="item-content">
                        @switch($form_item->item_type)
                            @case(App\Models\FormItem::ITEM_TYPE_NAME)
                                @include('form.components.name')
                                @break
                            @case(App\Models\FormItem::ITEM_TYPE_KANA)
                                @include('form.components.kana')
                                @break
                            @case(App\Models\FormItem::ITEM_TYPE_EMAIL)
                                @include('form.components.email')
                                @break
                            @case(App\Models\FormItem::ITEM_TYPE_TEL)
                                @include('form.components.tel')
                                @break
                            @case(App\Models\FormItem::ITEM_TYPE_GENDER)
                                @include('form.components.gender')
                                @break
                            @case(App\Models\FormItem::ITEM_TYPE_ADDRESS)
                                @include('form.components.address')
                                @break
                            @case(App\Models\FormItem::ITEM_TYPE_CHECKBOX)
                                @include('form.components.checkbox', ['max_count' => $max_count])
                                @break
                            @case(App\Models\FormItem::ITEM_TYPE_RADIO)
                                @include('form.components.radio', ['max_count' => $max_count])
                                @break
                            @case(App\Models\FormItem::ITEM_TYPE_SELECT_BOX)
                                @include('form.components.select')
                                @break
                            @case(App\Models\FormItem::ITEM_TYPE_TERMS)
                                @include('form.components.terms', ['form_item' => $form_item])
                                @break
                            @case(App\Models\FormItem::ITEM_TYPE_PRECAUTIONS)
                                @include('form.components.precautions', ['form_item' => $form_item])
                                @break
                            @default
                                @break
                        @endswitch
                    </div>
                </div>
            @endforeach

            <div style="width: 100%; display: flex; justify-content: center;">
                <input type="submit" value="申込" class="btn">
            </div>
        </form>

    </div>
</x-form-layout>
