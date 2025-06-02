<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HashAdminPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hash:admin-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash all plain text passwords in admin table to bcrypt';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $admins = DB::table('admin')->get();

        foreach ($admins as $admin) {
            // Cek apakah password sudah bcrypt (biasanya mulai dengan $2y$)
            if (!Str::startsWith($admin->password, '$2y$')) {
                $this->info('Hashing password for: ' . $admin->email);

                DB::table('admin')
                    ->where('email', $admin->email)
                    ->update([
                        'password' => Hash::make($admin->password),
                    ]);
            } else {
                $this->info('Already hashed: ' . $admin->email);
            }
        }

        $this->info('All passwords processed.');

        return 0;
    }
}
