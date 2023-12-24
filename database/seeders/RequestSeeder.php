<?php

namespace Database\Seeders;

use App\Models\FriendRequest;
use App\Models\User;
use Database\Factories\FriendRequestFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{

    public function run(): void
    {
        \App\Models\FriendRequest::factory(150)->create();}
}
