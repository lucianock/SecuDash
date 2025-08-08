<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PasswordGeneratorController extends Controller
{
    public function index()
    {
        return view('password-generator');
    }

    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'length' => 'required|integer|min:4|max:128',
            'include_uppercase' => 'boolean',
            'include_lowercase' => 'boolean',
            'include_numbers' => 'boolean',
            'include_symbols' => 'boolean',
            'exclude_similar' => 'boolean',
            'exclude_ambiguous' => 'boolean',
            'custom_chars' => 'nullable|string|max:50',
            'pattern' => 'nullable|string|in:random,pronounceable,memorable,passphrase',
            'word_count' => 'nullable|integer|min:3|max:10',
            'separator' => 'nullable|string|max:10'
        ]);

        $password = $this->generateAdvancedPassword($validated);
        $strength = $this->analyzePasswordStrength($password);
        $suggestions = $this->generateSuggestions($strength, $validated);

        return response()->json([
            'password' => $password,
            'strength' => $strength,
            'suggestions' => $suggestions,
            'entropy' => $this->calculateEntropy($password),
            'generation_time' => microtime(true)
        ]);
    }

    private function generateAdvancedPassword(array $options): string
    {
        $length = $options['length'];
        $pattern = $options['pattern'] ?? 'random';

        switch ($pattern) {
            case 'pronounceable':
                return $this->generatePronounceablePassword($length);
            case 'memorable':
                return $this->generateMemorablePassword($length);
            case 'passphrase':
                return $this->generatePassphrase($options);
            default:
                return $this->generateRandomPassword($options);
        }
    }

    private function generateRandomPassword(array $options): string
    {
        $length = $options['length'];
        $chars = '';

        // Construir conjunto de caracteres basado en opciones
        if ($options['include_lowercase'] ?? true) {
            $chars .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($options['include_uppercase'] ?? true) {
            $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($options['include_numbers'] ?? true) {
            $chars .= '0123456789';
        }
        if ($options['include_symbols'] ?? false) {
            $chars .= '!@#$%^&*()_+-=[]{}|;:,.<>?';
        }

        // Excluir caracteres similares
        if ($options['exclude_similar'] ?? false) {
            $chars = str_replace(['l', '1', 'I', 'O', '0'], '', $chars);
        }

        // Excluir caracteres ambiguos
        if ($options['exclude_ambiguous'] ?? false) {
            $chars = str_replace(['{}[]()/\\\'"`~,;:.<>'], '', $chars);
        }

        // Usar caracteres personalizados si se proporcionan
        if (!empty($options['custom_chars'])) {
            $chars = $options['custom_chars'];
        }

        if (empty($chars)) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }

        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $password;
    }

    private function generatePronounceablePassword(int $length): string
    {
        $consonants = 'bcdfghjklmnpqrstvwxz';
        $vowels = 'aeiouy';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            if ($i % 2 == 0) {
                $password .= $consonants[random_int(0, strlen($consonants) - 1)];
            } else {
                $password .= $vowels[random_int(0, strlen($vowels) - 1)];
            }
        }

        return $password;
    }

    private function generateMemorablePassword(int $length): string
    {
        $words = [
            'apple', 'banana', 'cherry', 'dragon', 'eagle', 'forest', 'garden', 'house',
            'island', 'jungle', 'knight', 'lemon', 'mountain', 'ocean', 'planet', 'queen',
            'river', 'sunset', 'tiger', 'umbrella', 'village', 'window', 'xylophone', 'yellow',
            'zebra', 'adventure', 'beautiful', 'creative', 'delicious', 'excellent', 'fantastic'
        ];

        $password = '';
        $wordCount = ceil($length / 6);
        
        for ($i = 0; $i < $wordCount; $i++) {
            $word = $words[array_rand($words)];
            $password .= ucfirst($word);
            
            if ($i < $wordCount - 1) {
                $password .= random_int(0, 9);
            }
        }

        return substr($password, 0, $length);
    }

    private function generatePassphrase(array $options): string
    {
        $wordCount = $options['word_count'] ?? 4;
        $separator = $options['separator'] ?? '-';
        
        $words = [
            'correct', 'horse', 'battery', 'staple', 'mountain', 'ocean', 'forest', 'river',
            'sunset', 'adventure', 'beautiful', 'creative', 'delicious', 'excellent', 'fantastic',
            'garden', 'house', 'island', 'jungle', 'knight', 'lemon', 'planet', 'queen',
            'tiger', 'village', 'window', 'yellow', 'zebra', 'dragon', 'eagle', 'cherry'
        ];

        $passphrase = [];
        for ($i = 0; $i < $wordCount; $i++) {
            $passphrase[] = $words[array_rand($words)];
        }

        return implode($separator, $passphrase);
    }

    private function analyzePasswordStrength(string $password): array
    {
        $score = 0;
        $feedback = [];

        // Longitud
        if (strlen($password) >= 12) {
            $score += 25;
        } elseif (strlen($password) >= 8) {
            $score += 15;
        } else {
            $feedback[] = 'Password is too short';
        }

        // Complejidad
        if (preg_match('/[a-z]/', $password)) $score += 10;
        if (preg_match('/[A-Z]/', $password)) $score += 10;
        if (preg_match('/[0-9]/', $password)) $score += 10;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $score += 15;

        // Patrones
        if (preg_match('/(.)\1{2,}/', $password)) {
            $score -= 10;
            $feedback[] = 'Avoid repeated characters';
        }

        if (preg_match('/123|abc|qwe/', strtolower($password))) {
            $score -= 15;
            $feedback[] = 'Avoid common sequences';
        }

        // Entropía
        $entropy = $this->calculateEntropy($password);
        if ($entropy > 80) {
            $score += 20;
        } elseif ($entropy > 60) {
            $score += 10;
        } else {
            $feedback[] = 'Password lacks randomness';
        }

        // Determinar nivel de fortaleza
        if ($score >= 80) {
            $strength = 'Very Strong';
            $color = 'text-green-500';
        } elseif ($score >= 60) {
            $strength = 'Strong';
            $color = 'text-blue-500';
        } elseif ($score >= 40) {
            $strength = 'Medium';
            $color = 'text-yellow-500';
        } elseif ($score >= 20) {
            $strength = 'Weak';
            $color = 'text-orange-500';
        } else {
            $strength = 'Very Weak';
            $color = 'text-red-500';
        }

        return [
            'score' => max(0, min(100, $score)),
            'strength' => $strength,
            'color' => $color,
            'feedback' => $feedback,
            'length' => strlen($password),
            'has_lowercase' => preg_match('/[a-z]/', $password),
            'has_uppercase' => preg_match('/[A-Z]/', $password),
            'has_numbers' => preg_match('/[0-9]/', $password),
            'has_symbols' => preg_match('/[^A-Za-z0-9]/', $password)
        ];
    }

    private function calculateEntropy(string $password): float
    {
        $charset = 0;
        if (preg_match('/[a-z]/', $password)) $charset += 26;
        if (preg_match('/[A-Z]/', $password)) $charset += 26;
        if (preg_match('/[0-9]/', $password)) $charset += 10;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $charset += 32;

        return strlen($password) * log($charset, 2);
    }

    private function generateSuggestions(array $strength, array $options): array
    {
        $suggestions = [];

        if ($strength['score'] < 60) {
            $suggestions[] = 'Increase password length to at least 12 characters';
        }

        if (!$strength['has_lowercase']) {
            $suggestions[] = 'Include lowercase letters';
        }

        if (!$strength['has_uppercase']) {
            $suggestions[] = 'Include uppercase letters';
        }

        if (!$strength['has_numbers']) {
            $suggestions[] = 'Include numbers';
        }

        if (!$strength['has_symbols']) {
            $suggestions[] = 'Include special characters';
        }

        if (empty($suggestions)) {
            $suggestions[] = 'Great! Your password meets security standards';
        }

        return $suggestions;
    }

    public function generateBulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'count' => 'required|integer|min:1|max:50',
            'length' => 'required|integer|min:4|max:128',
            'include_symbols' => 'boolean'
        ]);

        $passwords = [];
        for ($i = 0; $i < $validated['count']; $i++) {
            $password = $this->generateRandomPassword([
                'length' => $validated['length'],
                'include_symbols' => $validated['include_symbols'] ?? false
            ]);
            
            $passwords[] = [
                'password' => $password,
                'strength' => $this->analyzePasswordStrength($password)
            ];
        }

        return response()->json(['passwords' => $passwords]);
    }

    public function validatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'password' => 'required|string|max:255'
        ]);

        $strength = $this->analyzePasswordStrength($validated['password']);
        $suggestions = $this->generateSuggestions($strength, []);

        return response()->json([
            'strength' => $strength,
            'suggestions' => $suggestions,
            'entropy' => $this->calculateEntropy($validated['password'])
        ]);
    }
}
