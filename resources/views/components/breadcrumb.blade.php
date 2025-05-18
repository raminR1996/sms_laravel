<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $item)
            @if ($item['active'])
                <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>