<?php

namespace OC\PlatformBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AntifloodValidator extends ConstraintValidator
{
	private $requestStack;
	private $em;

  	public function __construct(RequestStack $requestStack, EntityManagerInterface $em){ //On les incorpore à l'objet
    	$this->requestStack = $requestStack;
    	$this->em           = $em;
  	}

  	public function validate($value, Constraint $constraint){
    	// Pour récupérer l'objet Request tel qu'on le connait, il faut utiliser getCurrentRequest du service request_stack
    	$request = $this->requestStack->getCurrentRequest();

    	$ip = $request->getClientIp(); //Récupération de l'ip du posteur

    	// On vérifie si cette IP a déjà posté une candidature il y a moins de 15 secondes
    	$isFlood = $this->em
      	->getRepository('OCPlatformBundle:Application')
      	->isFlood($ip, 15); 

    if ($isFlood) {
      	$this->context->addViolation($constraint->message);
    }
  }
}