<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Vault;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class GenerateVaultSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-vault-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for existing vault records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (Vault::select(['id', 'title'])->get() as $vault) {
            $vault->update(['slug' => Str::slug($vault->title)]);
        }
    }
}
