<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\AdvertSkill;

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
    	$em = $this->getDoctrine()->getManager(); //Enclenche les processus de Doctrine pour les entités (objets)

        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id); //Doctrine va chercher l'objet Advert en fonction de son id

        if(null===$advert){
            throw new NotFoundHttpException("L'annonce ".$id." n'existe pas");
        }

        $listApplications = $em->getRepository('OCPlatformBundle:Application')->findBy(array('advert'=>$advert)); //Cherche toutes les candidatures (objet Application) liées à l'annonce précédement cherchée
        $listAdvertSkills = $em->getRepository('OCPlatformBundle:AdvertSkill')->findBy(array('advert'=>$advert));
    	return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert'=>$advert, 'listApplications'=>$listApplications, 'listAdvertSkills'=>$listAdvertSkills));
    }

    public function addAction(Request $request)
    {
    	if($request->isMethod('POST')){
    		$request->getSession()->getFlashBag()->add('notice', 'annonce bien enregistrée'); //Message flash
    		return new RedirectToRoute('oc_platform_view', array('id'=>$advert->getId())); //Change de page sur l'annonce créee et affiche le message(a ajouter dans la view)
    	}

        $advert = new Advert();
        $advert->setTitle('Recherche développpeur Symfony2');
        $advert->setAuthor('alexandre');
        $advert->setMail('celebalane@gmail.com');
        $advert->setContent('Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…');

        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setMail('celebalane@gmail.com');
        $application1->setContent('Je suis ok!');

        $application2 = new Application();
        $application2->setAuthor('Mango');
        $application2->setMail('celebalane@gmail.com');
        $application2->setContent('Je suis le meilleur!');

        $advert->setImage($image);
        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

       

        $em = $this->getDoctrine()->getManager();
         $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll(); //Récupère les compétences

        foreach ($listSkills as $skill) {
            
            $advertSkill = new AdvertSkill();// On crée une nouvelle « relation entre 1 annonce et 1 compétence »
            $advertSkill->setAdvert($advert); //Lie l'annonce'
            $advertSkill->setSkill($skill); //Lie à l'annonce la compétence
            $advertSkill->setLevel('Expert');
            $em->persist($advertSkill);
        }

        $em->persist($advert); //persist = dire à doctrine de s'occuper de l'objet
        $em->persist($application1);
        $em->persist($application2);
        $em->flush();   //Un seul flush pour tout enregistrer dans la bdd

/*        $antispam = $this->get('oc_platform.antispam');
        $text = '...';
        if($antispam->isSpam($text)){
            throw new \Exception('Votre message a été détécté comme spam');
        }*/
    	return $this->render('OCPlatformBundle:Advert:add.html.twig',array('advert'=>$advert));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    	if($request->isMethod('POST')){
    		$request->getSession()->getFlashBag()->add('notice','annonce bien modifiée');
    		return new RedirectToRoute('oc_platform_view', array('id' => 5));
    	}

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();

        foreach ($listCategories as $category) {
            $advert->addCategory($category);  //Pour chaque annonce, on lui assigne toutes les categories contenues dans  listCategories
        }

        $em->flush();

    	return $this->render('OCPlatformBundle:Advert:edit.html.twig', array('advert'=>$advert));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category); //Enlève seulement les categories associées
        }

        $em->flush();

    	return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}