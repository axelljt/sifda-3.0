<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SidplaActividad;
use Minsal\sifdaBundle\Form\SidplaActividadType;

/**
 * SidplaActividad controller.
 *
 * @Route("/sidplaactividad")
 */
class SidplaActividadController extends Controller
{

    /**
     * Lists all SidplaActividad entities.
     *
     * @Route("/", name="sidplaactividad")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SidplaActividad')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SidplaActividad entity.
     *
     * @Route("/", name="sidplaactividad_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SidplaActividad:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SidplaActividad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sidplaactividad_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SidplaActividad entity.
     *
     * @param SidplaActividad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SidplaActividad $entity)
    {
        $form = $this->createForm(new SidplaActividadType(), $entity, array(
            'action' => $this->generateUrl('sidplaactividad_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SidplaActividad entity.
     *
     * @Route("/new", name="sidplaactividad_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SidplaActividad();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SidplaActividad entity.
     *
     * @Route("/{id}", name="sidplaactividad_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SidplaActividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SidplaActividad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SidplaActividad entity.
     *
     * @Route("/{id}/edit", name="sidplaactividad_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SidplaActividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SidplaActividad entity.');
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
    * Creates a form to edit a SidplaActividad entity.
    *
    * @param SidplaActividad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SidplaActividad $entity)
    {
        $form = $this->createForm(new SidplaActividadType(), $entity, array(
            'action' => $this->generateUrl('sidplaactividad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SidplaActividad entity.
     *
     * @Route("/{id}", name="sidplaactividad_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SidplaActividad:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SidplaActividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SidplaActividad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sidplaactividad_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SidplaActividad entity.
     *
     * @Route("/{id}", name="sidplaactividad_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SidplaActividad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SidplaActividad entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sidplaactividad'));
    }

    /**
     * Creates a form to delete a SidplaActividad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sidplaactividad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
