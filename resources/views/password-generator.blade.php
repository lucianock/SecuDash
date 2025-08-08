<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-dark-bg to-dark-surface p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="card bg-dark-surface border-dark-border mb-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-500 to-blue-300 bg-clip-text text-transparent mb-2">
                        Advanced Password Generator
                    </h1>
                    <p class="text-dark-text-secondary">
                        Generate secure, customizable passwords with advanced options and strength analysis
                    </p>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Generator Form -->
                <div class="card bg-dark-surface border-dark-border">
                    <h2 class="text-xl font-semibold text-dark-text mb-6">Password Settings</h2>
                    
                    <form id="password-form" class="space-y-6">
                        <!-- Password Length -->
                        <div>
                            <label for="length" class="form-label">Password Length</label>
                            <input type="number" id="length" name="length" value="16" min="8" max="128"
                                class="form-input w-full">
                        </div>

                        <!-- Password Type -->
                        <div>
                            <label for="password_type" class="form-label">Password Type</label>
                            <select id="password_type" name="password_type" class="form-select w-full">
                                <option value="random">Random Characters</option>
                                <option value="pronounceable">Pronounceable</option>
                                <option value="memorable">Memorable</option>
                                <option value="passphrase">Passphrase</option>
                            </select>
                        </div>

                        <!-- Character Options -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="include_uppercase" name="include_uppercase" checked
                                    class="form-checkbox">
                                <label for="include_uppercase" class="ml-2 text-dark-text">Uppercase (A-Z)</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="include_lowercase" name="include_lowercase" checked
                                    class="form-checkbox">
                                <label for="include_lowercase" class="ml-2 text-dark-text">Lowercase (a-z)</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="include_numbers" name="include_numbers" checked
                                    class="form-checkbox">
                                <label for="include_numbers" class="ml-2 text-dark-text">Numbers (0-9)</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="include_symbols" name="include_symbols" checked
                                    class="form-checkbox">
                                <label for="include_symbols" class="ml-2 text-dark-text">Symbols (!@#$%^&*)</label>
                            </div>
                        </div>

                        <!-- Advanced Options -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="exclude_similar" name="exclude_similar"
                                    class="form-checkbox">
                                <label for="exclude_similar" class="ml-2 text-dark-text">Exclude Similar (l,1,I,0,O)</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="exclude_ambiguous" name="exclude_ambiguous"
                                    class="form-checkbox">
                                <label for="exclude_ambiguous" class="ml-2 text-dark-text">Exclude Ambiguous</label>
                            </div>
                        </div>

                        <!-- Custom Characters -->
                        <div>
                            <label for="custom_chars" class="form-label">Custom Characters (optional)</label>
                            <input type="text" id="custom_chars" name="custom_chars"
                                class="form-input w-full"
                                placeholder="e.g., @#$%^&*()_+-=">
                        </div>

                        <!-- Generate Button -->
                        <button type="submit" 
                            class="w-full btn-primary py-3 text-lg font-medium">
                            Generate Password
                        </button>
                    </form>
                </div>

                <!-- Results Section -->
                <div class="space-y-6">
                    <!-- Generated Password -->
                    <div id="generated-password" class="card bg-dark-surface border-dark-border hidden">
                        <h3 class="text-lg font-semibold text-dark-text mb-4">Generated Password</h3>
                        <div class="bg-dark-surface-2 p-4 rounded-lg border border-dark-border">
                            <p id="password" class="text-2xl font-mono text-dark-text text-center break-all"></p>
                        </div>
                        <div class="flex gap-2 mt-4">
                            <button id="copy-button" class="btn-primary flex-1">
                                Copy to Clipboard
                            </button>
                            <button id="regenerate-button" class="btn-secondary flex-1">
                                Regenerate
                            </button>
                        </div>
                    </div>

                    <!-- Password Strength -->
                    <div id="password-strength" class="card bg-dark-surface border-dark-border hidden">
                        <h3 class="text-lg font-semibold text-dark-text mb-4">Password Strength</h3>
                        <div class="space-y-4">
                            <!-- Strength Bar -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-dark-text-secondary">Strength:</span>
                                    <span id="strength-text" class="font-medium"></span>
                                </div>
                                <div class="w-full bg-dark-surface-2 rounded-full h-2">
                                    <div id="strength-bar" class="h-2 rounded-full transition-all duration-300"></div>
                                </div>
                            </div>

                            <!-- Strength Score -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <p class="text-dark-text-secondary text-sm">Score</p>
                                    <p id="strength-score" class="text-2xl font-bold text-dark-text">0/100</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-dark-text-secondary text-sm">Entropy</p>
                                    <p id="entropy" class="text-2xl font-bold text-dark-text">0 bits</p>
                                </div>
                            </div>

                            <!-- Feedback -->
                            <div id="strength-feedback" class="space-y-2">
                                <!-- Feedback items will be populated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Generation -->
                    <div class="card bg-dark-surface border-dark-border">
                        <h3 class="text-lg font-semibold text-dark-text mb-4">Bulk Generation</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="bulk-count" class="form-label">Number of Passwords</label>
                                <input type="number" id="bulk-count" name="bulk-count" value="10" min="1" max="100"
                                    class="form-input w-full">
                            </div>
                            <button id="bulk-generate" class="btn-secondary w-full">
                                Generate Multiple Passwords
                            </button>
                            <div id="bulk-results" class="hidden">
                                <h4 class="text-dark-text font-medium mb-2">Generated Passwords:</h4>
                                <div id="bulk-passwords" class="bg-dark-surface-2 p-4 rounded-lg border border-dark-border max-h-40 overflow-y-auto">
                                    <!-- Bulk passwords will be populated here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('password-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Convert checkboxes to boolean
            data.include_uppercase = document.getElementById('include_uppercase').checked;
            data.include_lowercase = document.getElementById('include_lowercase').checked;
            data.include_numbers = document.getElementById('include_numbers').checked;
            data.include_symbols = document.getElementById('include_symbols').checked;
            data.exclude_similar = document.getElementById('exclude_similar').checked;
            data.exclude_ambiguous = document.getElementById('exclude_ambiguous').checked;

            try {
                const response = await fetch("{{ route('generate-password') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                
                if (result.password) {
                    displayPassword(result);
                    displayStrength(result);
                } else {
                    alert('Error generating password: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while generating the password.');
            }
        });

        function displayPassword(result) {
            const passwordElement = document.getElementById('password');
            const generatedSection = document.getElementById('generated-password');
            
            passwordElement.textContent = result.password;
            generatedSection.classList.remove('hidden');
        }

        function displayStrength(result) {
            const strengthSection = document.getElementById('password-strength');
            const strengthText = document.getElementById('strength-text');
            const strengthBar = document.getElementById('strength-bar');
            const strengthScore = document.getElementById('strength-score');
            const entropy = document.getElementById('entropy');
            const feedback = document.getElementById('strength-feedback');

            // Update strength text and bar
            const strength = result.strength || 'weak';
            strengthText.textContent = strength.charAt(0).toUpperCase() + strength.slice(1);
            
            // Set bar color and width
            const colors = {
                weak: '#ef4444',
                fair: '#f59e0b',
                good: '#10b981',
                strong: '#3b82f6',
                excellent: '#8b5cf6'
            };
            
            const widths = {
                weak: '25%',
                fair: '50%',
                good: '75%',
                strong: '90%',
                excellent: '100%'
            };

            strengthBar.style.backgroundColor = colors[strength] || colors.weak;
            strengthBar.style.width = widths[strength] || widths.weak;

            // Update score and entropy
            strengthScore.textContent = (result.score || 0) + '/100';
            entropy.textContent = (result.entropy || 0) + ' bits';

            // Update feedback
            feedback.innerHTML = '';
            if (result.suggestions && result.suggestions.length > 0) {
                result.suggestions.forEach(suggestion => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center space-x-2';
                    div.innerHTML = `
                        <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-dark-text-secondary text-sm">${suggestion}</span>
                    `;
                    feedback.appendChild(div);
                });
            }

            strengthSection.classList.remove('hidden');
        }

        // Copy functionality
        document.getElementById('copy-button').addEventListener('click', function() {
            const password = document.getElementById('password').textContent;
            
            navigator.clipboard.writeText(password).then(function() {
                const button = document.getElementById('copy-button');
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.add('bg-green-600');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                }, 2000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                alert('Failed to copy password to clipboard');
            });
        });

        // Regenerate functionality
        document.getElementById('regenerate-button').addEventListener('click', function() {
            document.getElementById('password-form').dispatchEvent(new Event('submit'));
        });

        // Bulk generation
        document.getElementById('bulk-generate').addEventListener('click', async function() {
            const count = document.getElementById('bulk-count').value;
            const formData = new FormData(document.getElementById('password-form'));
            const data = Object.fromEntries(formData.entries());
            
            data.count = count;
            data.include_uppercase = document.getElementById('include_uppercase').checked;
            data.include_lowercase = document.getElementById('include_lowercase').checked;
            data.include_numbers = document.getElementById('include_numbers').checked;
            data.include_symbols = document.getElementById('include_symbols').checked;
            data.exclude_similar = document.getElementById('exclude_similar').checked;
            data.exclude_ambiguous = document.getElementById('exclude_ambiguous').checked;

            try {
                const response = await fetch("{{ route('generate-bulk-passwords') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                
                if (result.passwords) {
                    displayBulkPasswords(result.passwords);
                } else {
                    alert('Error generating bulk passwords: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while generating bulk passwords.');
            }
        });

        function displayBulkPasswords(passwords) {
            const bulkResults = document.getElementById('bulk-results');
            const bulkPasswords = document.getElementById('bulk-passwords');
            
            bulkPasswords.innerHTML = passwords.map((password, index) => 
                `<div class="flex justify-between items-center py-1 border-b border-dark-border last:border-b-0">
                    <span class="text-dark-text-secondary">${index + 1}.</span>
                    <span class="font-mono text-dark-text">${password}</span>
                    <button onclick="copyToClipboard('${password}')" class="text-blue-400 hover:text-blue-300 text-sm">
                        Copy
                    </button>
                </div>`
            ).join('');
            
            bulkResults.classList.remove('hidden');
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show feedback
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.add('text-green-400');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('text-green-400');
                }, 2000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                alert('Failed to copy password to clipboard');
            });
        }
    </script>
</x-layouts.app>
