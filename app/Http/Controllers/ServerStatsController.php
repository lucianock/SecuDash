<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ServerStatsController extends Controller
{
    public function index(): View
    {
        return view('server-stats.index');
    }

    public function getMetrics(): JsonResponse
    {
        // Generar datos ficticios realistas
        $data = $this->generateFakeServerData();
        
        return response()->json($data);
    }

    private function generateFakeServerData(): array
    {
        // Simular variaciones realistas en los datos
        $baseTime = time();
        $variation = sin($baseTime / 30) * 0.3 + cos($baseTime / 45) * 0.2; // Variación cíclica
        
        // CPU con variación realista
        $cpuUsage = 30 + ($variation * 40) + (rand(-5, 5)); // Entre 25-75%
        $cpuUsage = max(5, min(95, $cpuUsage)); // Limitar entre 5-95%
        
        // Load average con variación
        $load1 = 0.5 + ($variation * 2) + (rand(-0.2, 0.2));
        $load5 = 0.6 + ($variation * 1.8) + (rand(-0.15, 0.15));
        $load15 = 0.7 + ($variation * 1.6) + (rand(-0.1, 0.1));
        
        // Memoria con variación
        $memTotal = 16384; // 16GB
        $memUsed = $memTotal * (0.4 + ($variation * 0.3) + (rand(-0.05, 0.05)));
        $memUsed = max($memTotal * 0.1, min($memTotal * 0.9, $memUsed));
        $memUsedPercent = ($memUsed / $memTotal) * 100;
        
        // Disco con variación lenta
        $diskTotal = 1000000; // 1TB en MB
        $diskUsed = $diskTotal * (0.65 + (sin($baseTime / 3600) * 0.1)); // Cambio más lento
        $diskUsedPercent = ($diskUsed / $diskTotal) * 100;
        
        // Procesos con variación
        $processCount = 180 + rand(-20, 20);
        
        // Usuario actual (simular diferentes usuarios)
        $users = ['admin', 'developer', 'system', 'monitor', 'backup'];
        $currentUser = $users[array_rand($users)];
        
        // Uptime con incremento real
        $uptimeSeconds = 86400 + ($baseTime % 604800); // Entre 1-7 días
        $uptime = $this->formatUptime($uptimeSeconds);
        
        // Boot time
        $bootTime = date('Y-m-d H:i:s', $baseTime - $uptimeSeconds);
        
        // Servicios con estado variable
        $services = [
            'nginx' => rand(0, 1) ? 'active' : 'inactive',
            'mysql' => 'active',
            'redis' => rand(0, 1) ? 'active' : 'inactive',
            'docker' => 'active',
            'ssh' => 'active',
            'cron' => 'active',
            'postgresql' => rand(0, 1) ? 'active' : 'inactive',
            'elasticsearch' => rand(0, 1) ? 'active' : 'inactive'
        ];
        
        // Procesos top CPU y Memoria
        $topCpu = $this->generateTopProcesses('cpu');
        $topMem = $this->generateTopProcesses('memory');
        
        // Red con tráfico variable
        $network = [
            'eth0' => [
                'rx_bytes' => 1000000000 + rand(-100000000, 100000000),
                'tx_bytes' => 500000000 + rand(-50000000, 50000000)
            ],
            'wlan0' => [
                'rx_bytes' => 200000000 + rand(-20000000, 20000000),
                'tx_bytes' => 100000000 + rand(-10000000, 10000000)
            ]
        ];
        
        // SSH sessions
        $sshSessions = $this->generateSSHSessions();
        
        return [
            'uptime' => $uptime,
            'current_user' => $currentUser,
            'processes' => [
                'count' => $processCount,
                'top_cpu' => $topCpu,
                'top_mem' => $topMem
            ],
            'services' => $services,
            'cpu' => [
                'total_usage_percent' => $cpuUsage,
                'load_avg' => [$load1, $load5, $load15]
            ],
            'memory' => [
                'total_mb' => $memTotal,
                'used_mb' => $memUsed,
                'used_percent' => $memUsedPercent
            ],
            'disk' => [
                'total_mb' => $diskTotal,
                'used_mb' => $diskUsed,
                'used_percent' => $diskUsedPercent
            ],
            'system' => [
                'hostname' => 'server-' . substr(md5($baseTime), 0, 6),
                'os' => 'Ubuntu 22.04.3 LTS',
                'kernel' => '5.15.0-88-generic',
                'architecture' => 'x86_64'
            ],
            'public_ip' => '192.168.1.' . rand(100, 254),
            'boot_time' => $bootTime,
            'network' => $network,
            'ssh_sessions' => $sshSessions
        ];
    }
    
    private function formatUptime(int $seconds): string
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        if ($days > 0) {
            return "{$days}d {$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h {$minutes}m";
        } else {
            return "{$minutes}m";
        }
    }
    
    private function generateTopProcesses(string $type): string
    {
        $processes = [
            'nginx' => ['pid' => 1234, 'cpu' => 15, 'mem' => 8],
            'mysql' => ['pid' => 1235, 'cpu' => 12, 'mem' => 25],
            'php-fpm' => ['pid' => 1236, 'cpu' => 8, 'mem' => 12],
            'redis' => ['pid' => 1237, 'cpu' => 5, 'mem' => 6],
            'docker' => ['pid' => 1238, 'cpu' => 3, 'mem' => 15],
            'systemd' => ['pid' => 1239, 'cpu' => 2, 'mem' => 4],
            'cron' => ['pid' => 1240, 'cpu' => 1, 'mem' => 2],
            'sshd' => ['pid' => 1241, 'cpu' => 1, 'mem' => 3]
        ];
        
        // Agregar variación
        foreach ($processes as &$process) {
            $process['cpu'] += rand(-2, 2);
            $process['mem'] += rand(-1, 1);
            $process['cpu'] = max(0, $process['cpu']);
            $process['mem'] = max(0, $process['mem']);
        }
        
        // Ordenar por CPU o memoria
        if ($type === 'cpu') {
            uasort($processes, function($a, $b) {
                return $b['cpu'] <=> $a['cpu'];
            });
        } else {
            uasort($processes, function($a, $b) {
                return $b['mem'] <=> $a['mem'];
            });
        }
        
        // Generar salida similar a top
        $output = "  PID %CPU %MEM COMMAND\n";
        $count = 0;
        foreach ($processes as $name => $process) {
            if ($count >= 5) break;
            $output .= sprintf("  %d  %.1f  %.1f %s\n", 
                $process['pid'], 
                $process['cpu'], 
                $process['mem'], 
                $name
            );
            $count++;
        }
        
        return $output;
    }
    
    private function generateSSHSessions(): string
    {
        $sessions = [];
        $users = ['admin', 'developer', 'deploy', 'monitor'];
        $ips = ['192.168.1.100', '10.0.0.50', '172.16.0.25', '203.0.113.10'];
        
        $sessionCount = rand(1, 4);
        
        for ($i = 0; $i < $sessionCount; $i++) {
            $user = $users[array_rand($users)];
            $ip = $ips[array_rand($ips)];
            $startTime = date('H:i', time() - rand(300, 3600)); // Entre 5 min y 1 hora
            $sessions[] = "{$user}@{$ip} - started {$startTime}";
        }
        
        return implode("\n", $sessions);
    }
}
