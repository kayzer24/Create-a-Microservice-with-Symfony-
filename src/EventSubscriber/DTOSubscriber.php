<?php

namespace App\EventSubscriber;

use App\Event\AfterDTOCreatedEvent;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOSubscriber implements EventSubscriberInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterDTOCreatedEvent::NAME => 'validateDto'
        ];
    }

    public function validateDto(AfterDTOCreatedEvent $event): void
    {
        // validate the dto
        $dto = $event->getDto();

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new ValidationFailedException('Validation failed', $errors);
        }
    }
}