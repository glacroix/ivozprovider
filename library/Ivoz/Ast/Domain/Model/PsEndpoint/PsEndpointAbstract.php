<?php

namespace Ivoz\Ast\Domain\Model\PsEndpoint;

use Assert\Assertion;
use Ivoz\Core\Application\DataTransferObjectInterface;
use Ivoz\Core\Domain\Model\ChangelogTrait;
use Ivoz\Core\Domain\Model\EntityInterface;

/**
 * PsEndpointAbstract
 * @codeCoverageIgnore
 */
abstract class PsEndpointAbstract
{
    /**
     * column: sorcery_id
     * @var string
     */
    protected $sorceryId;

    /**
     * column: from_domain
     * @var string | null
     */
    protected $fromDomain;

    /**
     * @var string | null
     */
    protected $aors;

    /**
     * @var string | null
     */
    protected $callerid;

    /**
     * @var string
     */
    protected $context = 'users';

    /**
     * @var string
     */
    protected $disallow = 'all';

    /**
     * @var string
     */
    protected $allow = 'all';

    /**
     * column: direct_media
     * @var string | null
     */
    protected $directMedia = 'yes';

    /**
     * column: direct_media_method
     * comment: enum:update|invite|reinvite
     * @var string | null
     */
    protected $directMediaMethod = 'update';

    /**
     * @var string | null
     */
    protected $mailboxes;

    /**
     * column: named_pickup_group
     * @var string | null
     */
    protected $namedPickupGroup;

    /**
     * column: send_diversion
     * @var string | null
     */
    protected $sendDiversion = 'yes';

    /**
     * column: send_pai
     * @var string | null
     */
    protected $sendPai = 'yes';

    /**
     * column: 100rel
     * @var string
     */
    protected $oneHundredRel = 'no';

    /**
     * column: outbound_proxy
     * @var string | null
     */
    protected $outboundProxy;

    /**
     * column: trust_id_inbound
     * @var string | null
     */
    protected $trustIdInbound;

    /**
     * column: t38_udptl
     * comment: enum:yes|no
     * @var string
     */
    protected $t38Udptl = 'no';

    /**
     * column: t38_udptl_ec
     * comment: enum:none|fec|redundancy
     * @var string
     */
    protected $t38UdptlEc = 'redundancy';

    /**
     * column: t38_udptl_maxdatagram
     * @var integer
     */
    protected $t38UdptlMaxdatagram = 1440;

    /**
     * column: t38_udptl_nat
     * comment: enum:yes|no
     * @var string
     */
    protected $t38UdptlNat = 'no';

    /**
     * @var \Ivoz\Provider\Domain\Model\Terminal\TerminalInterface
     */
    protected $terminal;

    /**
     * @var \Ivoz\Provider\Domain\Model\Friend\FriendInterface
     */
    protected $friend;

    /**
     * @var \Ivoz\Provider\Domain\Model\ResidentialDevice\ResidentialDeviceInterface
     */
    protected $residentialDevice;

    /**
     * @var \Ivoz\Provider\Domain\Model\RetailAccount\RetailAccountInterface
     */
    protected $retailAccount;


    use ChangelogTrait;

    /**
     * Constructor
     */
    protected function __construct(
        $sorceryId,
        $context,
        $disallow,
        $allow,
        $oneHundredRel,
        $t38Udptl,
        $t38UdptlEc,
        $t38UdptlMaxdatagram,
        $t38UdptlNat
    ) {
        $this->setSorceryId($sorceryId);
        $this->setContext($context);
        $this->setDisallow($disallow);
        $this->setAllow($allow);
        $this->setOneHundredRel($oneHundredRel);
        $this->setT38Udptl($t38Udptl);
        $this->setT38UdptlEc($t38UdptlEc);
        $this->setT38UdptlMaxdatagram($t38UdptlMaxdatagram);
        $this->setT38UdptlNat($t38UdptlNat);
    }

    abstract public function getId();

    public function __toString()
    {
        return sprintf(
            "%s#%s",
            "PsEndpoint",
            $this->getId()
        );
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function sanitizeValues()
    {
    }

    /**
     * @param null $id
     * @return PsEndpointDto
     */
    public static function createDto($id = null)
    {
        return new PsEndpointDto($id);
    }

    /**
     * @internal use EntityTools instead
     * @param PsEndpointInterface|null $entity
     * @param int $depth
     * @return PsEndpointDto|null
     */
    public static function entityToDto(EntityInterface $entity = null, $depth = 0)
    {
        if (!$entity) {
            return null;
        }

        Assertion::isInstanceOf($entity, PsEndpointInterface::class);

        if ($depth < 1) {
            return static::createDto($entity->getId());
        }

        if ($entity instanceof \Doctrine\ORM\Proxy\Proxy && !$entity->__isInitialized()) {
            return static::createDto($entity->getId());
        }

        /** @var PsEndpointDto $dto */
        $dto = $entity->toDto($depth-1);

        return $dto;
    }

    /**
     * Factory method
     * @internal use EntityTools instead
     * @param PsEndpointDto $dto
     * @return self
     */
    public static function fromDto(
        DataTransferObjectInterface $dto,
        \Ivoz\Core\Application\ForeignKeyTransformerInterface $fkTransformer
    ) {
        Assertion::isInstanceOf($dto, PsEndpointDto::class);

        $self = new static(
            $dto->getSorceryId(),
            $dto->getContext(),
            $dto->getDisallow(),
            $dto->getAllow(),
            $dto->getOneHundredRel(),
            $dto->getT38Udptl(),
            $dto->getT38UdptlEc(),
            $dto->getT38UdptlMaxdatagram(),
            $dto->getT38UdptlNat()
        );

        $self
            ->setFromDomain($dto->getFromDomain())
            ->setAors($dto->getAors())
            ->setCallerid($dto->getCallerid())
            ->setDirectMedia($dto->getDirectMedia())
            ->setDirectMediaMethod($dto->getDirectMediaMethod())
            ->setMailboxes($dto->getMailboxes())
            ->setNamedPickupGroup($dto->getNamedPickupGroup())
            ->setSendDiversion($dto->getSendDiversion())
            ->setSendPai($dto->getSendPai())
            ->setOutboundProxy($dto->getOutboundProxy())
            ->setTrustIdInbound($dto->getTrustIdInbound())
            ->setTerminal($fkTransformer->transform($dto->getTerminal()))
            ->setFriend($fkTransformer->transform($dto->getFriend()))
            ->setResidentialDevice($fkTransformer->transform($dto->getResidentialDevice()))
            ->setRetailAccount($fkTransformer->transform($dto->getRetailAccount()))
        ;

        $self->initChangelog();

        return $self;
    }

    /**
     * @internal use EntityTools instead
     * @param PsEndpointDto $dto
     * @return self
     */
    public function updateFromDto(
        DataTransferObjectInterface $dto,
        \Ivoz\Core\Application\ForeignKeyTransformerInterface $fkTransformer
    ) {
        Assertion::isInstanceOf($dto, PsEndpointDto::class);

        $this
            ->setSorceryId($dto->getSorceryId())
            ->setFromDomain($dto->getFromDomain())
            ->setAors($dto->getAors())
            ->setCallerid($dto->getCallerid())
            ->setContext($dto->getContext())
            ->setDisallow($dto->getDisallow())
            ->setAllow($dto->getAllow())
            ->setDirectMedia($dto->getDirectMedia())
            ->setDirectMediaMethod($dto->getDirectMediaMethod())
            ->setMailboxes($dto->getMailboxes())
            ->setNamedPickupGroup($dto->getNamedPickupGroup())
            ->setSendDiversion($dto->getSendDiversion())
            ->setSendPai($dto->getSendPai())
            ->setOneHundredRel($dto->getOneHundredRel())
            ->setOutboundProxy($dto->getOutboundProxy())
            ->setTrustIdInbound($dto->getTrustIdInbound())
            ->setT38Udptl($dto->getT38Udptl())
            ->setT38UdptlEc($dto->getT38UdptlEc())
            ->setT38UdptlMaxdatagram($dto->getT38UdptlMaxdatagram())
            ->setT38UdptlNat($dto->getT38UdptlNat())
            ->setTerminal($fkTransformer->transform($dto->getTerminal()))
            ->setFriend($fkTransformer->transform($dto->getFriend()))
            ->setResidentialDevice($fkTransformer->transform($dto->getResidentialDevice()))
            ->setRetailAccount($fkTransformer->transform($dto->getRetailAccount()));



        return $this;
    }

    /**
     * @internal use EntityTools instead
     * @param int $depth
     * @return PsEndpointDto
     */
    public function toDto($depth = 0)
    {
        return self::createDto()
            ->setSorceryId(self::getSorceryId())
            ->setFromDomain(self::getFromDomain())
            ->setAors(self::getAors())
            ->setCallerid(self::getCallerid())
            ->setContext(self::getContext())
            ->setDisallow(self::getDisallow())
            ->setAllow(self::getAllow())
            ->setDirectMedia(self::getDirectMedia())
            ->setDirectMediaMethod(self::getDirectMediaMethod())
            ->setMailboxes(self::getMailboxes())
            ->setNamedPickupGroup(self::getNamedPickupGroup())
            ->setSendDiversion(self::getSendDiversion())
            ->setSendPai(self::getSendPai())
            ->setOneHundredRel(self::getOneHundredRel())
            ->setOutboundProxy(self::getOutboundProxy())
            ->setTrustIdInbound(self::getTrustIdInbound())
            ->setT38Udptl(self::getT38Udptl())
            ->setT38UdptlEc(self::getT38UdptlEc())
            ->setT38UdptlMaxdatagram(self::getT38UdptlMaxdatagram())
            ->setT38UdptlNat(self::getT38UdptlNat())
            ->setTerminal(\Ivoz\Provider\Domain\Model\Terminal\Terminal::entityToDto(self::getTerminal(), $depth))
            ->setFriend(\Ivoz\Provider\Domain\Model\Friend\Friend::entityToDto(self::getFriend(), $depth))
            ->setResidentialDevice(\Ivoz\Provider\Domain\Model\ResidentialDevice\ResidentialDevice::entityToDto(self::getResidentialDevice(), $depth))
            ->setRetailAccount(\Ivoz\Provider\Domain\Model\RetailAccount\RetailAccount::entityToDto(self::getRetailAccount(), $depth));
    }

    /**
     * @return array
     */
    protected function __toArray()
    {
        return [
            'sorcery_id' => self::getSorceryId(),
            'from_domain' => self::getFromDomain(),
            'aors' => self::getAors(),
            'callerid' => self::getCallerid(),
            'context' => self::getContext(),
            'disallow' => self::getDisallow(),
            'allow' => self::getAllow(),
            'direct_media' => self::getDirectMedia(),
            'direct_media_method' => self::getDirectMediaMethod(),
            'mailboxes' => self::getMailboxes(),
            'named_pickup_group' => self::getNamedPickupGroup(),
            'send_diversion' => self::getSendDiversion(),
            'send_pai' => self::getSendPai(),
            '100rel' => self::getOneHundredRel(),
            'outbound_proxy' => self::getOutboundProxy(),
            'trust_id_inbound' => self::getTrustIdInbound(),
            't38_udptl' => self::getT38Udptl(),
            't38_udptl_ec' => self::getT38UdptlEc(),
            't38_udptl_maxdatagram' => self::getT38UdptlMaxdatagram(),
            't38_udptl_nat' => self::getT38UdptlNat(),
            'terminalId' => self::getTerminal() ? self::getTerminal()->getId() : null,
            'friendId' => self::getFriend() ? self::getFriend()->getId() : null,
            'residentialDeviceId' => self::getResidentialDevice() ? self::getResidentialDevice()->getId() : null,
            'retailAccountId' => self::getRetailAccount() ? self::getRetailAccount()->getId() : null
        ];
    }
    // @codeCoverageIgnoreStart

    /**
     * Set sorceryId
     *
     * @param string $sorceryId
     *
     * @return static
     */
    protected function setSorceryId($sorceryId)
    {
        Assertion::notNull($sorceryId, 'sorceryId value "%s" is null, but non null value was expected.');
        Assertion::maxLength($sorceryId, 40, 'sorceryId value "%s" is too long, it should have no more than %d characters, but has %d characters.');

        $this->sorceryId = $sorceryId;

        return $this;
    }

    /**
     * Get sorceryId
     *
     * @return string
     */
    public function getSorceryId()
    {
        return $this->sorceryId;
    }

    /**
     * Set fromDomain
     *
     * @param string $fromDomain
     *
     * @return static
     */
    protected function setFromDomain($fromDomain = null)
    {
        if (!is_null($fromDomain)) {
            Assertion::maxLength($fromDomain, 190, 'fromDomain value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->fromDomain = $fromDomain;

        return $this;
    }

    /**
     * Get fromDomain
     *
     * @return string | null
     */
    public function getFromDomain()
    {
        return $this->fromDomain;
    }

    /**
     * Set aors
     *
     * @param string $aors
     *
     * @return static
     */
    protected function setAors($aors = null)
    {
        if (!is_null($aors)) {
            Assertion::maxLength($aors, 200, 'aors value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->aors = $aors;

        return $this;
    }

    /**
     * Get aors
     *
     * @return string | null
     */
    public function getAors()
    {
        return $this->aors;
    }

    /**
     * Set callerid
     *
     * @param string $callerid
     *
     * @return static
     */
    protected function setCallerid($callerid = null)
    {
        if (!is_null($callerid)) {
            Assertion::maxLength($callerid, 100, 'callerid value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->callerid = $callerid;

        return $this;
    }

    /**
     * Get callerid
     *
     * @return string | null
     */
    public function getCallerid()
    {
        return $this->callerid;
    }

    /**
     * Set context
     *
     * @param string $context
     *
     * @return static
     */
    protected function setContext($context)
    {
        Assertion::notNull($context, 'context value "%s" is null, but non null value was expected.');
        Assertion::maxLength($context, 40, 'context value "%s" is too long, it should have no more than %d characters, but has %d characters.');

        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set disallow
     *
     * @param string $disallow
     *
     * @return static
     */
    protected function setDisallow($disallow)
    {
        Assertion::notNull($disallow, 'disallow value "%s" is null, but non null value was expected.');
        Assertion::maxLength($disallow, 200, 'disallow value "%s" is too long, it should have no more than %d characters, but has %d characters.');

        $this->disallow = $disallow;

        return $this;
    }

    /**
     * Get disallow
     *
     * @return string
     */
    public function getDisallow()
    {
        return $this->disallow;
    }

    /**
     * Set allow
     *
     * @param string $allow
     *
     * @return static
     */
    protected function setAllow($allow)
    {
        Assertion::notNull($allow, 'allow value "%s" is null, but non null value was expected.');
        Assertion::maxLength($allow, 200, 'allow value "%s" is too long, it should have no more than %d characters, but has %d characters.');

        $this->allow = $allow;

        return $this;
    }

    /**
     * Get allow
     *
     * @return string
     */
    public function getAllow()
    {
        return $this->allow;
    }

    /**
     * Set directMedia
     *
     * @param string $directMedia
     *
     * @return static
     */
    protected function setDirectMedia($directMedia = null)
    {
        $this->directMedia = $directMedia;

        return $this;
    }

    /**
     * Get directMedia
     *
     * @return string | null
     */
    public function getDirectMedia()
    {
        return $this->directMedia;
    }

    /**
     * Set directMediaMethod
     *
     * @param string $directMediaMethod
     *
     * @return static
     */
    protected function setDirectMediaMethod($directMediaMethod = null)
    {
        if (!is_null($directMediaMethod)) {
            Assertion::choice($directMediaMethod, [
                PsEndpointInterface::DIRECTMEDIAMETHOD_UPDATE,
                PsEndpointInterface::DIRECTMEDIAMETHOD_INVITE,
                PsEndpointInterface::DIRECTMEDIAMETHOD_REINVITE
            ], 'directMediaMethodvalue "%s" is not an element of the valid values: %s');
        }

        $this->directMediaMethod = $directMediaMethod;

        return $this;
    }

    /**
     * Get directMediaMethod
     *
     * @return string | null
     */
    public function getDirectMediaMethod()
    {
        return $this->directMediaMethod;
    }

    /**
     * Set mailboxes
     *
     * @param string $mailboxes
     *
     * @return static
     */
    protected function setMailboxes($mailboxes = null)
    {
        if (!is_null($mailboxes)) {
            Assertion::maxLength($mailboxes, 100, 'mailboxes value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->mailboxes = $mailboxes;

        return $this;
    }

    /**
     * Get mailboxes
     *
     * @return string | null
     */
    public function getMailboxes()
    {
        return $this->mailboxes;
    }

    /**
     * Set namedPickupGroup
     *
     * @param string $namedPickupGroup
     *
     * @return static
     */
    protected function setNamedPickupGroup($namedPickupGroup = null)
    {
        if (!is_null($namedPickupGroup)) {
            Assertion::maxLength($namedPickupGroup, 40, 'namedPickupGroup value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->namedPickupGroup = $namedPickupGroup;

        return $this;
    }

    /**
     * Get namedPickupGroup
     *
     * @return string | null
     */
    public function getNamedPickupGroup()
    {
        return $this->namedPickupGroup;
    }

    /**
     * Set sendDiversion
     *
     * @param string $sendDiversion
     *
     * @return static
     */
    protected function setSendDiversion($sendDiversion = null)
    {
        $this->sendDiversion = $sendDiversion;

        return $this;
    }

    /**
     * Get sendDiversion
     *
     * @return string | null
     */
    public function getSendDiversion()
    {
        return $this->sendDiversion;
    }

    /**
     * Set sendPai
     *
     * @param string $sendPai
     *
     * @return static
     */
    protected function setSendPai($sendPai = null)
    {
        $this->sendPai = $sendPai;

        return $this;
    }

    /**
     * Get sendPai
     *
     * @return string | null
     */
    public function getSendPai()
    {
        return $this->sendPai;
    }

    /**
     * Set oneHundredRel
     *
     * @param string $oneHundredRel
     *
     * @return static
     */
    protected function setOneHundredRel($oneHundredRel)
    {
        Assertion::notNull($oneHundredRel, 'oneHundredRel value "%s" is null, but non null value was expected.');

        $this->oneHundredRel = $oneHundredRel;

        return $this;
    }

    /**
     * Get oneHundredRel
     *
     * @return string
     */
    public function getOneHundredRel()
    {
        return $this->oneHundredRel;
    }

    /**
     * Set outboundProxy
     *
     * @param string $outboundProxy
     *
     * @return static
     */
    protected function setOutboundProxy($outboundProxy = null)
    {
        if (!is_null($outboundProxy)) {
            Assertion::maxLength($outboundProxy, 256, 'outboundProxy value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->outboundProxy = $outboundProxy;

        return $this;
    }

    /**
     * Get outboundProxy
     *
     * @return string | null
     */
    public function getOutboundProxy()
    {
        return $this->outboundProxy;
    }

    /**
     * Set trustIdInbound
     *
     * @param string $trustIdInbound
     *
     * @return static
     */
    protected function setTrustIdInbound($trustIdInbound = null)
    {
        $this->trustIdInbound = $trustIdInbound;

        return $this;
    }

    /**
     * Get trustIdInbound
     *
     * @return string | null
     */
    public function getTrustIdInbound()
    {
        return $this->trustIdInbound;
    }

    /**
     * Set t38Udptl
     *
     * @param string $t38Udptl
     *
     * @return static
     */
    protected function setT38Udptl($t38Udptl)
    {
        Assertion::notNull($t38Udptl, 't38Udptl value "%s" is null, but non null value was expected.');
        Assertion::choice($t38Udptl, [
            PsEndpointInterface::T38UDPTL_YES,
            PsEndpointInterface::T38UDPTL_NO
        ], 't38Udptlvalue "%s" is not an element of the valid values: %s');

        $this->t38Udptl = $t38Udptl;

        return $this;
    }

    /**
     * Get t38Udptl
     *
     * @return string
     */
    public function getT38Udptl()
    {
        return $this->t38Udptl;
    }

    /**
     * Set t38UdptlEc
     *
     * @param string $t38UdptlEc
     *
     * @return static
     */
    protected function setT38UdptlEc($t38UdptlEc)
    {
        Assertion::notNull($t38UdptlEc, 't38UdptlEc value "%s" is null, but non null value was expected.');
        Assertion::choice($t38UdptlEc, [
            PsEndpointInterface::T38UDPTLEC_NONE,
            PsEndpointInterface::T38UDPTLEC_FEC,
            PsEndpointInterface::T38UDPTLEC_REDUNDANCY
        ], 't38UdptlEcvalue "%s" is not an element of the valid values: %s');

        $this->t38UdptlEc = $t38UdptlEc;

        return $this;
    }

    /**
     * Get t38UdptlEc
     *
     * @return string
     */
    public function getT38UdptlEc()
    {
        return $this->t38UdptlEc;
    }

    /**
     * Set t38UdptlMaxdatagram
     *
     * @param integer $t38UdptlMaxdatagram
     *
     * @return static
     */
    protected function setT38UdptlMaxdatagram($t38UdptlMaxdatagram)
    {
        Assertion::notNull($t38UdptlMaxdatagram, 't38UdptlMaxdatagram value "%s" is null, but non null value was expected.');
        Assertion::integerish($t38UdptlMaxdatagram, 't38UdptlMaxdatagram value "%s" is not an integer or a number castable to integer.');
        Assertion::greaterOrEqualThan($t38UdptlMaxdatagram, 0, 't38UdptlMaxdatagram provided "%s" is not greater or equal than "%s".');

        $this->t38UdptlMaxdatagram = (int) $t38UdptlMaxdatagram;

        return $this;
    }

    /**
     * Get t38UdptlMaxdatagram
     *
     * @return integer
     */
    public function getT38UdptlMaxdatagram()
    {
        return $this->t38UdptlMaxdatagram;
    }

    /**
     * Set t38UdptlNat
     *
     * @param string $t38UdptlNat
     *
     * @return static
     */
    protected function setT38UdptlNat($t38UdptlNat)
    {
        Assertion::notNull($t38UdptlNat, 't38UdptlNat value "%s" is null, but non null value was expected.');
        Assertion::choice($t38UdptlNat, [
            PsEndpointInterface::T38UDPTLNAT_YES,
            PsEndpointInterface::T38UDPTLNAT_NO
        ], 't38UdptlNatvalue "%s" is not an element of the valid values: %s');

        $this->t38UdptlNat = $t38UdptlNat;

        return $this;
    }

    /**
     * Get t38UdptlNat
     *
     * @return string
     */
    public function getT38UdptlNat()
    {
        return $this->t38UdptlNat;
    }

    /**
     * Set terminal
     *
     * @param \Ivoz\Provider\Domain\Model\Terminal\TerminalInterface $terminal
     *
     * @return static
     */
    public function setTerminal(\Ivoz\Provider\Domain\Model\Terminal\TerminalInterface $terminal = null)
    {
        $this->terminal = $terminal;

        return $this;
    }

    /**
     * Get terminal
     *
     * @return \Ivoz\Provider\Domain\Model\Terminal\TerminalInterface
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * Set friend
     *
     * @param \Ivoz\Provider\Domain\Model\Friend\FriendInterface $friend
     *
     * @return static
     */
    public function setFriend(\Ivoz\Provider\Domain\Model\Friend\FriendInterface $friend = null)
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * Get friend
     *
     * @return \Ivoz\Provider\Domain\Model\Friend\FriendInterface
     */
    public function getFriend()
    {
        return $this->friend;
    }

    /**
     * Set residentialDevice
     *
     * @param \Ivoz\Provider\Domain\Model\ResidentialDevice\ResidentialDeviceInterface $residentialDevice
     *
     * @return static
     */
    public function setResidentialDevice(\Ivoz\Provider\Domain\Model\ResidentialDevice\ResidentialDeviceInterface $residentialDevice = null)
    {
        $this->residentialDevice = $residentialDevice;

        return $this;
    }

    /**
     * Get residentialDevice
     *
     * @return \Ivoz\Provider\Domain\Model\ResidentialDevice\ResidentialDeviceInterface
     */
    public function getResidentialDevice()
    {
        return $this->residentialDevice;
    }

    /**
     * Set retailAccount
     *
     * @param \Ivoz\Provider\Domain\Model\RetailAccount\RetailAccountInterface $retailAccount
     *
     * @return static
     */
    public function setRetailAccount(\Ivoz\Provider\Domain\Model\RetailAccount\RetailAccountInterface $retailAccount = null)
    {
        $this->retailAccount = $retailAccount;

        return $this;
    }

    /**
     * Get retailAccount
     *
     * @return \Ivoz\Provider\Domain\Model\RetailAccount\RetailAccountInterface
     */
    public function getRetailAccount()
    {
        return $this->retailAccount;
    }

    // @codeCoverageIgnoreEnd
}
