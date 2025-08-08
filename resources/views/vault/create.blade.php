<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4 text-white">Add New Credential</h2>

        <form id="vaultForm" class="shadow-md rounded-lg p-6 bg-neutral-800 border border-neutral-700">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-white mb-2">Name</label>
                    <input type="text" name="name" id="name" 
                        class="w-full p-3 border border-neutral-600 rounded-lg bg-neutral-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-white mb-2">Type</label>
                    <select name="type" id="type"
                        class="w-full p-3 border border-neutral-600 rounded-lg bg-neutral-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="website">Website Login</option>
                        <option value="server">Server Access</option>
                        <option value="database">Database Connection</option>
                        <option value="api">API Credentials</option>
                        <option value="email">Email Account</option>
                        <option value="application">Application Login</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="host" class="block text-sm font-medium text-white mb-2">Host/URL</label>
                    <input type="text" name="host" id="host" 
                        class="w-full p-3 border border-neutral-600 rounded-lg bg-neutral-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                </div>

                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-white mb-2">Username</label>
                    <input type="text" name="username" id="username" 
                        class="w-full p-3 border border-neutral-600 rounded-lg bg-neutral-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-white mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" 
                            class="w-full p-3 border border-neutral-600 rounded-lg bg-neutral-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-12"
                            required>
                        <button type="button" onclick="togglePassword()" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-neutral-400 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="security_level" class="block text-sm font-medium text-white mb-2">Security Level</label>
                    <select name="security_level" id="security_level"
                        class="w-full p-3 border border-neutral-600 rounded-lg bg-neutral-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Auto-detect</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-white mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full p-3 border border-neutral-600 rounded-lg bg-neutral-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_favorite" id="is_favorite" class="mr-2">
                    <span class="text-sm text-white">Mark as favorite</span>
                </label>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    Save Credential
                </button>
                <a href="{{ route('vault.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
        }

        document.getElementById('vaultForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                name: formData.get('name'),
                type: formData.get('type'),
                host: formData.get('host'),
                username: formData.get('username'),
                password: formData.get('password'),
                notes: formData.get('notes'),
                is_favorite: formData.get('is_favorite') === 'on',
                security_level: formData.get('security_level')
            };

            fetch('{{ route("vault.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Credential saved successfully!');
                    window.location.href = '{{ route("vault.index") }}';
                } else {
                    alert('Error saving credential: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving credential');
            });
        });
    </script>
</x-layouts.app>
