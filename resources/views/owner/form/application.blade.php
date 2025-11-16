<x-owner-app-layout>

    @push('scripts')
        @vite('resources/js/owner/form/form_application.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        {{ $form_setting->title }} - 応募者一覧
    </x-slot>

    {{-- ぱんくず --}}
    <x-slot name="breadcrumbs">
        <ol class="custom-container">
            <li><a href="{{ route('owner_dashboard') }}" class="anchor-link">ダッシュボード</a></li>
            <li><a href="{{ route('owner_form_index') }}" class="anchor-link">応募フォーム一覧</a></li>
            <li><a href="" class="anchor-link">{{ $form_setting->title }}</a></li>
        </ol>
    </x-slot>


    <div class="custom-container py-4 ">
        @include('layouts.owner.form.navigation', ['number' => \App\Consts\OwnerConst::NAV_MANU_APPLICATION_LIST])

        <div class="flex-between-center mb-4">
            <div class="flex-start-center gap-4">
                <button type="button" class="btn" >CSVダウンロード</button>
            </div>

            <button type="button" class="btn" id="item_add_btn">項目表示設定</button>
        </div>

        <div class="mb-8">
            <table
                class="list-tbl"
                id="application_list_tbl"
                data-url="{{ route('owner_form_get_application_list', ['form_setting' => $form_setting->id]) }}"
                data-columns='@json($display_columns)'
            ></table>
        </div>
    </div>


        {{-- 項目追加時のモーダル --}}
        @include('modal.owner.form.display_setting')

</x-owner-app-layout>
