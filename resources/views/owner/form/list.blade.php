<x-owner-app-layout>

    @push('scripts')
        @vite('resources/js/owner/form/form_list.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        応募フォーム一覧
    </x-slot>

    {{-- ぱんくず --}}
    <x-slot name="breadcrumbs">
        <ol class="custom-container">
            <li><a href="{{ route('owner_dashboard') }}" class="anchor-link">ダッシュボード</a></li>
            <li><a href="{{ route('owner_form_index') }}" class="anchor-link">応募フォーム一覧</a></li>
        </ol>
    </x-slot>


    <div class="custom-container py-4">

        <div class="flex-between-center mb-4">
            <div class="flex-start-center gap-4">
                <input type="text" name="title" id="title" class="input-box w-60" placeholder="タイトル"/>
                <input type="text" name="status" id="status" class="input-box w-60" placeholder="状態"/>

                <button type="button" class="btn">検索</button>
            </div>
        </div>

        <div class="mb-8">
            <table class="list-tbl" id="form_list_tbl" data-url="{{ route('owner_form_get_form_list') }}" data-show-url="{{ route('owner_form_application_list', ['form_setting' => '__ID__']) }}" ></table>
        </div>
    </div>
</x-owner-app-layout>
