<?php

namespace App\Console\Commands;

use App\Models\Vault;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class EncryptVaultPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vault:encrypt-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt all vault passwords that are not already encrypted';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting vault password encryption...');

        $vaults = Vault::all();
        $encrypted = 0;
        $skipped = 0;

        foreach ($vaults as $vault) {
            try {
                // Intentar desencriptar para ver si ya está encriptada
                Crypt::decryptString($vault->password);
                $skipped++;
                $this->line("Skipped vault ID {$vault->id} (already encrypted)");
            } catch (\Exception $e) {
                // Si no se puede desencriptar, significa que no está encriptada
                // Usar DB::update para evitar los observers del modelo
                \DB::table('vault')->where('id', $vault->id)->update([
                    'password' => Crypt::encryptString($vault->password),
                    'updated_at' => now()
                ]);
                $encrypted++;
                $this->line("Encrypted vault ID {$vault->id}");
            }
        }

        $this->info("Encryption completed!");
        $this->info("Encrypted: {$encrypted} passwords");
        $this->info("Skipped: {$skipped} passwords (already encrypted)");
    }
}
