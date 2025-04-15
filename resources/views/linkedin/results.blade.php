<x-layouts.app>

<div class="container">
    <h1 class="text-xl font-bold mb-4">Results for "{{ $keyword }}"</h1>

    @foreach ($results as $post)
        <div class="border-b py-2">
            <p>{{ $post['content'] }}</p>
            @if (!empty($post['url']))
                <a href="{{ $post['url'] }}" target="_blank" class="text-blue-500">View on LinkedIn</a>
            @endif
        </div>
    @endforeach

    <a href="{{ route('linkedin.index') }}" class="mt-4 inline-block text-blue-500">← Back to search</a>
</div>

</x-layouts.app>
