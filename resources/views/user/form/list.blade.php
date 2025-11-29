<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/form_list.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        応募フォーム一覧
    </x-slot>

    {{-- ぱんくず --}}
    <x-slot name="breadcrumbs">
        <ol class="custom-container">
            <li><a href="{{ route('user_dashboard') }}" class="anchor-link">ダッシュボード</a></li>
            <li><a href="{{ route('user_form_index') }}" class="anchor-link">応募フォーム一覧</a></li>
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

        @if(!$is_client_domain)
            <div class="flex-between-center mb-4">
                <a href="{{ route('user_form_create') }}" class="btn">新規応募フォーム作成</a>
            </div>
        @endif

        <div class="mb-8">
            <table class="list-tbl" id="form_list_tbl" data-url="{{ route('user_form_get_form_list') }}" data-show-url="{{ route('user_form_application_list', ['form_setting' => '__ID__']) }}" ></table>
        </div>
    </div>
</x-user-app-layout>
