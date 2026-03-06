<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/member_list.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        <div class="flex-between-center gap-2">
            <div class="page-name">{{ $form_setting->title }} - メンバー編集 </div>
            @include('layouts.user.form.form-navigation', ['number' => \App\Consts\UserConst::FORM_NAV_ITEM_MEMBER])
        </div>
    </x-slot>

    <div class="custom-container py-4">

        @if($form_plan === \App\Consts\PlanConst::FREE_PLAN)
            このフォームのオーナーは {{ \App\Consts\PlanConst::FREE_PLAN }}プランの為、メンバーの招待を行う事はできません
        @else
            @if($can_invite_member)
                <div class="flex-end-center">
                    <button type="button" class="btn" id="add_btn">新しいメンバーを招待</button>
                </div>
            @endif

            <div class="mb-8">
                <table class="list-tbl" id="list_tbl"
                       data-url="{{ route('user_form_member_get_list', ['form_setting' => $form_setting->id]) }}"
                       data-delete-url="{{ route('user_form_member_delete', ['form_setting' => $form_setting->id, 'user' => '__ID__' ]) }}"
                       >
                </table>
            </div>

            {{-- メンバー追加時のモーダル --}}
            @include('modal.user.form.member_add', ['openOnLoad' => $errors->any()])
        @endif

    </div>

</x-user-app-layout>
