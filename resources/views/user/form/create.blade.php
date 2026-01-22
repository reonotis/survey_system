<x-user-app-layout>

    @push('scripts')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        応募フォーム一覧
    </x-slot>

    {{-- ぱんくず --}}
    <x-slot name="breadcrumbs">
        <ol class="custom-container">
            <li><a href="{{ route('user_dashboard') }}" class="anchor-link">ダッシュボード</a></li>
            <li><a href="{{ route('user_form_create') }}" class="anchor-link">応募フォーム作成</a></li>
        </ol>
    </x-slot>


    <div class="custom-container py-4">

        <div class="contents-box mt-4 p-8" style="width: 700px;">

            <form method="POST" action="{{ route('user_form_register') }}">
                @csrf

                <div class="item-row">
                    <div class="item-title">管理名</div>
                    <div class="item-contents">
                        <x-input-text name="form_name" class="w-full"/>
                    </div>
                </div>
                <div class="item-row">
                    <div class="item-title">タイトル</div>
                    <div class="item-contents">
                        <x-input-text name="title" class="w-full"/>
                    </div>
                </div>

                <div class="item-row flex-center-center">
                    <button type="submit" class="btn">登録</button>
                </div>
            </form>
        </div>

    </div>
</x-user-app-layout>
