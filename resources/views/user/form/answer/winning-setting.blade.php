<x-user-app-layout>

    @push('scripts')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        <div class="flex-between-center gap-2">
            <div class="page-name">{{ $form_setting->title }} - 当選設定 </div>
            @include('layouts.user.form.form-navigation', ['number' => \App\Consts\UserConst::FORM_NAV_ITEM_APPLICATION])
        </div>
    </x-slot>
    {{-- 画面名 --}}

    <div class="custom-container py-4">
        @include('layouts.user.form.navigation', ['number' => \App\Consts\UserConst::NAV_MANU_WINNING_SETTING])

        <div class="mx-auto py-8" style="width: 800px;">
                この機能は現在作成中です
{{--            @if($form_setting->isPaid())--}}
{{--            @else--}}
{{--                @include('layouts.user.form.plan-update')--}}
{{--            @endif--}}

        </div>
    </div>

</x-user-app-layout>




