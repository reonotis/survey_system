@php
    $details = json_decode($form_item->details ?? '{}', true);
    $post_code_use_type = $details['post_code_use_type'] ?? 0;
    $address_separate_type = $details['address_separate_type'] ?? 0;

    if($address_separate_type == App\Consts\CommonConst::ADDRESS_SEPARATE) {
        $on_keyup = "AjaxZip3.zip2addr('zip21','zip22','pref21','address21','street21');";
    } else {
        $on_keyup = "AjaxZip3.zip2addr('zip21','zip22','address','address');";
    }

@endphp

@push('scripts')
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
@endpush

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div>
    <div class="form-item-detail-content">
        <div class="">

            @if($post_code_use_type)
                <div class="flex-start-center gap-2 mb-1">
                    <x-input-text name="zip21" id="zip21" class="w-20"
                                  :value="old('zip21')"
                                  :error="$errors->get('zip21')"
                                  placeholder='100'
                    />
                    <div class="px-1">-</div>
                    <x-input-text name="zip22" id="zip22" class="w-20"
                                  :value="old('zip22')"
                                  :error="$errors->get('zip22')"
                                  onKeyUp="{{ $on_keyup }}"
                                  placeholder='0001'
                    />
                </div>
                <x-input-error :messages="$errors->get('zip21')" class="my-1"/>
                <x-input-error :messages="$errors->get('zip22')" class="my-1"/>
            @endif

            @if($address_separate_type == App\Consts\CommonConst::ADDRESS_SEPARATE)
                <div class="flex-start-center gap-2 mb-1">
                    <x-input-text name="pref21" id="pref21" class="w-48"
                                  :value="old('pref21')"
                                  :error="$errors->get('pref21')"
                                  placeholder='東京都'
                    />
                    <x-input-text name="address21" id="address21" class="w-48"
                                  :value="old('address21')"
                                  :error="$errors->get('address21')"
                                  placeholder='千代田区'
                    />
                </div>
                <x-input-error :messages="$errors->get('pref21')" class="my-1"/>
                <x-input-error :messages="$errors->get('address21')" class="my-1"/>
                <div>
                    <x-input-text name="street21" id="street21" class="w-full"
                                  :value="old('street21')"
                                  :error="$errors->get('street21')"
                                  placeholder='〇〇1-1-1マンション101号室'/>
                </div>
                <x-input-error :messages="$errors->get('street21')" class="my-1"/>
            @else
                <x-input-text name="address" class="w-full" :value="old('address')"
                              :error="$errors->get('address')"
                              placeholder="東京都新宿区〇〇1-1-1マンション101号室"/>
                <x-input-error :messages="$errors->get('address')" class="my-1"/>
            @endif
        </div>
    </div>
</div>
