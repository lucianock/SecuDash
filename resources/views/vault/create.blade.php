<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Add New Access</h2>

        <form action="{{ route('vault.store') }}" method="POST" class="shadow-md rounded-lg p-6">
            @csrf
            <div class="mb-4">
                <label for="name" class="form-label text-sm font-medium">Name</label>
                <input type="text" name="name" id="name" class="form-control w-full p-2 border rounded-md"
                    required>
            </div>

            <div class="mb-4">
                <label for="type" class="form-label text-sm font-medium text-white">Type</label>
                <select name="type" id="type"
                    class="w-full p-2 border rounded-md bg-gray-800 text-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="cPanel">cPanel</option>
                    <option value="FTP">FTP</option>
                    <option value="WHM">WHM</option>
                </select>
            </div>
            <style>
                select option {
                    background-color: #262626;
                    color: white;
                }
            </style>

            <div class="mb-4">
                <label for="host" class="form-label text-sm font-medium">Host</label>
                <input type="text" name="host" id="host" class="form-control w-full p-2 border rounded-md"
                    required>
            </div>

            <div class="mb-4">
                <label for="username" class="form-label text-sm font-medium">Username</label>
                <input type="text" name="username" id="username" class="form-control w-full p-2 border rounded-md"
                    required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label text-sm font-medium">Password</label>
                <input type="password" name="password" id="password" class="form-control w-full p-2 border rounded-md"
                    required>
            </div>

            <div class="mb-4">
                <label for="notes" class="form-label text-sm font-medium">Notes</label>
                <textarea name="notes" id="notes" class="form-control w-full p-2 border rounded-md"></textarea>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Save</button>
        </form>
    </div>
</x-layouts.app>
