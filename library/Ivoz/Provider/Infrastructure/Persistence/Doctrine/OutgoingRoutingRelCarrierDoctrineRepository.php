<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\OutgoingRoutingRelCarrier\OutgoingRoutingRelCarrierRepository;
use Ivoz\Provider\Domain\Model\OutgoingRoutingRelCarrier\OutgoingRoutingRelCarrier;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * OutgoingRoutingRelCarrierDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OutgoingRoutingRelCarrierDoctrineRepository extends ServiceEntityRepository implements OutgoingRoutingRelCarrierRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OutgoingRoutingRelCarrier::class);
    }
}