<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Entity\Medium;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
	/*
	* REDIRECTION APRES INSCRIPTION
	*/
	public function registrationConfirmedAction()
	{
        return $this->redirectToRoute('main_homepage');
	}

	/*
	* HOMEPAGE
	*/
    public function indexAction()
    {
		if($this->isAuthentified($this->getUser()))
		{
			$user = $this->getUser();
			$isLeader = $this->isLead($this->getUser());
			$leader = $this->leader();
	        return $this->render('MainBundle::loggedHomepage.html.twig', array(
	        	'isLeader' => $isLeader,
	        	'leader' => $leader,
	        	'user' => $user,
	        	));
		}
	    else
	    {
	    	return $this->render('MainBundle::index.html.twig');
	    }
    }

    /*
    * GUESS
    */
    public function guessAction(Request $request)
    {
    	$medium = new Medium();
        $form = $this->createForm('MainBundle\Form\MediumType', $medium);
        $form->handleRequest($request);
        $isLead = $this->isLead($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $medium->setPath($this->get('file_uploader')->upload($form->get('file')->getData()));
            $medium->setUser($this->getUser());
            $medium->getUser()->setReject(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($medium);
            $em->flush($medium);

            return $this->redirectToRoute('main_homepage');
        }

        return $this->render('MainBundle::guess.html.twig', array(
            'medium' => $medium,
            'form' => $form->createView(),
        ));
    }

    /*
    * SEE PROPOSITIONS
    */
    public function seePropositionsAction()
    {
    	if($this->getUser()->getLead() == false)
    	{
    		return $this->redirectToRoute('main_homepage');
    	}

    	$em = $this->getDoctrine()->getManager();
    	$media = $em->getRepository('MainBundle:Medium')->findAllbut($this->getUser()->getMedium());
        $nothing = count($media) == 0 ? true : false;
    	return $this->render('MainBundle::seePropositions.html.twig', array(
    		'media' => $media,
            'nothing' => $nothing,
    		));
    }

    public function seePropositionsFalseAction(Medium $medium)
    {
    	if($this->getUser()->getLead() == false)
    	{
    		return $this->redirectToRoute('main_homepage');
    	}

        unlink($medium->getPath());
        $user = $medium->getUser();
        $user->setReject(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush($user);
        $em->remove($medium);
        $em->flush($medium);

        return $this->redirectToRoute('main_see_propositions');
    }

    public function seePropositionsValidAction(Medium $medium)
    {
    	if($this->getUser()->getLead() == false)
    	{
    		return $this->redirectToRoute('main_homepage');
    	}

    	$em = $this->getDoctrine()->getManager();
    	// passation du leadership
    	$leader = $this->leader();
    	$leader->setLead(false);
    	$em->persist($leader);
	    $em->flush($leader);
	    $newLead = $medium->getUser();
    	$newLead->setLead(true);
    	$newLead->setScore($newLead->getScore()+1);
	    $em->persist($newLead);
	    $em->flush($newLead);
    	// on reset tous le rejects
    	$users = $em->getRepository('MainBundle:User')->findAll();
    	foreach($users as $user)
    	{
    		$user->setReject(false);
    	}
    	// on efface tous les médias enregistrés jusqu'ici
    	$media = $em->getRepository('MainBundle:Medium')->findAll();
    	foreach($media as $medium)
    	{
    		unlink($medium->getPath());
    		// on clean la bdd
	    	$em->remove($medium);
	        $em->flush($medium);
    	}
        // on retourne à la page d'accueil
        return $this->redirectToRoute('main_homepage');
    }


    private function leader()
    {
    	$em = $this->getDoctrine()->getManager();
    	return $em->getRepository('MainBundle:User')->findOneByLead(true);
    }

    private function isLead($user)
    {
    	if($this->isAuthentified($user))
    		return $user->getLead();
    	else
    		return false;
    }

    private function isAuthentified($user)
    {
    	return $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ? true : false ;
    }
}
