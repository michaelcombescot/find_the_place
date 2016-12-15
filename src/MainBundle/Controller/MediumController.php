<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Medium;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Medium controller.
 *
 */
class MediumController extends Controller
{
    /**
     * Lists all medium entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $media = $em->getRepository('MainBundle:Medium')->findAll();

        return $this->render('MainBundle:medium:index.html.twig', array(
            'media' => $media,
        ));
    }

    /**
     * Creates a new medium entity.
     *
     */
    public function newAction(Request $request)
    {
        $medium = new Medium();
        $form = $this->createForm('MainBundle\Form\MediumType', $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medium->setPath($this->get('file_uploader')->upload($form->get('file')->getData()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($medium);
            $em->flush($medium);

            return $this->redirectToRoute('medium_show', array('id' => $medium->getId()));
        }

        return $this->render('MainBundle:medium:new.html.twig', array(
            'medium' => $medium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a medium entity.
     *
     */
    public function showAction(Medium $medium)
    {
        $deleteForm = $this->createDeleteForm($medium);

        return $this->render('MainBundle:medium:show.html.twig', array(
            'medium' => $medium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing medium entity.
     *
     */
    public function editAction(Request $request, Medium $medium)
    {
        $deleteForm = $this->createDeleteForm($medium);
        $editForm = $this->createForm('MainBundle\Form\MediumType', $medium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid())
        {
            unlink($medium->getPath());
            $medium->setPath($this->get('file_uploader')->upload($editForm->get('file')->getData()));
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('main_homepage');
        }

        return $this->render('MainBundle:medium:edit.html.twig', array(
            'medium' => $medium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a medium entity.
     *
     */
    public function deleteAction(Request $request, Medium $medium)
    {
        $form = $this->createDeleteForm($medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            unlink($medium->getPath());
            $em = $this->getDoctrine()->getManager();
            $em->remove($medium);
            $em->flush($medium);
        }

        return $this->redirectToRoute('medium_index');
    }

    /**
     * Creates a form to delete a medium entity.
     *
     * @param Medium $medium The medium entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Medium $medium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('medium_delete', array('id' => $medium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
