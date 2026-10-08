<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Controller\User;

use Shepherdmat\Phinanse\Application\Exception\NotFoundException;
use Shepherdmat\Phinanse\Application\Query\User\FindOneByEmailQuery;
use Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface;
use Shepherdmat\Phinanse\Shared\Security\PasswordHasherInterface;
use Shepherdmat\Phinanse\UI\Http\Exception\UiException;
use Shepherdmat\Phinanse\UI\Http\Request\Dto\LoginRequestDto;

final readonly class LoginController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private PasswordHasherInterface $passwordHasher, // Wstrzykujemy hasher!
    ) {}

    public function __invoke(LoginRequestDto $requestModel) // Zwracasz np. JWT token
    {
        try {
            // 1. Znajdź usera (zwróci DTO usera z zahashowanym hasłem wyciągniętym z bazy)
            $user = $this->messageBus->query(
                new FindOneByEmailQuery(email: $requestModel->email)
            );

            // 2. Zweryfikuj hasło
            if (!$this->passwordHasher->verify($requestModel->password, $user->passwordHash)) {
                // Rzucamy nasz wyjątek domyślny, żeby nie ułatwiać enumeracji kont
                throw new NotFoundException();
            }

            // 3. Sukces - generuj token / sesję
            // return $token;

        } catch (NotFoundException) {
            throw new UiException('error.credentials.invalid', [], 401, 'Invalid credentials');
        }

        dd('ok');
    }
}