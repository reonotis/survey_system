
@push('scripts')
    @vite('resources/js/user/form/register_item_setting.js')
@endpush


<div class="modal-background" id="item_add_modal" style="display: none;">
    <div class="modal-contents-area" style="width: 800px;">
        <div class="modal-close">×</div>

        <div class="modal-content">
            <form method="POST" action="{{ route('user_form_register_form_item', ['form_setting' => $form_setting->id]) }}">
                @csrf

                <div class="item-row">
                    <div class="item-title">項目種別</div>
                    <div class="item-contents">
                        @foreach($selectable_item_list as $item_type => $item_name)
                            <div class="item-type-item">
                                <input type="radio" id="new_item_type_{{ $item_type }}" name="new_item_type"
                                       value="{{ $item_type }}">
                                <label for="new_item_type_{{ $item_type }}">{{ $item_name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-title">必須項目</div>
                    <div class="item-contents">
                        <x-input-checkbox
                            name="required"
                            label="必須項目にする"
                            id="new_required"
                            value="1"
                        />
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-title">項目名</div>
                    <div class="item-contents flex-start-center">
                        <input type="text" id="item_title" name="item_title" class="input-box">
                    </div>
                </div>

                <div class="item-row" id="name_details_row" style="display: none;">
                    <div class="item-title">お名前詳細</div>
                    <div class="item-contents flex-start-center">
                        @include('user.form.components.name')
                    </div>
                </div>
                <div class="item-row" id="yomi_details_row" style="display: none;">
                    <div class="item-title">ヨミ詳細</div>
                    <div class="item-contents flex-start-center">
                        @include('user.form.components.kana')
                    </div>
                </div>
                <div class="item-row" id="email_details_row" style="display: none;">
                    <div class="item-title">メールアドレス詳細</div>
                    <div class="item-contents flex-start-center">
                        @include('user.form.components.email')
                    </div>
                </div>
                <div class="item-row" id="tel_details_row" style="display: none;">
                    <div class="item-title">電話番号詳細</div>
                    <div class="item-contents flex-start-center">
                        @include('user.form.components.tel')
                    </div>
                </div>
                <div class="item-row" id="gender_details_row" style="display: none;">
                    <div class="item-title">性別詳細</div>
                    <div class="item-contents flex-start-center">
                        @include('user.form.components.gender')
                    </div>
                </div>
                <div class="item-row" id="address_details_row" style="display: none;">
                    <div class="item-title">住所詳細</div>
                    <div class="item-contents flex-start-center">
                        @include('user.form.components.address')
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-contents flex-center-center">
                        <input type="submit" class="btn" value="登録"/>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
