<x-user-app-layout>

    @push('scripts')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        <div class="flex-between-center gap-2">
            <div class="page-name">{{ $form_setting->title }} - メンバー編集 </div>
            @include('layouts.user.form.form-navigation', ['number' => \App\Consts\UserConst::FORM_NAV_ITEM_MEMBER])
        </div>
    </x-slot>

    <div class="custom-container py-4">

        <div class="mx-auto py-8" style="width: 800px;">

            この機能は現在作成中です


        </div>
    </div>
</x-user-app-layout>
