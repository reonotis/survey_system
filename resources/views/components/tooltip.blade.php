@props(['text'])

<div {{ $attributes->merge(['class' => 'tooltip js-tooltip']) }}>
    <button type="button" class="tooltip__trigger" aria-label="説明を表示">?</button>
    <div class="tooltip__content" role="tooltip">{{ $text }}</div>
</div>

