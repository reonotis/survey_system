<x-user-app-layout>

    @push('scripts')
    @endpush

    <div class="custom-container py-4">
        <div class="contents-area mx-auto p-4" style="width: 400px;">

            <p class="support-msg">あなたのお名前を設定してください</p>
            <form method="POST" action="{{ route('user_name_setting_update') }}">
                @csrf

                <div class="item-row">
                    <div class="item-title"><x-input-label for="name" :value="__('Name')" /></div>
                    <div class="item-contents flex-start-center">
                        <x-input-text
                            type="text"
                            name="name"
                            id="name"
                            class="w-full"
                            :error="$errors->has('name')"
                            :value="old('name')"
                            placeholder="田中 太郎"
                        />
                    </div>
                </div>

                <div class="item-row flex-center-center">
                    <input type="submit" class="btn" value="送信">
                </div>
            </form>

        </div>

    </div>
</x-user-app-layout>
