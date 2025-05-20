namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ServerStatsController extends Controller
{
    public function index(): JsonResponse
    {
        $cpuLoad = sys_getloadavg(); // 1, 5, 15 min

        $memInfo = file_get_contents("/proc/meminfo");
        preg_match("/MemTotal:\\s+(\\d+)/", $memInfo, $total);
        preg_match("/MemAvailable:\\s+(\\d+)/", $memInfo, $available);

        $diskTotal = disk_total_space("/");
        $diskFree = disk_free_space("/");

        // Uptime del sistema
        $uptime = shell_exec("uptime -p");

        return response()->json([
            'cpu' => $cpuLoad,
            'memory' => [
                'total' => (int) $total[1],
                'available' => (int) $available[1],
            ],
            'disk' => [
                'total' => $diskTotal,
                'free' => $diskFree,
            ],
            'uptime' => trim($uptime)
        ]);
    }
}
