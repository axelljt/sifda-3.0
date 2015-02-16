<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\CtlDependencia;
use Minsal\sifdaBundle\Form\CtlDependenciaType;

/**
 * CtlDependencia controller.
 *
 * @Route("/sifda/ctldependencia")
 */
class CtlDependenciaController extends Controller
{

    /**
     * Lists all CtlDependencia entities.
     *
     * @Route("/", name="sifda_ctldependencia")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:CtlDependencia')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new CtlDependencia entity.
     *
     * @Route("/", name="sifda_ctldependencia_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:CtlDependencia:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CtlDependencia();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_ctldependencia_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a CtlDependencia entity.
     *
     * @param CtlDependencia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CtlDependencia $entity)
    {
        $form = $this->createForm(new CtlDependenciaType(), $entity, array(
            'action' => $this->generateUrl('sifda_ctldependencia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CtlDependencia entity.
     *
     * @Route("/new", name="sifda_ctldependencia_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CtlDependencia();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
        /**
    * Ajax utilizado para buscar rango de fechas
    *  
    * @Route("/listarDependencia", name="sifda_ctldependencia_listar_dependencia")
    */
    public function listarDependenciaAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $idTipoDependencia = $this->get('request')->request->get('idTipoDependencia');
             $em = $this->getDoctrine()->getManager();
             $dependencias = $em->getRepository('MinsalsifdaBundle:CtlDependencia')->findBy(array('idTipoDependencia'=>$idTipoDependencia));
             $mensaje = $this->renderView('MinsalsifdaBundle:CtlDependencia:dependenciasShow.html.twig' , array('dependencias' =>$dependencias));
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    } 

    /**
     * Finds and displays a CtlDependencia entity.
     *
     * @Route("/{id}", name="sifda_ctldependencia_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlDependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlDependencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CtlDependencia entity.
     *
     * @Route("/{id}/edit", name="sifda_ctldependencia_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlDependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlDependencia entity.');
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
    * Creates a form to edit a CtlDependencia entity.
    *
    * @param CtlDependencia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CtlDependencia $entity)
    {
        $form = $this->createForm(new CtlDependenciaType(), $entity, array(
            'action' => $this->generateUrl('sifda_ctldependencia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CtlDependencia entity.
     *
     * @Route("/{id}", name="sifda_ctldependencia_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:CtlDependencia:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlDependencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlDependencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_ctldependencia_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CtlDependencia entity.
     *
     * @Route("/{id}", name="sifda_ctldependencia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:CtlDependencia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CtlDependencia entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_ctldependencia'));
    }

    /**
     * Creates a form to delete a CtlDependencia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_ctldependencia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
