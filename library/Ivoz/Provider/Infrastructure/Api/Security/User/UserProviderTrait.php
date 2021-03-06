<?php

namespace Ivoz\Provider\Infrastructure\Api\Security\User;

use Ivoz\Provider\Domain\Model\Administrator\Administrator;
use Ivoz\Provider\Domain\Model\User\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Ivoz\Provider\Domain\Model\WebPortal\WebPortalRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

trait UserProviderTrait
{
    private $registry;
    private $requestStack;
    private $webPortalRepository;
    private $logger;
    private $managerName;
    private $entityClass;
    private $identifierField;

    public function __construct(
        ManagerRegistry $registry,
        RequestStack $requestStack,
        WebPortalRepository $webPortalRepository,
        LoggerInterface $logger,
        string $entityClass,
        string $identifierField,
        $managerName = null
    ) {
        $this->registry = $registry;
        $this->requestStack = $requestStack;
        $this->webPortalRepository = $webPortalRepository;
        $this->logger = $logger;
        $this->managerName = $managerName;

        $this->entityClass = $entityClass;
        $this->identifierField = $identifierField;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        try {
            $user = $this->findUser([
                $this->identifierField => $username
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }

        if (null === $user) {
            $errorMsg = sprintf('User "%s" not found.', $username);
            $this->logger->debug($errorMsg);
            throw new UsernameNotFoundException($errorMsg);
        }

        return $user;
    }

    abstract protected function findUser(array $criteria);

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof $this->entityClass) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $repository = $this->getRepository();

        // The user must be reloaded via the primary key as all other data
        // might have changed without proper persistence in the database.
        // That's the case when the user has been changed by a form with
        // validation errors.
        if (!$id = $this->getClassMetadata()->getIdentifierValues($user)) {
            throw new \InvalidArgumentException('You cannot refresh a user '.
                'from the EntityUserProvider that does not contain an identifier. '.
                'The user object has to be serialized with its own identifier '.
                'mapped by Doctrine.');
        }

        $refreshedUser = $repository->find($id);
        if (null === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with id %s not found', json_encode($id)));
        }

        return $refreshedUser;
    }

    private function getCurrentHost(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request->getSchemeAndHttpHost();
    }

    private function getObjectManager()
    {
        return $this->registry->getManager($this->managerName);
    }

    private function getRepository()
    {
        return $this->getObjectManager()->getRepository($this->entityClass);
    }

    private function getClassMetadata()
    {
        return $this->getObjectManager()->getClassMetadata($this->entityClass);
    }
}
