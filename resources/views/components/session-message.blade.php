<div class="session-message-area">
    @if (session('success'))
        @foreach (session('success') as $message)
            <div class="session-message success" data-message-index="{{ $loop->index }}">
                <span class="session-message__text">{{ $message }}</span>
                <button type="button" class="session-close">
                    <span>&times;</span>
                </button>
            </div>
        @endforeach
    @endif

    @if (session('error'))
        @foreach (session('error') as $message)
            <div class="session-message error" data-message-index="{{ $loop->index }}">
                <span>{{ $message }}</span>
                <button type="button" class="session-close">
                    <span>&times;</span>
                </button>
            </div>
        @endforeach
    @endif
</div>

