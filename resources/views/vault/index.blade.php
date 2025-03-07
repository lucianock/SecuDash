<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Vault Accesses</h2>
        <a href="{{ route('vault.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add New</a>

        <div class="mt-6  shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Host</th>
                        <th class="px-4 py-2">Username</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vaults as $vault)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $vault->name }}</td>
                            <td class="px-4 py-2">{{ $vault->type }}</td>
                            <td class="px-4 py-2">{{ $vault->host }}</td>
                            <td class="px-4 py-2">{{ $vault->username }}</td>
                            {{-- <td class="px-4 py-2 flex space-x-2">
                                <a href="{{ route('vault.edit', $vault) }}" class="text-blue-500">Edit</a>
                                <form action="{{ route('vault.destroy', $vault) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500">Delete</button>
                                </form>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
