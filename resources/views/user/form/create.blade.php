<x-user-app-layout>

    @push('scripts')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        <div class="flex-between-center gap-2">
            <div class="page-name">フォーム作成</div>
        </div>
    </x-slot>

    <div class="custom-container py-4">

        <div class="contents-box mt-4 p-8" style="width: 700px;">

            <form method="POST" action="{{ route('user_form_register') }}">
                @csrf

                <div class="item-row">
                    <div class="item-title">管理名</div>
                    <div class="item-contents">
                        <x-input-text name="form_name" class="w-full"/>

                        <div class="support-msg">
                            ※他フォーム等とタイトル名が重複する場合に、フォーム一覧画面でメンバーが分かりやすい名前を作成してください
                        </div>
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
