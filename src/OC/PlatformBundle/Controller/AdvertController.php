<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
    	if ($page<1){
    		throw new NotFoundHTTPException("La page ".$page." n'existe pas");
    	}
    	$listAdverts = array(
      array(
        'title'   => 'Recherche développpeur Symfony',
        'id'      => 1,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Mission de webmaster',
        'id'      => 2,
        'author'  => 'Hugo',
        'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Offre de stage webdesigner',
        'id'      => 3,
        'author'  => 'Mathieu',
        'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
        'date'    => new \Datetime())
    );
    	return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts'=>$listAdverts));
    }

    public function menuAction($limit)
    {
    	$listAdverts = array(
    		array('id'=>2, 'title'=>'Recherche développeur Symfony'),
    		array('id'=>5,'title'=>'Mission de webmaster'),
    		array('id'=>9, 'title'=>'Offre de stage webdesigner'));
    	return $this->render('OCPlatformBundle:Advert:menu.html.twig', array('listAdverts'=>$listAdverts));
    }

    public function viewAction($id)
    {
    	$repository = $this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Advert');
        $advert = $repository->find($id);

        if(null===$advert){
            throw new NotFoundHttpException("L'annonce ".$id." n'existe pas");
        }
    	return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert'=>$advert));
    }

    public function addAction(Request $request)
    {
    	if($request->isMethod('POST')){
    		$request->getSession()->getFlashBag()->add('notice', 'annonce bien enregistrée');
    		return new RedirectToRoute('oc_platform_view', array('id'=>$advert->getId()));
    	}

        $advert = new Advert();
        $advert->setTitle('Recherche développpeur Symfony2');
        $advert->setAuthor('alexandre');
        $advert->setContent('Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…');

        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();

/*        $antispam = $this->get('oc_platform.antispam');
        $text = '...';
        if($antispam->isSpam($text)){
            throw new \Exception('Votre message a été détécté comme spam');
        }*/
    	return $this->render('OCPlatformBundle:Advert:add.html.twig',array('advert'=>$advert));
    }

    public function editAction($id, Request $request)
    {
    	if($request->isMethod('POST')){
    		$request->getSession()->getFlashBag()->add('notice','annonce bien modifiée');
    		return new RedirectToRoute('oc_platform_view', array('id' => 5));
    	}
    	$advert = array(
      		'title'   => 'Recherche développpeur Symfony',
      		'id'      => $id,
      		'author'  => 'Alexandre',
      		'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
      		'date'    => new \Datetime());
    	return $this->render('OCPlatformBundle:Advert:edit.html.twig', array('advert'=>$advert));
    }

    public function deleteAction($id)
    {
    	return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}