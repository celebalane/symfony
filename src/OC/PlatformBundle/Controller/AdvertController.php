<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
    	if ($page<1){
    		throw new NotFoundHTTPException("La page ".$page." n'existe pas");
    	}
    	return $this->render('OCPlatformBundle:Advert:index.html.twig');
    }

    public function viewAction($id)
    {
    	return $this->render('OCPlatformBundle:Advert:view.html.twig', array('id'=>$id));
    }

    public function addAction(Request $request)
    {
    	if($request->isMethod('POST')){
    		$request->getSession()->getFlashBag()->add('notice', 'annonce bien enregistrée');
    		return new RedirectToRoute('oc_platform_view', array('id'=>5));
    	}
    	return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }

    public function editAction(Request $request)
    {
    	if($request->isMethod('POST')){
    		$request->getSession()->getFlashBag()->add('notice','annonce bien modifiée');
    		return new RedirectToRoute('oc_platform_view', array('id' => 5));
    	}
    	return $this->render('OCPlatformBundle:Advert:edit.html.twig');
    }

    public function deleteAction($id)
    {
    	return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}