<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SifdaRecursoServicio;
use Minsal\sifdaBundle\Form\SifdaRecursoServicioType;
use Doctrine\ORM\EntityRepository;

/**
 * SifdaRecursoServicio controller.
 *
 * @Route("/sifda/sifdarecursoservicio")
 */
class SifdaRecursoServicioController extends Controller
{

    /**
     * Lists all SifdaRecursoServicio entities.
     *
     * @Route("/lstRec/{idInf}", name="sifdarecursoservicio")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($idInf)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SifdaRecursoServicio')->findBy(array('idInformeOrdenTrabajo'=>$idInf)); 
        $informe = $em->getRepository('MinsalsifdaBundle:SifdaInformeOrdenTrabajo')->find($idInf);
            if (!$informe) {
                throw $this->createNotFoundException('Unable to find SifdaRecursoServicio entity.');
            } 
        return array(
            'entities' => $entities,
            'informe' => $informe,
        );
    }
    /**
     * Creates a new SifdaRecursoServicio entity.
     *
     * @Route("/create/{id}", name="sifdarecursoservicio_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaRecursoServicio:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $entity = new SifdaRecursoServicio();
        $form = $this->createCreateForm($entity, $id);
        $form->handleRequest($request);
        $parameters = $request->request->all();
        foreach($parameters as $p){
            $TipoRecursoDependencia = $p['idTipoRecursoDependencia'];
            $cantidad = $p['cantidad'];
        }
        $em = $this->getDoctrine()->getManager();
        $idTipoRecursoDependencia = $em->getRepository('MinsalsifdaBundle:SifdaTipoRecursoDependencia')->find($TipoRecursoDependencia);
        //ladybug_dump($idTipoRecursoDependencia);
        $costoUnitario = $idTipoRecursoDependencia->getCostoUnitario();
        $costoTotal = $cantidad * $costoUnitario;

        $informe = $em->getRepository('MinsalsifdaBundle:SifdaInformeOrdenTrabajo')->find($id);
        $entity->setIdInformeOrdenTrabajo($informe);

        if ($form->isValid()) {
            $entity->setCostoTotal($costoTotal);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            // si se ha hecho click al boton save_and_add 
            if ($form->get('save_and_add')->isClicked()) {
                return $this->redirect($this->generateUrl('sifdarecursoservicio_new', array('id' => $id)));
            }
            // si se ha hecho click al boton save
            else {
                return $this->redirect($this->generateUrl('sifdarecursoservicio', array('idInf' => $id)));
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SifdaRecursoServicio entity.
     *
     * @param SifdaRecursoServicio $entity The entity
     *  @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaRecursoServicio $entity, $id)
    {
        $form = $this->createForm(new SifdaRecursoServicioType(), $entity, array(
            'action' => $this->generateUrl('sifdarecursoservicio_create', array('id' => $id)),
            'method' => 'POST',
        ));

        $userId = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($userId);
        
        $form->add('idTipoRecursoDependencia', 'entity', array(
                    'required'      =>  true,
                    'label'         =>  'Recurso utilizado',    
                    'class'         =>  'MinsalsifdaBundle:SifdaTipoRecursoDependencia',
                            'query_builder' =>  function(EntityRepository $repositorio) use ( $usuario ){
                    return $repositorio
                            ->createQueryBuilder('rcv')
                            ->where('rcv.idDependenciaEstablecimiento = :depest')
                            ->setParameter(':depest', $usuario->getIdDependenciaEstablecimiento());
                    }));
        
        $form->add('save_and_add', 'submit', array('label' => 'Guardar y registrar nuevo recurso'));
        $form->add('save', 'submit', array('label' => 'Guardar y terminar'));

        return $form;
    }

    /**
     * Displays a form to create a new SifdaRecursoServicio entity.
     *
     * @Route("/new/{id}", name="sifdarecursoservicio_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new SifdaRecursoServicio();
        $informe = null;
        if ($id != 0) {
            $em = $this->getDoctrine()->getManager();
            $informe = $em->getRepository('MinsalsifdaBundle:SifdaInformeOrdenTrabajo')->find($id);
            
            if (!$informe) {
                throw $this->createNotFoundException('Unable to find SifdaInformeOrdenTrabajo entity.');
            }

            $entity->setIdInformeOrdenTrabajo($informe);
            $form   = $this->createCreateForm($entity, $id);
        }

        return array(
            'entity' => $entity,    
            'informe' => $informe,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SifdaRecursoServicio entity.
     *
     * @Route("/{id}", name="sifdarecursoservicio_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaRecursoServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaRecursoServicio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SifdaRecursoServicio entity.
     *
     * @Route("/{id}/edit", name="sifdarecursoservicio_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaRecursoServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaRecursoServicio entity.');
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
    * Creates a form to edit a SifdaRecursoServicio entity.
    *
    * @param SifdaRecursoServicio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaRecursoServicio $entity)
    {
        $form = $this->createForm(new SifdaRecursoServicioType(), $entity, array(
            'action' => $this->generateUrl('sifdarecursoservicio_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing SifdaRecursoServicio entity.
     *
     * @Route("/{id}", name="sifdarecursoservicio_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaRecursoServicio:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaRecursoServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaRecursoServicio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifdarecursoservicio_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SifdaRecursoServicio entity.
     *
     * @Route("/{id}", name="sifdarecursoservicio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaRecursoServicio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaRecursoServicio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifdarecursoservicio'));
    }

    /**
     * Creates a form to delete a SifdaRecursoServicio entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifdarecursoservicio_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
