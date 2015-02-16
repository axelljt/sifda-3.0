<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\CtlCargo;
use Minsal\sifdaBundle\Form\CtlCargoType;

/**
 * CtlCargo controller.
 *
 * @Route("/ctlcargo")
 */
class CtlCargoController extends Controller
{

    /**
     * Lists all CtlCargo entities.
     *
     * @Route("/", name="ctlcargo")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:CtlCargo')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new CtlCargo entity.
     *
     * @Route("/", name="ctlcargo_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:CtlCargo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CtlCargo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ctlcargo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a CtlCargo entity.
     *
     * @param CtlCargo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CtlCargo $entity)
    {
        $form = $this->createForm(new CtlCargoType(), $entity, array(
            'action' => $this->generateUrl('ctlcargo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CtlCargo entity.
     *
     * @Route("/new", name="ctlcargo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CtlCargo();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a CtlCargo entity.
     *
     * @Route("/{id}", name="ctlcargo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlCargo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlCargo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CtlCargo entity.
     *
     * @Route("/{id}/edit", name="ctlcargo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlCargo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlCargo entity.');
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
    * Creates a form to edit a CtlCargo entity.
    *
    * @param CtlCargo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CtlCargo $entity)
    {
        $form = $this->createForm(new CtlCargoType(), $entity, array(
            'action' => $this->generateUrl('ctlcargo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CtlCargo entity.
     *
     * @Route("/{id}", name="ctlcargo_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:CtlCargo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlCargo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlCargo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ctlcargo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CtlCargo entity.
     *
     * @Route("/{id}", name="ctlcargo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:CtlCargo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CtlCargo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ctlcargo'));
    }

    /**
     * Creates a form to delete a CtlCargo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ctlcargo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
