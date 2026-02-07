<x-form-layout :form_setting="$form_setting">

    @push('scripts')
        @if($form_setting->design_type === \App\Consts\CommonConst::DESIGN_TYPE_A)
            @vite('resources/js/form/type_a.js')
        @elseif($form_setting->design_type === \App\Consts\CommonConst::DESIGN_TYPE_B)
            @vite('resources/js/form/type_b.js')
        @elseif($form_setting->design_type === \App\Consts\CommonConst::DESIGN_TYPE_C)
            @vite('resources/js/form/type_c.js')
        @elseif($form_setting->design_type === \App\Consts\CommonConst::DESIGN_TYPE_D)
            @vite('resources/js/form/type_d.js')
        @endif
    @endpush

    <div class="custom-container py-8 mx-auto" style="width: 95%;max-width: 600px;">
        <div class="form-title">{{ $form_setting->title }}</div>

        @if($error_type === 1)
            {!! nl2br(($form_setting->message->outside_period_message)?? '現在申込は出来ません') !!}
        @elseif($error_type === 2)
            現在公開されていません
        @elseif($error_type === 3)
            申込上限に達しています
        @endif
    </div>

</x-form-layout>
