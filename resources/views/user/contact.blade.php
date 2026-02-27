<x-user-app-layout>

    @push('scripts')
    @endpush

    <div class="custom-container py-4">
        <div class="contents-area mx-auto p-4" style="width: 800px;">

            <form method="POST" action="{{ route('user_contact_send') }}">
                @csrf

                <div class="item-row">
                    <div class="item-title">名前</div>
                    <div class="item-contents flex-start-center">
                        {{ Auth::guard('user')->user()->name }}
                    </div>
                </div>
                <div class="item-row">
                    <div class="item-title">メールアドレス</div>
                    <div class="item-contents flex-start-center">
                        {{ Auth::guard('user')->user()->email }}
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-title">問い合わせ内容</div>
                    <div class="item-contents flex-start-center">
                        <textarea name="message" class="input-box h-36 w-full"
                        placeholder="プランのアップグレードをするにはどうしたらいいですか？"></textarea>
                    </div>
                </div>

                <div class="item-row flex-center-center">
                    <input type="submit" class="btn" value="内容を送信">
                </div>
            </form>

            <div class="support-msg">
                問い合わせた内容は3営業日以内に担当者が確認します。<br>
                連絡がない場合は本問い合わせ画面より改めてお問い合わせください。<br>
            </div>

        </div>

    </div>
</x-user-app-layout>
