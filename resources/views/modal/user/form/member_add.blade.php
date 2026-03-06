<div class="modal-background" id="display_setting_modal" style="display: none;" data-open-on-load="{{ isset($openOnLoad) && $openOnLoad ? '1' : '0' }}">
    <div class="modal-contents-area" style="width: 500px;">
        <div class="modal-close">×</div>

        <div class="modal-content">

            <form method="POST" action="{{ route('user_form_member_invite', ['form_setting' => $form_setting->id]) }}">
                @csrf

                <div class="item-row">
                    <div class="item-title"><x-input-label for="email" :value="__('Email')" /></div>
                    <div class="item-contents">
                        <x-input-text name="email" id="email"
                                      type="email"
                                      class="w-full"
                                      placeholder="sample@sample.jp"
                                      :value="old('email')"
                                      :error="$errors->get('email')"
                                      :required="true"
                                      autocomplete="email"
                                        />

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        <p class="support-msg">※招待する方のメールアドレスを入力して下さい</p>
                    </div>
                </div>

                <div class="item-row flex-center-center">
                    <input type="submit" class="btn" value="招待"/>
                </div>
            </form>

        </div>
    </div>
</div>
