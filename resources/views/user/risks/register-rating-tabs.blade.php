<nav class="register-rating-tabs" aria-label="Rating filters; counts reflect this view before table filters">
    @foreach(['' => 'All risks', 'High' => 'High', 'Moderate' => 'Moderate', 'Low' => 'Low'] as $rating => $label)
    <button type="button" data-register-rating="{{ $rating }}" aria-pressed="{{ $rating === '' ? 'true' : 'false' }}" class="register-summary-card" data-tone="{{ $rating ?: 'all' }}">
        <span class="register-summary-label">{{ $label }}</span>
        <strong data-register-count="{{ $rating }}">{{ $rating === '' ? count($data) : $data->where('rank', $rating)->count() }}</strong>
    </button>
    @endforeach
</nav>
