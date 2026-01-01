@php
    $confirm_type = $form_item->details['confirm_type'];
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div>
    <x-input-text
        type="email"
        name="email[{{ $form_item->id }}]"
        class="w-full"
        :error="$errors->has('email.' . $form_item->id)"
        :value="old('email.' . $form_item->id)"
        placeholder="sample@example.jp"
    />

    @if($confirm_type == 1)
        <x-input-text
            type="email"
            name="email_confirm[{{ $form_item->id }}]"
            class="w-full mt-1"
            :error="$errors->has('email_confirm.' . $form_item->id)"
            :value="old('email_confirm.' . $form_item->id)"
            placeholder="sample@example.jp（確認用）"
        />
    @endif

    <x-input-error :messages="$errors->get('email.' . $form_item->id)" class="mt-1"/>
    <x-input-error :messages="$errors->get('email_confirm.' . $form_item->id)" class="mt-1"/>
</div>
