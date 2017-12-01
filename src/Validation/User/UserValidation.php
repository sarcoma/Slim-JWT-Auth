<?php

namespace Oacc\Validation\User;

use Doctrine\ORM\EntityManager;
use Oacc\Entity\User;
use Oacc\Validation\Entity\EntityValidation;
use Oacc\Validation\Field\ValidateFields;

/**
 * Class UserValidation
 * @package Oacc\Validation
 */
class UserValidation extends EntityValidation
{
    /**
     * @var string $confirmPassword
     */
    private $confirmPassword;

    public function __construct($confirmPassword, EntityManager $entityManager)
    {
        parent::__construct();
        $this->confirmPassword = $confirmPassword;
        $this->entityManager = $entityManager;
    }

    /**
     * @param $entity
     */
    public function validate($entity)
    {
        if (!($entity instanceof User)) {
            return;
        }
        $user = $entity;
        $validate = new ValidateFields($this->error);
        $validate->addCheck(new UsernameValidation($user, $this->entityManager));
        $validate->addCheck(new EmailValidation($user, $this->entityManager));
        $validate->addCheck(new PasswordValidation($user, $this->confirmPassword));
        $validate->validate();
    }
}