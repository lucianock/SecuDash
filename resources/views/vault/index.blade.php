<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-dark-bg to-dark-surface p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="card bg-dark-surface border-dark-border mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-green-500 to-green-300 bg-clip-text text-transparent">
                            Secure Vault
                        </h1>
                        <p class="text-dark-text-secondary mt-2">
                            Manage your credentials securely with advanced encryption and organization
                        </p>
                    </div>
                    <a href="{{ route('vault.create') }}" class="btn-primary px-6 py-3">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Credential
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            @if(isset($stats))
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="card bg-dark-surface border-dark-border">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-500/10 rounded-xl">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-dark-text-secondary text-sm font-medium">Total Credentials</p>
                            <p class="text-2xl font-bold text-blue-400">{{ $stats['total_credentials'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="card bg-dark-surface border-dark-border">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-500/10 rounded-xl">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-dark-text-secondary text-sm font-medium">Recently Added</p>
                            <p class="text-2xl font-bold text-green-400">{{ $stats['recently_added'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="card bg-dark-surface border-dark-border">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-500/10 rounded-xl">
                            <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-dark-text-secondary text-sm font-medium">Favorites</p>
                            <p class="text-2xl font-bold text-yellow-400">{{ $stats['favorites'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="card bg-dark-surface border-dark-border">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-500/10 rounded-xl">
                            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-dark-text-secondary text-sm font-medium">Avg Security Score</p>
                            <p class="text-2xl font-bold text-purple-400">{{ round($stats['average_security_score'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Vault Table -->
            <div class="card bg-dark-surface border-dark-border">
                <div class="p-6 border-b border-dark-border">
                    <h2 class="text-xl font-semibold text-dark-text">Your Credentials</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-dark-border">
                                <th class="px-6 py-4 text-left text-dark-text font-semibold">Name</th>
                                <th class="px-6 py-4 text-left text-dark-text font-semibold">Type</th>
                                <th class="px-6 py-4 text-left text-dark-text font-semibold">Host</th>
                                <th class="px-6 py-4 text-left text-dark-text font-semibold">Username</th>
                                <th class="px-6 py-4 text-left text-dark-text font-semibold">Security Score</th>
                                <th class="px-6 py-4 text-left text-dark-text font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vaults as $vault)
                                <tr class="border-b border-dark-border hover:bg-dark-surface-2 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($vault['is_favorite'] ?? false)
                                                <svg class="w-4 h-4 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endif
                                            <span class="text-dark-text font-medium">{{ $vault['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-surface-2 text-dark-text">
                                            {{ $vault['type'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-dark-text-secondary font-mono text-sm">{{ $vault['host'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-dark-text">{{ $vault['username'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $score = $vault['security_score'] ?? 0;
                                            $colorClass = $score >= 80 ? 'bg-green-500/20 text-green-400' : 
                                                        ($score >= 60 ? 'bg-yellow-500/20 text-yellow-400' : 
                                                        ($score >= 40 ? 'bg-orange-500/20 text-orange-400' : 'bg-red-500/20 text-red-400'));
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                            {{ $score }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button onclick="viewVault({{ $vault['id'] }})" 
                                                class="text-blue-400 hover:text-blue-300 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button onclick="editVault({{ $vault['id'] }})" 
                                                class="text-green-400 hover:text-green-300 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button onclick="deleteVault({{ $vault['id'] }})" 
                                                class="text-red-400 hover:text-red-300 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewVault(id) {
            fetch(`/vault/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.vault) {
                        showVaultModal(data.vault);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function showVaultModal(vault) {
            const modal = `
                <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                    <div class="bg-dark-surface rounded-2xl p-6 max-w-md w-full mx-4 border border-dark-border">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-xl font-bold text-dark-text">${vault.name}</h2>
                            <button onclick="this.closest('.fixed').remove()" class="text-dark-text-secondary hover:text-dark-text">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="text-dark-text-secondary">Type:</span>
                                <span class="text-dark-text ml-2">${vault.type}</span>
                            </div>
                            <div>
                                <span class="text-dark-text-secondary">Host:</span>
                                <span class="text-dark-text ml-2 font-mono">${vault.host}</span>
                            </div>
                            <div>
                                <span class="text-dark-text-secondary">Username:</span>
                                <span class="text-dark-text ml-2">${vault.username}</span>
                            </div>
                            <div>
                                <span class="text-dark-text-secondary">Password:</span>
                                <span class="text-dark-text ml-2 font-mono">${vault.password}</span>
                            </div>
                            <div>
                                <span class="text-dark-text-secondary">Security Score:</span>
                                <span class="text-dark-text ml-2">${vault.security_score || 0}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modal);
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
                    } else {
                        alert('Error deleting credential: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the credential.');
                });
            }
        }
    </script>
</x-layouts.app>
