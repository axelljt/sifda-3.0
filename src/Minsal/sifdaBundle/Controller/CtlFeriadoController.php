<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\CtlFeriado;
use Minsal\sifdaBundle\Form\CtlFeriadoType;

/**
 * CtlFeriado controller.
 *
 * @Route("/sifda/ctlferiado")
 */
class CtlFeriadoController extends Controller
{

    /**
     * Lists all CtlFeriado entities.
     *
     * @Route("/", name="sifda_ctlferiado")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MinsalsifdaBundle:CtlFeriado')->findAll();
        
        $idusuario=  $this->getUser()->getId();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        
        return array(
            'entities' => $entities,
            'usuario' => $usuario,
        );
    }
    /**
     * Creates a new CtlFeriado entity.
     *
     * @Route("/", name="sifda_ctlferiado_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:CtlFeriado:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CtlFeriado();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        $idusuario=  $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        
        $data=array(); 
        $parameters = $request->request->all();
        foreach($parameters as $p){
            $data = $p['fechaFestiva'];
        }
        
        //if ($form->isValid()) {            
            
            if($data){
                $this->establecerFechaFestiva($data);
                return $this->redirect($this->generateUrl('sifda_ctlferiado'));
            }
        //}    
        return array(
            'entity' => $entity,
            'usuario' => $usuario,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a CtlFeriado entity.
     *
     * @param CtlFeriado $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CtlFeriado $entity)
    {
        $form = $this->createForm(new CtlFeriadoType(), $entity, array(
            'action' => $this->generateUrl('sifda_ctlferiado_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Registrar dias festivos'));

        return $form;
    }

    /**
     * Displays a form to create a new CtlFeriado entity.
     *
     * @Route("/new", name="sifda_ctlferiado_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CtlFeriado();
        $form   = $this->createCreateForm($entity);
        
        $idusuario=  $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);

        return array(
            'entity' => $entity,
            'usuario' => $usuario,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a CtlFeriado entity.
     *
     * @Route("/{id}", name="sifda_ctlferiado_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlFeriado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlFeriado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CtlFeriado entity.
     *
     * @Route("/{id}/edit", name="sifda_ctlferiado_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlFeriado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlFeriado entity.');
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
    * Creates a form to edit a CtlFeriado entity.
    *
    * @param CtlFeriado $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CtlFeriado $entity)
    {
        $form = $this->createForm(new CtlFeriadoType(), $entity, array(
            'action' => $this->generateUrl('sifda_ctlferiado_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CtlFeriado entity.
     *
     * @Route("/{id}", name="sifda_ctlferiado_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:CtlFeriado:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:CtlFeriado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CtlFeriado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_ctlferiado_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CtlFeriado entity.
     *
     * @Route("/{id}", name="sifda_ctlferiado_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:CtlFeriado')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CtlFeriado entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_ctlferiado'));
    }

    /**
     * Creates a form to delete a CtlFeriado entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_ctlferiado_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Metodo que sirve para establecer las fechas festivas del anio.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $fechas
     *
     */
    private function establecerFechaFestiva(array $fechas)
    {
        foreach ($fechas as $fecha)
        {
            $entity = new CtlFeriado();
            $fechaActual = new \DateTime();
            
            $entity->setAnio($fechaActual->format('Y'));
            $entity->setFechaInicio(new \DateTime($fecha));
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
        }    
    }
}
