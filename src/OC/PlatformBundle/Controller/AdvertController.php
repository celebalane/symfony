<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdvertController extends Controller
{
    public function indexAction()
    {
    	$content = $this->get('templating')->render('OCPlatformBundle:Advert:index.html.twig',array('nom'=>'Mango'));
        return new Response($content);
    }

    public function byeAction()
    {
    	$content = $this->get('templating')->render('OCPlatformBundle:Advert:bye.html.twig',array('nom'=>'Petit chat'));
    	return new Response($content);
    }
}