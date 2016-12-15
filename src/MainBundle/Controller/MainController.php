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
			if($isLeader)
			{
				$leader = $this->getUser();
			}
			else
			{
				$em = $this->getDoctrine()->getManager();
				$leader = $em->getRepository('MainBundle:User')->findOneByLead(true);
			}
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

        if ($form->isSubmitted() && $form->isValid()) {
            $medium->setPath($this->get('file_uploader')->upload($form->get('file')->getData()));
            $medium->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($medium);
            $em->flush($medium);

            return $this->redirectToRoute('medium_show', array('id' => $medium->getId()));
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
    	$em = $this->getDoctrine()->getManager();
    	$media = $em->getRepository('MainBundle:Medium')->findAllbut($this->getUser()->getMedium());
    	return $this->render('MainBundle::seePropositions.html.twig', array(
    		'media' => $media,
    		));
    }

    public function seePropositionsFalseAction(Medium $medium)
    {
    	unlink($medium->getPath());
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($medium);
        $em->flush($medium);

        return $this->render('MainBundle::seePropositions.html.twig', array(
    		'propositions' => $propositions,
    		));
    }

    public function seePropositionsValidAction(Medium $medium)
    {
    	
    }

    public function isLead($user)
    {
    	if($this->isAuthentified($user))
    		return $user->getLead();
    	else
    		return false;
    }

    public function isAuthentified($user)
    {
    	return $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ? true : false ;
    }
}
