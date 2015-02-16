<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SidplaSubactividad;
use Minsal\sifdaBundle\Form\SidplaSubactividadType;

/**
 * SidplaSubactividad controller.
 *
 * @Route("/sifda/sidplasubactividad")
 */
class SidplaSubactividadController extends Controller
{

    /**
     * Lists all SidplaSubactividad entities.
     *
     * @Route("/", name="sidplasubactividad")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SidplaSubactividad')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SidplaSubactividad entity.
     *
     * @Route("/", name="sidplasubactividad_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SidplaSubactividad:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SidplaSubactividad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sidplasubactividad_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SidplaSubactividad entity.
     *
     * @param SidplaSubactividad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SidplaSubactividad $entity)
    {
        $form = $this->createForm(new SidplaSubactividadType(), $entity, array(
            'action' => $this->generateUrl('sidplasubactividad_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SidplaSubactividad entity.
     *
     * @Route("/new", name="sidplasubactividad_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SidplaSubactividad();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SidplaSubactividad entity.
     *
     * @Route("/{id}", name="sidplasubactividad_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SidplaSubactividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SidplaSubactividad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SidplaSubactividad entity.
     *
     * @Route("/{id}/edit", name="sidplasubactividad_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SidplaSubactividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SidplaSubactividad entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a SidplaSubactividad entity.
    *
    * @param SidplaSubactividad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SidplaSubactividad $entity)
    {
        $form = $this->createForm(new SidplaSubactividadType(), $entity, array(
            'action' => $this->generateUrl('sidplasubactividad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SidplaSubactividad entity.
     *
     * @Route("/{id}", name="sidplasubactividad_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SidplaSubactividad:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SidplaSubactividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SidplaSubactividad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sidplasubactividad_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SidplaSubactividad entity.
     *
     * @Route("/{id}", name="sidplasubactividad_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SidplaSubactividad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SidplaSubactividad entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sidplasubactividad'));
    }

    /**
     * Creates a form to delete a SidplaSubactividad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sidplasubactividad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
