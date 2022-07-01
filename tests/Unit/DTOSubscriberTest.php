<?php

namespace App\Tests\Unit;

use App\DTO\LowestPriceEnquiry;
use App\Event\AfterDTOCreatedEvent;
use App\EventSubscriber\DTOSubscriber;
use App\Service\ServiceException;
use App\Tests\ServiceTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class DTOSubscriberTest extends ServiceTestCase
{
    public function testEventSubscriber(): void
    {
        $this->assertArrayHasKey(AfterDTOCreatedEvent::NAME, DTOSubscriber::getSubscribedEvents());
    }

    public function testValidDto(): void
    {
        $dto = new LowestPriceEnquiry();
        $dto->setQuantity(-5);

        $event = new AfterDTOCreatedEvent($dto);

        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->container->get(EventDispatcherInterface::class);

        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('ConstraintViolationList');

        //When
        $dispatcher->dispatch($event, $event::NAME);
    }
}
