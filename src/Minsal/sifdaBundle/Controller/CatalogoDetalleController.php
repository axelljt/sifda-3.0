<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\CatalogoDetalle;
use Minsal\sifdaBundle\Form\CatalogoDetalleType;

/**
 * CatalogoDetalle controller.
 *
 * @Route("/sifda/catalogodetalle")
 */
class CatalogoDetalleController extends Controller
{

    /**
     * Lists all CatalogoDetalle entities.
     *
     * @Route("/", name="sifda_catalogodetalle")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new CatalogoDetalle entity.
     *
     * @Route("/", name="sifda_catalogodetalle_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:CatalogoDetalle:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CatalogoDetalle();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_catalogodetalle_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a CatalogoDetalle entity.
     *
     * @param CatalogoDetalle $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CatalogoDetalle $entity)
    {
        $form = $this->createForm(new CatalogoDetalleType(), $entity, array(
            'action' => $this->generateUrl('sifda_catalogodetalle_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CatalogoDetalle entity.
     *
     * @Route("/new", name="sifda_catalogodetalle_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CatalogoDetalle();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a CatalogoDetalle entity.
     *
     * @Route("/{id}", name="sifda_catalogodetalle_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CatalogoDetalle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CatalogoDetalle entity.
     *
     * @Route("/{id}/edit", name="sifda_catalogodetalle_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CatalogoDetalle entity.');
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
    * Creates a form to edit a CatalogoDetalle entity.
    *
    * @param CatalogoDetalle $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CatalogoDetalle $entity)
    {
        $form = $this->createForm(new CatalogoDetalleType(), $entity, array(
            'action' => $this->generateUrl('sifda_catalogodetalle_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CatalogoDetalle entity.
     *
     * @Route("/{id}", name="sifda_catalogodetalle_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:CatalogoDetalle:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CatalogoDetalle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_catalogodetalle_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CatalogoDetalle entity.
     *
     * @Route("/{id}", name="sifda_catalogodetalle_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CatalogoDetalle entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_catalogodetalle'));
    }

    /**
     * Creates a form to delete a CatalogoDetalle entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_catalogodetalle_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
