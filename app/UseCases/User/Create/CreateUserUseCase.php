<?php

namespace App\UseCases\User\Create;

use App\Models\User;
use App\UseCases\User\Create\Dto\CreateUserDto;

class CreateUserUseCase {
    public function handle(CreateUserDto $createUserDto): User
    {
        $user = new User();
        $user->id = $createUserDto->getId();
        $user->phone = $createUserDto->getPhone();
        $user->role = $createUserDto->getRole();
        $user->name = $createUserDto->getName();
        $user->password = $createUserDto->getPassword();

        $user->save();

        return $user;
    }
}
