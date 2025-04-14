<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class GenerateUserSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-user-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for existing users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (User::select(['id', 'name'])->get() as $user) {
            $user->update(['slug' => Str::slug($user->name)]);
        }
    }
}
