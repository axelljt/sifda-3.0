<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\CtlEmpleado;
use Minsal\sifdaBundle\Form\CtlEmpleadoType;

/**
 * CtlEmpleado controller.
 *
 * @Route("/sifda/ctlempleado")
 */
class CtlEmpleadoController extends Controller
{

    /**
     * Lists all CtlEmpleado entities.
     *
     * @Route("/", name="ctlempleado")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:CtlEmpleado')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new CtlEmpleado entity.
     *
     * @Route("/", name="ctlempleado_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:CtlEmpleado:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CtlEmpleado();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ctlempleado_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a CtlEmpleado entity.
     *
     * @param CtlEmpleado $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CtlEmpleado $entity)
    {
        $form = $this->createForm(new CtlEmpleadoType(), $entity, array(
            'action' => $this->generateUrl('ctlempleado_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CtlEmpleado entity.
     *
     * @Route("/new", name="ctlempleado_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CtlEmpleado();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a CtlEmpleado entity.
     *
     * @Route("/{id}", name="ctlempleado_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlEmpleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlEmpleado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CtlEmpleado entity.
     *
     * @Route("/{id}/edit", name="ctlempleado_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlEmpleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlEmpleado entity.');
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
    * Creates a form to edit a CtlEmpleado entity.
    *
    * @param CtlEmpleado $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CtlEmpleado $entity)
    {
        $form = $this->createForm(new CtlEmpleadoType(), $entity, array(
            'action' => $this->generateUrl('ctlempleado_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CtlEmpleado entity.
     *
     * @Route("/{id}", name="ctlempleado_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:CtlEmpleado:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlEmpleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlEmpleado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ctlempleado_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CtlEmpleado entity.
     *
     * @Route("/{id}", name="ctlempleado_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:CtlEmpleado')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CtlEmpleado entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ctlempleado'));
    }

    /**
     * Creates a form to delete a CtlEmpleado entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ctlempleado_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
