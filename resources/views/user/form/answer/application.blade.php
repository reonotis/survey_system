<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/form_application.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        <div class="flex-between-center gap-2">
            <div class="page-name">{{ $form_setting->title }} - 応募者一覧 </div>
            @include('layouts.user.form.form-navigation', ['number' => \App\Consts\UserConst::FORM_NAV_ITEM_APPLICATION])
        </div>
    </x-slot>

    <div class="custom-container py-4 ">
        @include('layouts.user.form.navigation', ['number' => \App\Consts\UserConst::NAV_MANU_APPLICATION_LIST])

        <div class="flex-between-center mb-4">
            <div class="flex-start-center gap-4">
                <form method="POST" action="{{ route('user_form_csv_download', ['form_setting' => $form_setting->id]) }}">
                    @csrf
                    <button type="submit" class="btn">CSVダウンロード</button>
                </form>
            </div>

            <button type="button" class="btn" id="item_add_btn">項目表示設定</button>
        </div>

        <div class="mb-8">
            <table
                class="list-tbl"
                id="application_list_tbl"
                data-url="{{ route('user_form_get_application_list', ['form_setting' => $form_setting->id]) }}"
                data-columns='@json($display_columns)'
            ></table>
        </div>
    </div>


    {{-- 項目追加時のモーダル --}}
    @include('modal.user.form.display_setting')

</x-user-app-layout>
