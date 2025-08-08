<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vault extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'host',
        'username',
        'password',
        'notes',
        'user_id',
        'tags',
        'is_favorite',
        'auto_login',
        'expires_at',
        'security_level',
        'last_accessed',
        'access_count'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'tags' => 'array',
        'is_favorite' => 'boolean',
        'auto_login' => 'boolean',
        'expires_at' => 'datetime',
        'last_accessed' => 'datetime',
        'access_count' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeBySecurityLevel($query, string $level)
    {
        return $query->where('security_level', $level);
    }

    public function scopeRecentlyAccessed($query, int $days = 7)
    {
        return $query->where('last_accessed', '>=', now()->subDays($days));
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('host', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%")
              ->orWhere('notes', 'like', "%{$search}%");
        });
    }

    public function scopeByTags($query, array $tags)
    {
        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->where('tags', 'like', "%{$tag}%");
            }
        });
    }

    public function getSecurityScoreAttribute(): int
    {
        // Implementar lógica de cálculo de score de seguridad
        $score = 0;
        
        // Longitud de contraseña
        $passwordLength = strlen($this->password);
        if ($passwordLength >= 12) $score += 25;
        elseif ($passwordLength >= 8) $score += 15;
        
        // Complejidad
        if (preg_match('/[a-z]/', $this->password)) $score += 10;
        if (preg_match('/[A-Z]/', $this->password)) $score += 10;
        if (preg_match('/[0-9]/', $this->password)) $score += 10;
        if (preg_match('/[^A-Za-z0-9]/', $this->password)) $score += 15;
        
        return min(100, $score);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }
        
        return now()->diffInDays($this->expires_at, false);
    }

    public function getFormattedLastAccessedAttribute(): string
    {
        if (!$this->last_accessed) {
            return 'Never';
        }
        
        return $this->last_accessed->diffForHumans();
    }

    public function getTypeLabelAttribute(): string
    {
        $labels = [
            'website' => 'Website Login',
            'server' => 'Server Access',
            'database' => 'Database Connection',
            'api' => 'API Credentials',
            'email' => 'Email Account',
            'application' => 'Application Login',
            'other' => 'Other'
        ];
        
        return $labels[$this->type] ?? ucfirst($this->type);
    }

    public function getSecurityLevelLabelAttribute(): string
    {
        $labels = [
            'critical' => 'Critical',
            'high' => 'High',
            'medium' => 'Medium',
            'low' => 'Low'
        ];
        
        return $labels[$this->security_level] ?? 'Unknown';
    }

    public function getSecurityLevelColorAttribute(): string
    {
        $colors = [
            'critical' => 'text-red-500',
            'high' => 'text-orange-500',
            'medium' => 'text-yellow-500',
            'low' => 'text-green-500'
        ];
        
        return $colors[$this->security_level] ?? 'text-gray-500';
    }

    public function getTypeIconAttribute(): string
    {
        $icons = [
            'website' => '🌐',
            'server' => '🖥️',
            'database' => '🗄️',
            'api' => '🔌',
            'email' => '📧',
            'application' => '📱',
            'other' => '🔑'
        ];
        
        return $icons[$this->type] ?? '🔑';
    }

    public function incrementAccessCount(): void
    {
        $this->increment('access_count');
        $this->update(['last_accessed' => now()]);
    }

    public function toggleFavorite(): void
    {
        $this->update(['is_favorite' => !$this->is_favorite]);
    }

    public function addTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
    }

    public function removeTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        $tags = array_filter($tags, fn($t) => $t !== $tag);
        $this->update(['tags' => array_values($tags)]);
    }

    public function hasTag(string $tag): bool
    {
        return in_array($tag, $this->tags ?? []);
    }

    public function getTagsListAttribute(): string
    {
        return implode(', ', $this->tags ?? []);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vault) {
            if (!$vault->security_level) {
                $vault->security_level = $vault->assessSecurityLevel();
            }
        });

        static::updating(function ($vault) {
            if ($vault->isDirty('password')) {
                $vault->security_level = $vault->assessSecurityLevel();
            }
        });
    }

    private function assessSecurityLevel(): string
    {
        $score = $this->security_score;
        
        if ($score >= 80) return 'low';
        if ($score >= 60) return 'medium';
        if ($score >= 40) return 'high';
        return 'critical';
    }
}

