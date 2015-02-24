<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Minsal\sifdaBundle\Entity\SifdaEquipoTrabajo;
use Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo;
use Minsal\sifdaBundle\Form\SifdaEquipoTrabajoType;

/**
 * SifdaEquipoTrabajo controller.
 *
 * @Route("/sifda/equipotrabajo")
 */
class SifdaEquipoTrabajoController extends Controller
{

    /**
     * Lists all SifdaEquipoTrabajo entities.
     *
     * @Route("/", name="sifda_equipotrabajo")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SifdaEquipoTrabajo')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SifdaEquipoTrabajo entity.
     *
     * @Route("/create/{id}", name="sifda_equipotrabajo_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaEquipoTrabajo:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $entity = new SifdaEquipoTrabajo();
        $form = $this->createCreateForm($entity, $id);
        
        $em = $this->getDoctrine()->getManager();
        $idOrdenTrabajo = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($id);
        $entity->setIdOrdenTrabajo($idOrdenTrabajo);
        $entity->setResponsableEquipo(TRUE);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $data = $form->get('equipoTrabajo')->getData();
            if($data){
                $this->establecerEquipoTrabajo($idOrdenTrabajo, $data);
            }
            
            $equipoTrabajo = $em->getRepository('MinsalsifdaBundle:SifdaEquipoTrabajo')->findBy(array(
                                                                        'idOrdenTrabajo' => $idOrdenTrabajo
                    ));
            
            $correos = array();
            foreach ($equipoTrabajo as $value) {
                $correos[] = $value->getIdEmpleado()->getCorreoElectronico();
            }
            
            $texto = "";
            $texto.= 'Descripcion solicitud de servicio: '.$idOrdenTrabajo->getIdSolicitudServicio()->getDescripcion().' Descripcion'.$idOrdenTrabajo->getDescripcion();
            
            foreach ($correos as $correo){
                    
                $message = \Swift_Message::newInstance()
                           ->setSubject('Asignacion de nueva orden de trabajo')
                           ->setFrom('testing@sifda.gob.sv')
                           ->setTo($correo)
                           ->setBody($texto);
                $this->get('mailer')->send($message);     // then we send the message.
            
            }
            
            return $this->redirect($this->generateUrl('sifda_ordentrabajo_gestion'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SifdaEquipoTrabajo entity.
     *
     * @param SifdaEquipoTrabajo $entity The entity
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaEquipoTrabajo $entity, $id)
    {
        $form = $this->createForm(new SifdaEquipoTrabajoType(), $entity, array(
            'action' => $this->generateUrl('sifda_equipotrabajo_create', array('id' => $id)),
            'method' => 'POST',
        ));
        
        $userId = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($userId);
        
        $form->add('idEmpleado', 'entity', array(
                'label'     => 'Responsable',
                'class'     => 'MinsalsifdaBundle:CtlEmpleado',
                'query_builder' =>  function(EntityRepository $repositorio) use ($usuario) {
                    return $repositorio
                        ->createQueryBuilder('emp')
                        ->where('emp.idDependenciaEstablecimiento = :de')
                        ->setParameter(':de', $usuario->getIdDependenciaEstablecimiento()->getId());
            }
                ));
        
        $form->add('equipoTrabajo', 'entity', array(
                    'label'         =>  'Seleccione equipo de trabajo',
                    'class'         =>  'MinsalsifdaBundle:CtlEmpleado',
                    'multiple'  => true,
                    'expanded'  => true,
                    'required'  => false,
                    'mapped' => false,
                    'query_builder' =>  function(EntityRepository $repositorio) use ($usuario) {
                    return $repositorio
                        ->createQueryBuilder('emp')
                        ->where('emp.idDependenciaEstablecimiento = :de')
                        ->setParameter(':de', $usuario->getIdDependenciaEstablecimiento()->getId());
            }
                ))    
                ;
        
        $form->add('submit', 'submit', array('label' => 'Registrar personal'));

        return $form;
    }

    /**
     * Displays a form to create a new SifdaEquipoTrabajo entity.
     *
     * @Route("/new/{id}", name="sifda_equipotrabajo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new SifdaEquipoTrabajo();
        if ($id != 0) {
           $em = $this->getDoctrine()->getManager();
           $orden = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($id);
        
           if (!$orden) {
                throw $this->createNotFoundException('Unable to find SifdaOrdenTrabajo entity.');
            }
        }
        
        $userId = $this->getUser()->getId();
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($userId);
        
        $sql = "select distinct e.nombre|| ' ' ||e.apellido nombre, count(distinct eq.id_orden_trabajo) as atendidas,
                (select count(distinct eqp.id_orden_trabajo) 
                   from  sifda_equipo_trabajo eqp 
                   left outer join sifda_orden_trabajo ot on ot.id = eqp.id_orden_trabajo
                   where ot.id_estado = 2 and eqp.id_empleado = eq.id_empleado) as pendientes,
                (select count(distinct eqp.id_orden_trabajo) 
                   from  sifda_equipo_trabajo eqp 
                   left outer join sifda_orden_trabajo ot on ot.id = eqp.id_orden_trabajo
                  where ot.id_estado = 4 and eqp.id_empleado = eq.id_empleado) as finalizadas         
                from sifda_equipo_trabajo eq 
                full outer join ctl_empleado e on e.id = eq.id_empleado
                where e.id_dependencia_establecimiento = ?
                group by eq.id_empleado, e.nombre|| ' ' ||e.apellido
                order by count(distinct eq.id_orden_trabajo) desc,
                (select count(distinct eqp.id_orden_trabajo) 
                   from  sifda_equipo_trabajo eqp 
                   left outer join sifda_orden_trabajo ot on ot.id = eqp.id_orden_trabajo
                   where ot.id_estado = 2 and eqp.id_empleado = eq.id_empleado) desc,
                (select count(distinct eqp.id_orden_trabajo) 
                   from  sifda_equipo_trabajo eqp 
                   left outer join sifda_orden_trabajo ot on ot.id = eqp.id_orden_trabajo
                   where ot.id_estado = 4 and eqp.id_empleado = eq.id_empleado) desc;";
        
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('nombre','nombre');
        $rsm->addScalarResult('atendidas','atendidas');
        $rsm->addScalarResult('pendientes','pendientes');
        $rsm->addScalarResult('finalizadas','finalizadas');
        
        $empleados = $em->createNativeQuery($sql, $rsm)
                    ->setParameter(1, $usuario->getIdDependenciaEstablecimiento()->getId())
                    ->getResult();
        
        $form   = $this->createCreateForm($entity, $id);

        return array(
            'entity' => $entity,
            'orden' => $orden,
            'empleados' => $empleados,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SifdaEquipoTrabajo entity.
     *
     * @Route("/{id}", name="sifda_equipotrabajo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $ordenTrabajo = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($id);
        $responsable = $em->getRepository('MinsalsifdaBundle:SifdaEquipoTrabajo')->findOneBy(array(
                                                                            'idOrdenTrabajo' => $ordenTrabajo,
                                                                            'responsableEquipo' => 'TRUE'
                                                                                ));
        /*if (!$responsable) {
            throw $this->createNotFoundException(
                                'No se ha encontrado equipo de trabajo asignado a la orden'
                                );
        }*/
        
        $personal = $em->getRepository('MinsalsifdaBundle:SifdaEquipoTrabajo')->findBy(array(
                                                                            'idOrdenTrabajo' => $ordenTrabajo,
                                                                            'responsableEquipo' => 'FALSE'
                                                                                ));
        
        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'       => $responsable,
            'personal'     => $personal,
            'ordenTrabajo' => $ordenTrabajo,
        );
    }

    /**
     * Displays a form to edit an existing SifdaEquipoTrabajo entity.
     *
     * @Route("/{id}/edit", name="sifda_equipotrabajo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $ordenTrabajo = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($id);        
        if (!$ordenTrabajo) {
            throw $this->createNotFoundException('Unable to find SifdaOrdenTrabajo entity.');
        }
        
        $responsable = $em->getRepository('MinsalsifdaBundle:SifdaEquipoTrabajo')->findOneBy(array(
                                                                                'idOrdenTrabajo' => $ordenTrabajo,
                                                                                'responsableEquipo' => 'TRUE'        
                                                                                ));
        
        if (!$responsable) {
            throw $this->createNotFoundException('Unable to find SifdaEquipoTrabajo entity.');
        }
        
        $editForm = $this->createEditForm($responsable);
        
        return array(
            'ordenTrabajo' => $ordenTrabajo,
            'responsable'  => $responsable,
            'edit_form'    => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a SifdaEquipoTrabajo entity.
    *
    * @param SifdaEquipoTrabajo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaEquipoTrabajo $entity)
    {
        $form = $this->createForm(new SifdaEquipoTrabajoType(), $entity, array(
            'action' => $this->generateUrl('sifda_equipotrabajo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $userId = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($userId);
        
        $form->add('idEmpleado', 'entity', array(
                'label'     => 'Responsable',
                'class'     => 'MinsalsifdaBundle:CtlEmpleado',
                'query_builder' =>  function(EntityRepository $repositorio) use ($usuario) {
                    return $repositorio
                        ->createQueryBuilder('emp')
                        ->where('emp.idDependenciaEstablecimiento = :de')
                        ->setParameter(':de', $usuario->getIdDependenciaEstablecimiento()->getId());
                }
        ));
        
        $equipo = $em->getRepository('MinsalsifdaBundle:SifdaEquipoTrabajo')->findBy(array(
                                                                            'idOrdenTrabajo' => $entity->getIdOrdenTrabajo(),
                                                                            'responsableEquipo' => 'FALSE'
                                                                                ));        
        
        if (!$equipo) {
            throw $this->createNotFoundException('Unable to find SifdaEquipoTrabajo entity.');
        }
        
        $choices = array();
        foreach ($equipo as $empleado) {
            $choices[$empleado->getIdEmpleado()->getId()] = $empleado->getIdEmpleado();
        }
                
        $form->add('equipoTrabajo', 'entity', array(
                    'label'         =>  'Seleccione equipo de trabajo',
                    'class'         =>  'MinsalsifdaBundle:CtlEmpleado',
                    'multiple'  => true,
                    'expanded'  => true,
                    'required'  => false,
                    'mapped' => false,
                    'query_builder' =>  function(EntityRepository $repositorio) use ($usuario) {
                    return $repositorio
                        ->createQueryBuilder('emp')
                        ->where('emp.idDependenciaEstablecimiento = :de')
                        ->setParameter(':de', $usuario->getIdDependenciaEstablecimiento()->getId());
                    },
                    'data' => $choices
                ));

        $form->get('equipoTrabajo')->setData( $equipo );
        $form->add('submit', 'submit', array('label' => 'Reasignar orden de trabajo'));

        return $form;
    }
    /**
     * Edits an existing SifdaEquipoTrabajo entity.
     *
     * @Route("/{id}", name="sifda_equipotrabajo_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaEquipoTrabajo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaEquipoTrabajo')->find($id);
        $orden = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($entity->getIdOrdenTrabajo());
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaEquipoTrabajo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        $parameters = $request->request->all();
        
//        foreach($parameters as $p){
        $responsable = $parameters['minsal_sifdabundle_sifdaequipotrabajo']['idEmpleado'];
//        }
        $idEmpleado = $em->getRepository('MinsalsifdaBundle:CtlEmpleado')->find($responsable);
       // ladybug_dump($responsable);
        $responsableEquipo = $em->getRepository('MinsalsifdaBundle:SifdaEquipoTrabajo')->findOneBy(array(
                                                                            'idOrdenTrabajo' => $orden,
                                                                            'responsableEquipo' => 'TRUE'
                                                                                ));        
        $responsableEquipo->setIdOrdenTrabajo($orden);
        $responsableEquipo->setResponsableEquipo(TRUE);
        $responsableEquipo->setIdEmpleado($idEmpleado);
        
        if ($editForm->isValid()) {
            $em->merge($responsableEquipo);
            $em->flush();
            $data = $editForm->get('equipoTrabajo')->getData();
            if($data){
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('MinsalsifdaBundle:SifdaEquipoTrabajo')->findBy(array('idOrdenTrabajo' => $orden));

                






//                $emp=array(); 
//                foreach ($data as $empleado){
//                    foreach ($entity as $ord) {
//                        if($ord->getIdEmpleado()->getId() != $empleado->getId()){
//
//                            $ord->setIdOrdenTrabajo($orden);
//                            $ord->setResponsableEquipo(FALSE);
//                            $ord->setIdEmpleado($empleado);
//                            $em->persist($ord);
//                            $em->flush(); 
//                        }
//                    }
//                }
        
                //Se obtienen todas las ordenes de trabajo que tiene una determinada solicitud
//                foreach ($entity->getIdEmpleado() as $empleado) {
//                    $emp[] = $empleado->getId();
//                }
            }
            
            return $this->redirect($this->generateUrl('sifda_equipotrabajo_show', array('id' => $orden->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a SifdaEquipoTrabajo entity.
     *
     * @Route("/{id}", name="sifda_equipotrabajo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaEquipoTrabajo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaEquipoTrabajo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_equipotrabajo'));
    }

    /**
     * Creates a form to delete a SifdaEquipoTrabajo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_equipotrabajo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Metodo que sirve para establecer al equipo de trabajo de la orden de trabajo.
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo $idOrdenTrabajo
     * @param \Doctrine\Common\Collections\ArrayCollection $personal
     *
     */
    private function establecerEquipoTrabajo(\Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo $idOrdenTrabajo,
                                             \Doctrine\Common\Collections\ArrayCollection $personal)
    {
        foreach ($personal as $idEmpleado)
        {
            $entity = new SifdaEquipoTrabajo();
            $entity->setIdOrdenTrabajo($idOrdenTrabajo);
            $entity->setResponsableEquipo(FALSE);
            $entity->setIdEmpleado($idEmpleado);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
        }    
    }

    /** Metodo que sirve para reasignar al equipo de trabajo de la orden de trabajo.
     *
     * @param SifdaOrdenTrabajo $idOrdenTrabajo
     * @param \Doctrine\Common\Collections\ArrayCollection $personal
     *
     */
    private function reasignarEquipoTrabajo(SifdaOrdenTrabajo $idOrdenTrabajo,
                                             \Doctrine\Common\Collections\ArrayCollection $personal)
    {
        foreach ($personal as $idEmpleado)
        {
            $entity = new SifdaEquipoTrabajo();
            $entity->setIdOrdenTrabajo($idOrdenTrabajo);
            $entity->setResponsableEquipo(FALSE);
            $entity->setIdEmpleado($idEmpleado);
            $em = $this->getDoctrine()->getManager();
            $em->merge($entity);
            $em->flush();
        }    
    }    
}