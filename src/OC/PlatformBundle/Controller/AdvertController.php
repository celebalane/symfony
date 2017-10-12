<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdvertController extends Controller
{
    public function indexAction()
    {
    	/*$content = $this->get('templating')->render('OCPlatformBundle:Advert:index.html.twig',array('nom'=>'Mango'));*/
    	$url = $this->generateUrl('oc_platform_view', array('id'=>'5'),UrlGeneratorInterface::ABSOLUTE_URL);
        return new Response("L'url est : ".$url);
    }

    public function viewAction($id)
    {
    	return new Response("Affichage de l'annonce : ".$id);
    }
}