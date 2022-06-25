<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class TokenCommand extends Command
{
    protected $signature = 'token:generate {id}'; // userId for specific user

    public function handle()
    {
        $id = $this->argument('id'); // sent to command in console

        $user = User::find($id);

        \Auth::setUser($user);

        $console = new ConsoleOutput();

        $console->writeln($user->createToken('admin')->plainTextToken);
    }
}
