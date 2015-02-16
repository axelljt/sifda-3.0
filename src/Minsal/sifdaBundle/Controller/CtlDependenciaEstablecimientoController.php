<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento;
use Minsal\sifdaBundle\Form\CtlDependenciaEstablecimientoType;

/**
 * CtlDependenciaEstablecimiento controller.
 *
 * @Route("/ctldependenciaestablecimiento")
 */
class CtlDependenciaEstablecimientoController extends Controller
{

    /**
     * Lists all CtlDependenciaEstablecimiento entities.
     *
     * @Route("/", name="ctldependenciaestablecimiento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:CtlDependenciaEstablecimiento')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new CtlDependenciaEstablecimiento entity.
     *
     * @Route("/", name="ctldependenciaestablecimiento_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:CtlDependenciaEstablecimiento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CtlDependenciaEstablecimiento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ctldependenciaestablecimiento_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a CtlDependenciaEstablecimiento entity.
     *
     * @param CtlDependenciaEstablecimiento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CtlDependenciaEstablecimiento $entity)
    {
        $form = $this->createForm(new CtlDependenciaEstablecimientoType(), $entity, array(
            'action' => $this->generateUrl('ctldependenciaestablecimiento_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CtlDependenciaEstablecimiento entity.
     *
     * @Route("/new", name="ctldependenciaestablecimiento_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CtlDependenciaEstablecimiento();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a CtlDependenciaEstablecimiento entity.
     *
     * @Route("/{id}", name="ctldependenciaestablecimiento_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlDependenciaEstablecimiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlDependenciaEstablecimiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CtlDependenciaEstablecimiento entity.
     *
     * @Route("/{id}/edit", name="ctldependenciaestablecimiento_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlDependenciaEstablecimiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlDependenciaEstablecimiento entity.');
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
    * Creates a form to edit a CtlDependenciaEstablecimiento entity.
    *
    * @param CtlDependenciaEstablecimiento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CtlDependenciaEstablecimiento $entity)
    {
        $form = $this->createForm(new CtlDependenciaEstablecimientoType(), $entity, array(
            'action' => $this->generateUrl('ctldependenciaestablecimiento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CtlDependenciaEstablecimiento entity.
     *
     * @Route("/{id}", name="ctldependenciaestablecimiento_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:CtlDependenciaEstablecimiento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlDependenciaEstablecimiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlDependenciaEstablecimiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ctldependenciaestablecimiento_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CtlDependenciaEstablecimiento entity.
     *
     * @Route("/{id}", name="ctldependenciaestablecimiento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:CtlDependenciaEstablecimiento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CtlDependenciaEstablecimiento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ctldependenciaestablecimiento'));
    }

    /**
     * Creates a form to delete a CtlDependenciaEstablecimiento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ctldependenciaestablecimiento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
