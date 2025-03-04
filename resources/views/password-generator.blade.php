<x-layouts.app>
    <div class="max-w-xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Password Generator</h2>

        <form id="password-form" class="space-y-4">
            <div>
                <label for="length" class="block text-sm font-medium">Password Length</label>
                <input type="number" id="length" name="length" value="12" min="8" max="32"
                    class="w-full p-2 border rounded-md">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="include_symbols" name="include_symbols" class="form-checkbox mr-2">
                <label for="include_symbols" class="text-sm font-medium">Include Symbols</label>
            </div>
            <br>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md cursor-pointer">Generate
                Password</button>
        </form>

        <div id="generated-password" class="mt-6 p-4 bg-gray-100 border rounded-md hidden">
            <h3 class="font-medium">Generated Password:</h3>
            <p id="password" class="text-xl font-bold"></p>
            <button id="copy-button" type="button"
                class="bg-blue-500 text-white p-2 rounded-md cursor-pointer">Copy</button>
        </div>
    </div>

    <script>
        document.getElementById('password-form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const length = document.getElementById('length').value;
            const includeSymbols = document.getElementById('include_symbols').checked;

            const response = await fetch("{{ route('generate-password') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    length,
                    include_symbols: includeSymbols
                })
            });

            const data = await response.json();
            const password = data.password;

            document.getElementById('generated-password').classList.remove('hidden');
            document.getElementById('password').innerText = password;
        });

        document.getElementById('copy-button').addEventListener('click', function() {
            const password = document.getElementById('password').innerText;

            const textarea = document.createElement('textarea');
            textarea.value = password;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                alert("Contraseña copiada al portapapeles");
            } catch (err) {
                console.error("Error al copiar al portapapeles: ", err);
                alert("Hubo un error al copiar la contraseña.");
            }
            document.body.removeChild(textarea);
        });
    </script>
</x-layouts.app>
