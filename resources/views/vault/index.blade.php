<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Vault Accesses</h2>
        <a href="{{ route('vault.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add New</a>

        <div class="mt-6 shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Host</th>
                        <th class="px-4 py-2">Username</th>
                        <th class="px-4 py-2">Security Score</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vaults as $vault)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $vault['name'] }}</td>
                            <td class="px-4 py-2">{{ $vault['type'] }}</td>
                            <td class="px-4 py-2">{{ $vault['host'] }}</td>
                            <td class="px-4 py-2">{{ $vault['username'] }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm 
                                    @if($vault['security_score'] >= 80) bg-green-100 text-green-800
                                    @elseif($vault['security_score'] >= 60) bg-yellow-100 text-yellow-800
                                    @elseif($vault['security_score'] >= 40) bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $vault['security_score'] }}
                                </span>
                            </td>
                            <td class="px-4 py-2 flex space-x-2">
                                <button onclick="viewVault({{ $vault['id'] }})" class="text-blue-500 hover:text-blue-700">View</button>
                                <button onclick="editVault({{ $vault['id'] }})" class="text-green-500 hover:text-green-700">Edit</button>
                                <button onclick="deleteVault({{ $vault['id'] }})" class="text-red-500 hover:text-red-700">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(isset($stats))
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Credentials</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['total_credentials'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Recently Added</h3>
                <p class="text-2xl font-bold text-green-600">{{ $stats['recently_added'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Favorites</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['favorites'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Avg Security Score</h3>
                <p class="text-2xl font-bold text-purple-600">{{ round($stats['average_security_score'] ?? 0) }}</p>
            </div>
        </div>
        @endif
    </div>

    <script>
        function viewVault(id) {
            fetch(`/vault/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.vault) {
                        alert(`Vault: ${data.vault.name}\nHost: ${data.vault.host}\nUsername: ${data.vault.username}\nPassword: ${data.vault.password}`);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function editVault(id) {
            // Implementar edición
            alert('Edit functionality coming soon!');
        }

        function deleteVault(id) {
            if (confirm('Are you sure you want to delete this credential?')) {
                fetch(`/vault/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</x-layouts.app>
