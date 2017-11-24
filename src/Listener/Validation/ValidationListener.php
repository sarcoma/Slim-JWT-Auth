<?php

namespace Oacc\Listener\Validation;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Oacc\Utility\Error;
use Oacc\Listener\Validation\Exceptions\ValidationException;

/**
 * Class ValidationListener
 * @package Oacc\Validation
 */
abstract class ValidationListener implements EventSubscriber
{

    /**
     * @var Error
     */
    protected $error;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserValidationListener constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->error = new Error();
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ValidationException
     */
    public function checkErrors()
    {
        if ($this->error->hasErrors()) {
            throw new ValidationException($this->error);
        }
    }

    protected function fieldIsAvailable($criteria, $entityName, $entityId = null)
    {
        $entityRepository = $this->entityManager->getRepository($entityName);
        $entity = $entityRepository->findOneBy($criteria);
        switch ($entity) {
            case null:
                return true;
            case $entity->getId() === $entityId:
                return true;
            default:
                return !$entity;
        }
    }
}