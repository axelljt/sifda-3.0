<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\CtlTipoDependencia;
use Minsal\sifdaBundle\Form\CtlTipoDependenciaType;

/**
 * CtlTipoDependencia controller.
 *
 * @Route("/sifda/ctltipodependencia")
 */
class CtlTipoDependenciaController extends Controller
{

    /**
     * Lists all CtlTipoDependencia entities.
     *
     * @Route("/", name="sifda_ctltipodependencia")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:CtlTipoDependencia')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new CtlTipoDependencia entity.
     *
     * @Route("/", name="sifda_ctltipodependencia_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:CtlTipoDependencia:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CtlTipoDependencia();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_ctltipodependencia_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a CtlTipoDependencia entity.
     *
     * @param CtlTipoDependencia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CtlTipoDependencia $entity)
    {
        $form = $this->createForm(new CtlTipoDependenciaType(), $entity, array(
            'action' => $this->generateUrl('sifda_ctltipodependencia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CtlTipoDependencia entity.
     *
     * @Route("/new", name="sifda_ctltipodependencia_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CtlTipoDependencia();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a CtlTipoDependencia entity.
     *
     * @Route("/{id}", name="sifda_ctltipodependencia_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlTipoDependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlTipoDependencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CtlTipoDependencia entity.
     *
     * @Route("/{id}/edit", name="sifda_ctltipodependencia_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlTipoDependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlTipoDependencia entity.');
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
    * Creates a form to edit a CtlTipoDependencia entity.
    *
    * @param CtlTipoDependencia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CtlTipoDependencia $entity)
    {
        $form = $this->createForm(new CtlTipoDependenciaType(), $entity, array(
            'action' => $this->generateUrl('sifda_ctltipodependencia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CtlTipoDependencia entity.
     *
     * @Route("/{id}", name="sifda_ctltipodependencia_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:CtlTipoDependencia:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlTipoDependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlTipoDependencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_ctltipodependencia_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CtlTipoDependencia entity.
     *
     * @Route("/{id}", name="sifda_ctltipodependencia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:CtlTipoDependencia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CtlTipoDependencia entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_ctltipodependencia'));
    }

    /**
     * Creates a form to delete a CtlTipoDependencia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_ctltipodependencia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
