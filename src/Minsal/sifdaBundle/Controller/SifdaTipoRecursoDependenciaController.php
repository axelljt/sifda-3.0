<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SifdaTipoRecursoDependencia;
use Minsal\sifdaBundle\Form\SifdaTipoRecursoDependenciaType;

/**
 * SifdaTipoRecursoDependencia controller.
 *
 * @Route("/sifda/sifdatiporecursodependencia")
 */
class SifdaTipoRecursoDependenciaController extends Controller
{

    /**
     * Lists all SifdaTipoRecursoDependencia entities.
     *
     * @Route("/", name="sifdatiporecursodependencia")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SifdaTipoRecursoDependencia')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SifdaTipoRecursoDependencia entity.
     *
     * @Route("/", name="sifdatiporecursodependencia_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaTipoRecursoDependencia:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SifdaTipoRecursoDependencia();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sifdatiporecursodependencia_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SifdaTipoRecursoDependencia entity.
     *
     * @param SifdaTipoRecursoDependencia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaTipoRecursoDependencia $entity)
    {
        $form = $this->createForm(new SifdaTipoRecursoDependenciaType(), $entity, array(
            'action' => $this->generateUrl('sifdatiporecursodependencia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SifdaTipoRecursoDependencia entity.
     *
     * @Route("/new", name="sifdatiporecursodependencia_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SifdaTipoRecursoDependencia();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SifdaTipoRecursoDependencia entity.
     *
     * @Route("/{id}", name="sifdatiporecursodependencia_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaTipoRecursoDependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaTipoRecursoDependencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SifdaTipoRecursoDependencia entity.
     *
     * @Route("/{id}/edit", name="sifdatiporecursodependencia_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaTipoRecursoDependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaTipoRecursoDependencia entity.');
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
    * Creates a form to edit a SifdaTipoRecursoDependencia entity.
    *
    * @param SifdaTipoRecursoDependencia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaTipoRecursoDependencia $entity)
    {
        $form = $this->createForm(new SifdaTipoRecursoDependenciaType(), $entity, array(
            'action' => $this->generateUrl('sifdatiporecursodependencia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SifdaTipoRecursoDependencia entity.
     *
     * @Route("/{id}", name="sifdatiporecursodependencia_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaTipoRecursoDependencia:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaTipoRecursoDependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaTipoRecursoDependencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifdatiporecursodependencia_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SifdaTipoRecursoDependencia entity.
     *
     * @Route("/{id}", name="sifdatiporecursodependencia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaTipoRecursoDependencia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaTipoRecursoDependencia entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifdatiporecursodependencia'));
    }

    /**
     * Creates a form to delete a SifdaTipoRecursoDependencia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifdatiporecursodependencia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
