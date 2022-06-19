<?php

namespace App\Tests\Unit;

use App\DTO\LowestPriceEnquiry;
use App\Event\AfterDTOCreatedEvent;
use App\Tests\ServiceTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class DTOSubscriberTest extends ServiceTestCase
{
    public function testDtoIsValidateAfterItHasBeenCreated(): void
    {
        $dto = new LowestPriceEnquiry();
        $dto->setQuantity(-5);

        $event = new AfterDTOCreatedEvent($dto);

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->container->get('debug.event_dispatcher');

        $this->expectException(ValidationFailedException::class);
        $this->expectExceptionMessage('This value should be positive.');

        //When
        $eventDispatcher->dispatch($event, $event::NAME);
    }
}
