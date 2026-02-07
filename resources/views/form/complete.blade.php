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

        @if($message && $message->complete_message)
            {!! nl2br($message->complete_message)?? '申し込みが完了しました' !!}
        @else
            申し込みが完了しました
        @endif

    </div>
</x-form-layout>
