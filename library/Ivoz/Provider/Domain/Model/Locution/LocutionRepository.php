<?php

namespace Ivoz\Provider\Domain\Model\Locution;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Collections\Selectable;

interface LocutionRepository extends ObjectRepository, Selectable
{

}
