<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vault;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class VaultController extends Controller
{
    public function index(): View
    {
        $vaults = Vault::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($vault) {
                try {
                    $decryptedPassword = Crypt::decryptString($vault->password);
                    $maskedPassword = $this->maskPassword($decryptedPassword);
                } catch (\Exception $e) {
                    // Si no se puede desencriptar, mostrar como no disponible
                    $maskedPassword = '*** (encrypted) ***';
                }

                return [
                    'id' => $vault->id,
                    'name' => $vault->name,
                    'type' => $vault->type,
                    'host' => $vault->host,
                    'username' => $vault->username,
                    'password' => $maskedPassword,
                    'notes' => $vault->notes,
                    'created_at' => $vault->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $vault->updated_at->format('Y-m-d H:i:s'),
                    'security_score' => $this->calculateSecurityScore($vault),
                    'last_accessed' => $vault->last_accessed ?? 'Never',
                    'access_count' => $vault->access_count ?? 0,
                    'is_favorite' => $vault->is_favorite ?? false,
                    'tags' => $vault->tags ? json_decode($vault->tags, true) : []
                ];
            });

        $stats = $this->generateVaultStats();
        
        return view('vault.index', compact('vaults', 'stats'));
    }

    public function create(): View
    {
        $categories = $this->getCategories();
        $templates = $this->getTemplates();
        
        return view('vault.create', compact('categories', 'templates'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:website,server,database,api,email,application,other',
            'host' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'tags' => 'nullable|array',
            'is_favorite' => 'boolean',
            'auto_login' => 'boolean',
            'expires_at' => 'nullable|date|after:today',
            'security_level' => 'nullable|string|in:low,medium,high,critical'
        ]);

        // Guardar la contraseña original para el cálculo de seguridad
        $originalPassword = $validated['password'];
        
        // Encriptar la contraseña
        $validated['password'] = Crypt::encryptString($validated['password']);
        $validated['user_id'] = Auth::id();
        $validated['tags'] = json_encode($validated['tags'] ?? []);
        $validated['security_level'] = $validated['security_level'] ?? $this->assessSecurityLevel($originalPassword);

        $vault = Vault::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Credential added successfully!',
            'vault' => [
                'id' => $vault->id,
                'name' => $vault->name,
                'type' => $vault->type,
                'host' => $vault->host,
                'username' => $vault->username,
                'password' => $this->maskPassword($originalPassword),
                'security_score' => $this->calculateSecurityScore($vault)
            ]
        ]);
    }

    public function show(Vault $vault): JsonResponse
    {
        $this->authorize('view', $vault);

        // Registrar acceso
        $vault->increment('access_count');
        $vault->update(['last_accessed' => now()]);

        try {
            $decryptedPassword = Crypt::decryptString($vault->password);
        } catch (\Exception $e) {
            $decryptedPassword = '*** (encrypted) ***';
        }

        return response()->json([
            'vault' => [
                'id' => $vault->id,
                'name' => $vault->name,
                'type' => $vault->type,
                'host' => $vault->host,
                'username' => $vault->username,
                'password' => $decryptedPassword,
                'notes' => $vault->notes,
                'tags' => json_decode($vault->tags, true) ?? [],
                'security_score' => $this->calculateSecurityScore($vault),
                'created_at' => $vault->created_at->format('Y-m-d H:i:s'),
                'last_accessed' => $vault->last_accessed?->format('Y-m-d H:i:s') ?? 'Never',
                'access_count' => $vault->access_count ?? 0
            ]
        ]);
    }

    public function update(Request $request, Vault $vault): JsonResponse
    {
        $this->authorize('update', $vault);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:website,server,database,api,email,application,other',
            'host' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'tags' => 'nullable|array',
            'is_favorite' => 'boolean',
            'expires_at' => 'nullable|date|after:today',
            'security_level' => 'nullable|string|in:low,medium,high,critical'
        ]);

        // Guardar la contraseña original para el cálculo de seguridad
        $originalPassword = $validated['password'];
        
        // Encriptar la contraseña si ha cambiado
        if ($originalPassword !== Crypt::decryptString($vault->password)) {
            $validated['password'] = Crypt::encryptString($originalPassword);
        }

        $validated['tags'] = json_encode($validated['tags'] ?? []);
        $validated['security_level'] = $validated['security_level'] ?? $this->assessSecurityLevel($originalPassword);

        $vault->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Credential updated successfully!'
        ]);
    }

    public function destroy(Vault $vault): JsonResponse
    {
        $this->authorize('delete', $vault);
        $vault->delete();

        return response()->json([
            'success' => true,
            'message' => 'Credential deleted successfully!'
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');
        $type = $request->input('type');
        $tags = $request->input('tags', []);

        $vaults = Vault::where('user_id', auth()->id())
            ->when($query, function ($q) use ($query) {
                $q->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('host', 'like', "%{$query}%")
                      ->orWhere('username', 'like', "%{$query}%")
                      ->orWhere('notes', 'like', "%{$query}%");
                });
            })
            ->when($type, function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->when($tags, function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->where('tags', 'like', "%{$tag}%");
                }
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($vault) {
                return [
                    'id' => $vault->id,
                    'name' => $vault->name,
                    'type' => $vault->type,
                    'host' => $vault->host,
                    'username' => $vault->username,
                    'password' => $this->maskPassword($vault->password),
                    'security_score' => $this->calculateSecurityScore($vault)
                ];
            });

        return response()->json(['vaults' => $vaults]);
    }

    public function export(Request $request): JsonResponse
    {
        $format = $request->input('format', 'json');
        $vaults = Vault::where('user_id', auth()->id())->get();

        $data = $vaults->map(function ($vault) {
            try {
                $decryptedPassword = Crypt::decryptString($vault->password);
            } catch (\Exception $e) {
                $decryptedPassword = '*** (encrypted) ***';
            }

            return [
                'name' => $vault->name,
                'type' => $vault->type,
                'host' => $vault->host,
                'username' => $vault->username,
                'password' => $decryptedPassword,
                'notes' => $vault->notes,
                'tags' => json_decode($vault->tags, true) ?? [],
                'created_at' => $vault->created_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json([
            'format' => $format,
            'data' => $data,
            'exported_at' => now()->toISOString()
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:json,csv|max:2048'
        ]);

        $file = $request->file('file');
        $imported = 0;
        $errors = [];

        if ($file->getClientOriginalExtension() === 'json') {
            $data = json_decode($file->getContents(), true);
            
            foreach ($data as $item) {
                try {
                    Vault::create([
                        'user_id' => Auth::id(),
                        'name' => $item['name'] ?? 'Imported Credential',
                        'type' => $item['type'] ?? 'other',
                        'host' => $item['host'] ?? '',
                        'username' => $item['username'] ?? '',
                        'password' => Crypt::encryptString($item['password'] ?? ''),
                        'notes' => $item['notes'] ?? '',
                        'tags' => json_encode($item['tags'] ?? [])
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Error importing {$item['name']}: " . $e->getMessage();
                }
            }
        }

        return response()->json([
            'success' => true,
            'imported' => $imported,
            'errors' => $errors,
            'message' => "Successfully imported {$imported} credentials"
        ]);
    }

    public function securityAudit(): JsonResponse
    {
        $vaults = Vault::where('user_id', auth()->id())->get();
        
        $audit = [
            'total_credentials' => $vaults->count(),
            'weak_passwords' => 0,
            'duplicate_passwords' => 0,
            'expired_credentials' => 0,
            'security_score' => 0,
            'recommendations' => []
        ];

        $passwords = [];
        
        foreach ($vaults as $vault) {
            try {
                $password = Crypt::decryptString($vault->password);
                $strength = $this->analyzePasswordStrength($password);
                
                if ($strength['score'] < 40) {
                    $audit['weak_passwords']++;
                }
                
                $passwords[] = $password;
                $audit['security_score'] += $strength['score'];
            } catch (\Exception $e) {
                // Si hay error al desencriptar, contar como contraseña débil
                $audit['weak_passwords']++;
                $audit['security_score'] += 30; // Score base bajo
            }
        }

        // Detectar contraseñas duplicadas
        $duplicates = array_count_values($passwords);
        $audit['duplicate_passwords'] = count(array_filter($duplicates, fn($count) => $count > 1));

        // Calcular score promedio
        $audit['security_score'] = $audit['total_credentials'] > 0 ? 
            round($audit['security_score'] / $audit['total_credentials'], 2) : 0;

        // Generar recomendaciones
        if ($audit['weak_passwords'] > 0) {
            $audit['recommendations'][] = "Update {$audit['weak_passwords']} weak passwords";
        }
        if ($audit['duplicate_passwords'] > 0) {
            $audit['recommendations'][] = "Replace {$audit['duplicate_passwords']} duplicate passwords";
        }
        if ($audit['security_score'] < 60) {
            $audit['recommendations'][] = "Overall security score is low. Consider updating passwords";
        }

        return response()->json($audit);
    }

    private function maskPassword(string $password): string
    {
        $length = strlen($password);
        if ($length <= 2) {
            return str_repeat('*', $length);
        }
        
        return $password[0] . str_repeat('*', $length - 2) . $password[$length - 1];
    }

    private function calculateSecurityScore(Vault $vault): int
    {
        try {
            $password = Crypt::decryptString($vault->password);
            $strength = $this->analyzePasswordStrength($password);
            
            $score = $strength['score'];
            
            // Bonus por tipo de credencial
            $typeBonus = [
                'website' => 5,
                'server' => 10,
                'database' => 15,
                'api' => 10,
                'email' => 8,
                'application' => 12,
                'other' => 0
            ];
            
            $score += $typeBonus[$vault->type] ?? 0;
            
            return min(100, $score);
        } catch (\Exception $e) {
            // Si hay error al desencriptar, retornar score base
            return 50;
        }
    }

    private function analyzePasswordStrength(string $password): array
    {
        $score = 0;
        
        // Longitud
        if (strlen($password) >= 12) {
            $score += 25;
        } elseif (strlen($password) >= 8) {
            $score += 15;
        }
        
        // Complejidad
        if (preg_match('/[a-z]/', $password)) $score += 10;
        if (preg_match('/[A-Z]/', $password)) $score += 10;
        if (preg_match('/[0-9]/', $password)) $score += 10;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $score += 15;
        
        return [
            'score' => min(100, $score),
            'length' => strlen($password),
            'has_lowercase' => preg_match('/[a-z]/', $password),
            'has_uppercase' => preg_match('/[A-Z]/', $password),
            'has_numbers' => preg_match('/[0-9]/', $password),
            'has_symbols' => preg_match('/[^A-Za-z0-9]/', $password)
        ];
    }

    private function assessSecurityLevel(string $password): string
    {
        $strength = $this->analyzePasswordStrength($password);
        
        if ($strength['score'] >= 80) return 'high';
        if ($strength['score'] >= 60) return 'medium';
        if ($strength['score'] >= 40) return 'low';
        return 'critical';
    }

    private function generateVaultStats(): array
    {
        $vaults = Vault::where('user_id', auth()->id())->get();
        
        return [
            'total_credentials' => $vaults->count(),
            'by_type' => $vaults->groupBy('type')->map->count(),
            'recently_added' => $vaults->where('created_at', '>=', now()->subDays(7))->count(),
            'favorites' => $vaults->where('is_favorite', true)->count(),
            'average_security_score' => $vaults->avg(function ($vault) {
                return $this->calculateSecurityScore($vault);
            })
        ];
    }

    private function getCategories(): array
    {
        return [
            'website' => 'Website Login',
            'server' => 'Server Access',
            'database' => 'Database Connection',
            'api' => 'API Credentials',
            'email' => 'Email Account',
            'application' => 'Application Login',
            'other' => 'Other'
        ];
    }

    private function getTemplates(): array
    {
        return [
            'website' => [
                'name' => 'Website Login',
                'host' => 'https://example.com',
                'username' => 'user@example.com',
                'notes' => 'Website login credentials'
            ],
            'server' => [
                'name' => 'Server SSH',
                'host' => '192.168.1.100',
                'username' => 'root',
                'notes' => 'SSH server access'
            ],
            'database' => [
                'name' => 'Database Connection',
                'host' => 'localhost:3306',
                'username' => 'db_user',
                'notes' => 'MySQL database credentials'
            ],
            'api' => [
                'name' => 'API Key',
                'host' => 'api.example.com',
                'username' => 'api_user',
                'notes' => 'API authentication'
            ]
        ];
    }
}
