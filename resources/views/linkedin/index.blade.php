<x-layouts.app>

<div class="container">
    <h1 class="text-xl font-bold mb-4">Search LinkedIn Posts</h1>

    <form method="POST" action="{{ route('linkedin.search') }}">
        @csrf
        <input type="text" name="keyword" placeholder="Enter keyword..." required class="border p-2 rounded w-full mb-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
    </form>

    @if($errors->any())
        <div class="text-red-500 mt-2">{{ $errors->first() }}</div>
    @endif
</div>
</x-layouts.app>
