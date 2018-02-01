<?php

namespace OC\PlatformBundle\Validator;

use Symfony\Component\Validator\Constraint;

//@Annotation nécessaire pour que la classe soit utilisable dans les annotations
/**
 * @Annotation
 */
class Antiflood extends Constraint
{
  public $message = "Vous avez déjà posté un message il y a moins de 15 secondes, merci d'attendre un peu.";

  public function validateBy(){
  	return 'oc_platform_antiflood';
  }
}