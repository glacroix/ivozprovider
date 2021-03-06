<?php

namespace Ivoz\Provider\Domain\Model\OutgoingRouting;

use Ivoz\Core\Application\DataTransferObjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * OutgoingRoutingTrait
 * @codeCoverageIgnore
 */
trait OutgoingRoutingTrait
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Ivoz\Cgr\Domain\Model\TpLcrRule\TpLcrRuleInterface
     */
    protected $tpLcrRule;

    /**
     * @var ArrayCollection
     */
    protected $lcrRules;

    /**
     * @var ArrayCollection
     */
    protected $lcrRuleTargets;

    /**
     * @var ArrayCollection
     */
    protected $relCarriers;


    /**
     * Constructor
     */
    protected function __construct()
    {
        parent::__construct(...func_get_args());
        $this->lcrRules = new ArrayCollection();
        $this->lcrRuleTargets = new ArrayCollection();
        $this->relCarriers = new ArrayCollection();
    }

    abstract protected function sanitizeValues();

    /**
     * Factory method
     * @internal use EntityTools instead
     * @param OutgoingRoutingDto $dto
     * @param \Ivoz\Core\Application\ForeignKeyTransformerInterface  $fkTransformer
     * @return static
     */
    public static function fromDto(
        DataTransferObjectInterface $dto,
        \Ivoz\Core\Application\ForeignKeyTransformerInterface $fkTransformer
    ) {
        /** @var static $self */
        $self = parent::fromDto($dto, $fkTransformer);
        if (!is_null($dto->getLcrRules())) {
            $self->replaceLcrRules(
                $fkTransformer->transformCollection(
                    $dto->getLcrRules()
                )
            );
        }

        if (!is_null($dto->getLcrRuleTargets())) {
            $self->replaceLcrRuleTargets(
                $fkTransformer->transformCollection(
                    $dto->getLcrRuleTargets()
                )
            );
        }

        if (!is_null($dto->getRelCarriers())) {
            $self->replaceRelCarriers(
                $fkTransformer->transformCollection(
                    $dto->getRelCarriers()
                )
            );
        }
        $self->sanitizeValues();
        if ($dto->getId()) {
            $self->id = $dto->getId();
            $self->initChangelog();
        }

        return $self;
    }

    /**
     * @internal use EntityTools instead
     * @param OutgoingRoutingDto $dto
     * @param \Ivoz\Core\Application\ForeignKeyTransformerInterface  $fkTransformer
     * @return static
     */
    public function updateFromDto(
        DataTransferObjectInterface $dto,
        \Ivoz\Core\Application\ForeignKeyTransformerInterface $fkTransformer
    ) {
        parent::updateFromDto($dto, $fkTransformer);
        if (!is_null($dto->getLcrRules())) {
            $this->replaceLcrRules(
                $fkTransformer->transformCollection(
                    $dto->getLcrRules()
                )
            );
        }
        if (!is_null($dto->getLcrRuleTargets())) {
            $this->replaceLcrRuleTargets(
                $fkTransformer->transformCollection(
                    $dto->getLcrRuleTargets()
                )
            );
        }
        if (!is_null($dto->getRelCarriers())) {
            $this->replaceRelCarriers(
                $fkTransformer->transformCollection(
                    $dto->getRelCarriers()
                )
            );
        }
        $this->sanitizeValues();

        return $this;
    }

    /**
     * @internal use EntityTools instead
     * @param int $depth
     * @return OutgoingRoutingDto
     */
    public function toDto($depth = 0)
    {
        $dto = parent::toDto($depth);
        return $dto
            ->setId($this->getId());
    }

    /**
     * @return array
     */
    protected function __toArray()
    {
        return parent::__toArray() + [
            'id' => self::getId()
        ];
    }
    /**
     * Set tpLcrRule
     *
     * @param \Ivoz\Cgr\Domain\Model\TpLcrRule\TpLcrRuleInterface $tpLcrRule
     *
     * @return static
     */
    public function setTpLcrRule(\Ivoz\Cgr\Domain\Model\TpLcrRule\TpLcrRuleInterface $tpLcrRule = null)
    {
        $this->tpLcrRule = $tpLcrRule;

        return $this;
    }

    /**
     * Get tpLcrRule
     *
     * @return \Ivoz\Cgr\Domain\Model\TpLcrRule\TpLcrRuleInterface
     */
    public function getTpLcrRule()
    {
        return $this->tpLcrRule;
    }

    /**
     * Add lcrRule
     *
     * @param \Ivoz\Kam\Domain\Model\TrunksLcrRule\TrunksLcrRuleInterface $lcrRule
     *
     * @return static
     */
    public function addLcrRule(\Ivoz\Kam\Domain\Model\TrunksLcrRule\TrunksLcrRuleInterface $lcrRule)
    {
        $this->lcrRules->add($lcrRule);

        return $this;
    }

    /**
     * Remove lcrRule
     *
     * @param \Ivoz\Kam\Domain\Model\TrunksLcrRule\TrunksLcrRuleInterface $lcrRule
     */
    public function removeLcrRule(\Ivoz\Kam\Domain\Model\TrunksLcrRule\TrunksLcrRuleInterface $lcrRule)
    {
        $this->lcrRules->removeElement($lcrRule);
    }

    /**
     * Replace lcrRules
     *
     * @param ArrayCollection $lcrRules of Ivoz\Kam\Domain\Model\TrunksLcrRule\TrunksLcrRuleInterface
     * @return static
     */
    public function replaceLcrRules(ArrayCollection $lcrRules)
    {
        $updatedEntities = [];
        $fallBackId = -1;
        foreach ($lcrRules as $entity) {
            $index = $entity->getId() ? $entity->getId() : $fallBackId--;
            $updatedEntities[$index] = $entity;
            $entity->setOutgoingRouting($this);
        }
        $updatedEntityKeys = array_keys($updatedEntities);

        foreach ($this->lcrRules as $key => $entity) {
            $identity = $entity->getId();
            if (in_array($identity, $updatedEntityKeys)) {
                $this->lcrRules->set($key, $updatedEntities[$identity]);
            } else {
                $this->lcrRules->remove($key);
            }
            unset($updatedEntities[$identity]);
        }

        foreach ($updatedEntities as $entity) {
            $this->addLcrRule($entity);
        }

        return $this;
    }

    /**
     * Get lcrRules
     * @param Criteria | null $criteria
     * @return \Ivoz\Kam\Domain\Model\TrunksLcrRule\TrunksLcrRuleInterface[]
     */
    public function getLcrRules(Criteria $criteria = null)
    {
        if (!is_null($criteria)) {
            return $this->lcrRules->matching($criteria)->toArray();
        }

        return $this->lcrRules->toArray();
    }

    /**
     * Add lcrRuleTarget
     *
     * @param \Ivoz\Kam\Domain\Model\TrunksLcrRuleTarget\TrunksLcrRuleTargetInterface $lcrRuleTarget
     *
     * @return static
     */
    public function addLcrRuleTarget(\Ivoz\Kam\Domain\Model\TrunksLcrRuleTarget\TrunksLcrRuleTargetInterface $lcrRuleTarget)
    {
        $this->lcrRuleTargets->add($lcrRuleTarget);

        return $this;
    }

    /**
     * Remove lcrRuleTarget
     *
     * @param \Ivoz\Kam\Domain\Model\TrunksLcrRuleTarget\TrunksLcrRuleTargetInterface $lcrRuleTarget
     */
    public function removeLcrRuleTarget(\Ivoz\Kam\Domain\Model\TrunksLcrRuleTarget\TrunksLcrRuleTargetInterface $lcrRuleTarget)
    {
        $this->lcrRuleTargets->removeElement($lcrRuleTarget);
    }

    /**
     * Replace lcrRuleTargets
     *
     * @param ArrayCollection $lcrRuleTargets of Ivoz\Kam\Domain\Model\TrunksLcrRuleTarget\TrunksLcrRuleTargetInterface
     * @return static
     */
    public function replaceLcrRuleTargets(ArrayCollection $lcrRuleTargets)
    {
        $updatedEntities = [];
        $fallBackId = -1;
        foreach ($lcrRuleTargets as $entity) {
            $index = $entity->getId() ? $entity->getId() : $fallBackId--;
            $updatedEntities[$index] = $entity;
            $entity->setOutgoingRouting($this);
        }
        $updatedEntityKeys = array_keys($updatedEntities);

        foreach ($this->lcrRuleTargets as $key => $entity) {
            $identity = $entity->getId();
            if (in_array($identity, $updatedEntityKeys)) {
                $this->lcrRuleTargets->set($key, $updatedEntities[$identity]);
            } else {
                $this->lcrRuleTargets->remove($key);
            }
            unset($updatedEntities[$identity]);
        }

        foreach ($updatedEntities as $entity) {
            $this->addLcrRuleTarget($entity);
        }

        return $this;
    }

    /**
     * Get lcrRuleTargets
     * @param Criteria | null $criteria
     * @return \Ivoz\Kam\Domain\Model\TrunksLcrRuleTarget\TrunksLcrRuleTargetInterface[]
     */
    public function getLcrRuleTargets(Criteria $criteria = null)
    {
        if (!is_null($criteria)) {
            return $this->lcrRuleTargets->matching($criteria)->toArray();
        }

        return $this->lcrRuleTargets->toArray();
    }

    /**
     * Add relCarrier
     *
     * @param \Ivoz\Provider\Domain\Model\OutgoingRoutingRelCarrier\OutgoingRoutingRelCarrierInterface $relCarrier
     *
     * @return static
     */
    public function addRelCarrier(\Ivoz\Provider\Domain\Model\OutgoingRoutingRelCarrier\OutgoingRoutingRelCarrierInterface $relCarrier)
    {
        $this->relCarriers->add($relCarrier);

        return $this;
    }

    /**
     * Remove relCarrier
     *
     * @param \Ivoz\Provider\Domain\Model\OutgoingRoutingRelCarrier\OutgoingRoutingRelCarrierInterface $relCarrier
     */
    public function removeRelCarrier(\Ivoz\Provider\Domain\Model\OutgoingRoutingRelCarrier\OutgoingRoutingRelCarrierInterface $relCarrier)
    {
        $this->relCarriers->removeElement($relCarrier);
    }

    /**
     * Replace relCarriers
     *
     * @param ArrayCollection $relCarriers of Ivoz\Provider\Domain\Model\OutgoingRoutingRelCarrier\OutgoingRoutingRelCarrierInterface
     * @return static
     */
    public function replaceRelCarriers(ArrayCollection $relCarriers)
    {
        $updatedEntities = [];
        $fallBackId = -1;
        foreach ($relCarriers as $entity) {
            $index = $entity->getId() ? $entity->getId() : $fallBackId--;
            $updatedEntities[$index] = $entity;
            $entity->setOutgoingRouting($this);
        }
        $updatedEntityKeys = array_keys($updatedEntities);

        foreach ($this->relCarriers as $key => $entity) {
            $identity = $entity->getId();
            if (in_array($identity, $updatedEntityKeys)) {
                $this->relCarriers->set($key, $updatedEntities[$identity]);
            } else {
                $this->relCarriers->remove($key);
            }
            unset($updatedEntities[$identity]);
        }

        foreach ($updatedEntities as $entity) {
            $this->addRelCarrier($entity);
        }

        return $this;
    }

    /**
     * Get relCarriers
     * @param Criteria | null $criteria
     * @return \Ivoz\Provider\Domain\Model\OutgoingRoutingRelCarrier\OutgoingRoutingRelCarrierInterface[]
     */
    public function getRelCarriers(Criteria $criteria = null)
    {
        if (!is_null($criteria)) {
            return $this->relCarriers->matching($criteria)->toArray();
        }

        return $this->relCarriers->toArray();
    }
}
