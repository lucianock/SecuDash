<x-layouts.app>
    <div class="p-6 bg-neutral-900 min-h-screen">

        <!-- Live Refresh Toggle -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Server Statistics</h1>
            <button id="refreshToggle" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                Auto-Refresh: ON
            </button>
        </div>

        <!-- Header / Summary cards -->
        <div class="grid gap-6 md:grid-cols-4 mb-8">
            <!-- Uptime -->
            <div
                class="bg-neutral-800 rounded-2xl shadow-lg p-5 flex items-center space-x-4 hover:scale-105 transition-transform">
                <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z" />
                </svg>
                <div>
                    <p class="text-gray-400">Uptime</p>
                    <p id="cardUptime" class="text-xl font-semibold text-white">Loading...</p>
                </div>
            </div>
            <!-- Usuario -->
            <div
                class="bg-neutral-800 rounded-2xl shadow-lg p-5 flex items-center space-x-4 hover:scale-105 transition-transform">
                <svg class="w-8 h-8 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M14 7a4 4 0 11-8 0 4 4 0 018 0zM2 18a6 6 0 0112 0H2z" />
                </svg>
                <div>
                    <p class="text-gray-400">Usuario Actual</p>
                    <p id="cardUser" class="text-xl font-semibold text-white">Loading...</p>
                </div>
            </div>
            <!-- Procesos -->
            <div
                class="bg-neutral-800 rounded-2xl shadow-lg p-5 flex items-center space-x-4 hover:scale-105 transition-transform">
                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M2 11a1 1 0 011-1h3V7H3a1 1 0 110-2h3V3a1 1 0 112 0v2h3a1 1 0 110 2H9v3h3a1 1 0 110 2H9v3a1 1 0 11-2 0v-3H4a1 1 0 01-1-1z" />
                </svg>
                <div>
                    <p class="text-gray-400">Procesos</p>
                    <p id="cardProc" class="text-xl font-semibold text-white">Loading...</p>
                </div>
            </div>
            <!-- Servicios -->
            <div
                class="bg-neutral-800 rounded-2xl shadow-lg p-5 flex items-center space-x-4 hover:scale-105 transition-transform">
                <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2z" />
                </svg>
                <div>
                    <p class="text-gray-400">Servicios Activos</p>
                    <p id="cardServices" class="text-xl font-semibold text-white">Loading...</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid gap-6 md:grid-cols-3 mb-8">
            <!-- CPU Load Chart -->
            <div class="bg-neutral-800 rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                <h3 class="text-lg font-semibold text-white mb-4">Carga de CPU</h3>
                <canvas id="cpuChart"></canvas>
            </div>
            <!-- Memory Chart -->
            <div class="bg-neutral-800 rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                <h3 class="text-lg font-semibold text-white mb-4">Memoria RAM</h3>
                <canvas id="memChart"></canvas>
            </div>
            <!-- Disk Chart -->
            <div class="bg-neutral-800 rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                <h3 class="text-lg font-semibold text-white mb-4">Disco</h3>
                <canvas id="diskChart"></canvas>
            </div>
        </div>

        <!-- Detailed Info & Alert -->
        <div class="grid gap-6 md:grid-cols-2 mb-8">
            <div class="bg-neutral-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Información del Sistema</h3>
                <ul class="text-white space-y-2">
                    <li><strong>Hostname:</strong> <span id="sysHost"></span></li>
                    <li><strong>SO:</strong> <span id="sysOS"></span></li>
                    <li><strong>Kernel:</strong> <span id="sysKernel"></span></li>
                    <li><strong>Arquitectura:</strong> <span id="sysArch"></span></li>
                    <li><strong>IP Pública:</strong> <span id="sysIP"></span></li>
                </ul>
            </div>
            <div class="bg-neutral-800 rounded-2xl shadow-lg p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Uptime & Boot</h3>
                    <p class="text-white"><strong>Uptime:</strong> <span id="uptimeFull"></span></p>
                    <p class="text-white mt-2"><strong>Boot:</strong> <span id="bootTime"></span></p>
                </div>
                <div id="alertBox" class="mt-6 p-4 rounded-lg hidden">
                    <p id="alertText" class="text-sm font-medium"></p>
                </div>
            </div>
        </div>

        <!-- Processes & Network -->
        <div class="grid gap-6 md:grid-cols-2 mb-8">
            <!-- Processes Table -->
            <div class="bg-neutral-800 rounded-2xl shadow-lg p-6 overflow-auto">
                <h3 class="text-lg font-semibold text-white mb-4">Top Procesos</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-400">CPU</p>
                        <table class="min-w-full text-sm text-left text-white">
                            <thead>
                                <tr>
                                    <th class="py-1">PID</th>
                                    <th>%CPU</th>
                                </tr>
                            </thead>
                            <tbody id="tableCpuRows"></tbody>
                        </table>
                    </div>
                    <div>
                        <p class="text-gray-400">Memoria</p>
                        <table class="min-w-full text-sm text-left text-white">
                            <thead>
                                <tr>
                                    <th class="py-1">PID</th>
                                    <th>%MEM</th>
                                </tr>
                            </thead>
                            <tbody id="tableMemRows"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Network Chart -->
            <div class="bg-neutral-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Tráfico de Red</h3>
                <canvas id="netChart"></canvas>
            </div>
        </div>

        <!-- SSH Sessions -->
        <div class="bg-neutral-800 rounded-2xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-white mb-4">Sesiones SSH</h3>
            <pre id="sshSessions" class="text-sm text-white bg-neutral-700 p-4 rounded-lg max-h-40 overflow-auto"></pre>
        </div>
    </div>

    <!-- CDN Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/countup.js@2.6.2/dist/countUp.umd.js"></script>

    <script>
        // Esperar a que CountUp.js esté cargado
        function waitForCountUp(callback) {
            if (window.countUp) {
                callback();
            } else {
                setTimeout(() => waitForCountUp(callback), 50);
            }
        }

        waitForCountUp(() => {
            const { CountUp } = window.countUp;
            let autoRefresh = true;
            
            // Store chart instances
            let cpuChart = null;
            let memChart = null;
            let diskChart = null;
            let netChart = null;
            
            const refreshToggle = document.getElementById('refreshToggle');
            if (refreshToggle) {
                refreshToggle.addEventListener('click', () => {
                    autoRefresh = !autoRefresh;
                    refreshToggle.textContent = `Auto-Refresh: ${autoRefresh ? 'ON' : 'OFF'}`;
                });
                refreshToggle.textContent = `Auto-Refresh: ${autoRefresh ? 'ON' : 'OFF'}`;
            }

            async function cargarMetricas() {
                const alertBox = document.getElementById('alertBox');
                if (!alertBox) return; // Not the dashboard page, skip
                const { data: d } = await axios.get('/api/server-metrics');

                // Cards
                const cardUptime = document.getElementById('cardUptime');
                if (cardUptime) cardUptime.textContent = d.uptime;
                const cardUser = document.getElementById('cardUser');
                if (cardUser) cardUser.textContent = d.current_user;
                new CountUp('cardProc', d.processes.count, {
                    duration: 1
                }).start();
                new CountUp('cardServices', Object.values(d.services).filter(s => s === 'active').length, {
                    duration: 1
                }).start();

                // System Info
                const sysHost = document.getElementById('sysHost');
                if (sysHost) sysHost.textContent = d.system.hostname;
                const sysOS = document.getElementById('sysOS');
                if (sysOS) sysOS.textContent = d.system.os;
                const sysKernel = document.getElementById('sysKernel');
                if (sysKernel) sysKernel.textContent = d.system.kernel;
                const sysArch = document.getElementById('sysArch');
                if (sysArch) sysArch.textContent = d.system.architecture;
                const sysIP = document.getElementById('sysIP');
                if (sysIP) sysIP.textContent = d.public_ip;
                const uptimeFull = document.getElementById('uptimeFull');
                if (uptimeFull) uptimeFull.textContent = d.uptime;
                const bootTime = document.getElementById('bootTime');
                if (bootTime) bootTime.textContent = d.boot_time;

                // Alert
                if (d.cpu.total_usage_percent > 90) {
                    alertBox.classList.remove('hidden');
                    alertBox.classList.add('bg-red-700');
                    const alertText = document.getElementById('alertText');
                    if (alertText) alertText.textContent = '¡Atención! Uso de CPU muy alto.';
                } else {
                    alertBox.classList.add('hidden');
                }

                // Line Chart - CPU Load
                const cpuChartEl = document.getElementById('cpuChart');
                if (cpuChartEl) {
                    // Destroy previous chart instance if it exists
                    if (cpuChart) cpuChart.destroy();
                    cpuChart = new Chart(cpuChartEl, {
                        type: 'line',
                        data: {
                            labels: ['1m', '5m', '15m'],
                            datasets: [{
                                label: 'Load Avg',
                                data: d.cpu.load_avg,
                                fill: true,
                                tension: 0.4,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59,130,246,0.3)'
                            }]
                        },
                        options: {
                            responsive: true
                        }
                    });
                }

                // Doughnuts - RAM & Disco
                const memChartEl = document.getElementById('memChart');
                if (memChartEl) {
                    // Destroy previous chart instance if it exists
                    if (memChart) memChart.destroy();
                    memChart = new Chart(memChartEl, {
                        type: 'doughnut',
                        data: {
                            labels: ['Usada', 'Libre'],
                            datasets: [{
                                data: [d.memory.used_percent, 100 - d.memory.used_percent],
                                backgroundColor: ['#3b82f6', '#374151']
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#ddd'
                                    }
                                }
                            }
                        }
                    });
                }
                
                const diskChartEl = document.getElementById('diskChart');
                if (diskChartEl) {
                    // Destroy previous chart instance if it exists
                    if (diskChart) diskChart.destroy();
                    diskChart = new Chart(diskChartEl, {
                        type: 'doughnut',
                        data: {
                            labels: ['Usado', 'Libre'],
                            datasets: [{
                                data: [d.disk.used_percent, 100 - d.disk.used_percent],
                                backgroundColor: ['#ef4444', '#374151']
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#ddd'
                                    }
                                }
                            }
                        }
                    });
                }

                // Bar Chart - Network
                const labelsNet = Object.keys(d.network);
                const rx = labelsNet.map(i => d.network[i].rx_bytes);
                const tx = labelsNet.map(i => d.network[i].tx_bytes);
                const netChartEl = document.getElementById('netChart');
                if (netChartEl) {
                    // Destroy previous chart instance if it exists
                    if (netChart) netChart.destroy();
                    netChart = new Chart(netChartEl, {
                        type: 'bar',
                        data: {
                            labels: labelsNet,
                            datasets: [{
                                    label: 'RX',
                                    data: rx,
                                    backgroundColor: '#10b981'
                                },
                                {
                                    label: 'TX',
                                    data: tx,
                                    backgroundColor: '#f59e0b'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                // Tables - Procesos
                const cpuRows = document.getElementById('tableCpuRows');
                if (cpuRows) {
                    cpuRows.innerHTML = '';
                    d.processes.top_cpu.trim().split('\n').slice(1).forEach(l => {
                        const [pid, , pc] = l.trim().split(/\s+/);
                        cpuRows.innerHTML +=
                            `<tr class="hover:bg-neutral-700"><td class="py-1">${pid}</td><td>${pc}</td></tr>`;
                    });
                }
                const memRows = document.getElementById('tableMemRows');
                if (memRows) {
                    memRows.innerHTML = '';
                    d.processes.top_mem.trim().split('\n').slice(1).forEach(l => {
                        const [pid, , pm] = l.trim().split(/\s+/);
                        memRows.innerHTML +=
                            `<tr class="hover:bg-neutral-700"><td class="py-1">${pid}</td><td>${pm}</td></tr>`;
                    });
                }

                // SSH
                const sshSessions = document.getElementById('sshSessions');
                if (sshSessions) sshSessions.textContent = d.ssh_sessions;
            }

            cargarMetricas();
            setInterval(() => {
                if (autoRefresh) cargarMetricas();
            }, 5000);
        });
    </script>
</x-layouts.app> 