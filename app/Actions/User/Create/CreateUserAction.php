<?php

namespace App\Actions\User\Create;

use App\Models\User;
use App\Actions\User\Create\Dto\CreateUserDto;

class CreateUserAction {
    public function handle(CreateUserDto $createUserDto): User
    {

        $user = new User();
        $user->phone = $createUserDto->getPhone();
        $user->role = $createUserDto->getRole();
        $user->name = $createUserDto->getName();
        $user->password = $createUserDto->getPassword();

        $user->save();

        return $user;
    }
}
