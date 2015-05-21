<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo;
use Minsal\sifdaBundle\Form\SifdaOrdenTrabajoType;
use Minsal\sifdaBundle\Entity\Vwetapassolicitud;

/**
 * SifdaOrdenTrabajo controller.
 *
 * @Route("/sifda/ordentrabajo")
 */
class SifdaOrdenTrabajoController extends Controller
{

    /**
     * Lists all SifdaOrdenTrabajo entities.
     *
     * @Route("/", name="sifda_ordentrabajo")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $userId = $this->getUser()->getId();
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($userId);
        $asignado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(2);

        $sql = "select ot.id id, ot.descripcion descripcion, ot.codigo codigo, 
                       ot.fecha_creacion fechacreacion, ot.fecha_finalizacion fechafinalizacion,
                       ot.observacion observacion
                from sifda_equipo_trabajo eq
                inner join ctl_empleado emp on eq.id_empleado = emp.id
                inner join sifda_orden_trabajo ot on eq.id_orden_trabajo = ot.id
                where emp.id = ?
                and ot.id_estado = ?
                order by ot.fecha_creacion desc, ot.fecha_finalizacion asc";
        
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('descripcion','descripcion');
        $rsm->addScalarResult('codigo','codigo');
        $rsm->addScalarResult('fechacreacion','fechacreacion');
        $rsm->addScalarResult('fechafinalizacion','fechafinalizacion');
        $rsm->addScalarResult('observacion','observacion');
        
        $entities = $em->createNativeQuery($sql, $rsm)
                    ->setParameter(1, $usuario->getIdEmpleado()->getId())
                    ->setParameter(2, $asignado->getId())
                    ->getResult();
        
        return array(
            'entities' => $entities,
            'usuario'  => $usuario,
        );
    }
    
        /**
     * Lists all SifdaOrdenTrabajo entities.
     *
     * @Route("/gestion_ordenestrabajo", name="sifda_ordentrabajo_gestion")
     * @Method("GET")
     * @Template()
     */
    public function gestionOrdenesAction()
     {
         $em = $this->getDoctrine()->getManager();
        $user=$this->getUser();
//        $objUser=$em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($user);
        $entities = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->findBy(array('idDependenciaEstablecimiento'=>$user->getIdDependenciaEstablecimiento()->getId()),
                                                                                        array(
                                                                                 'fechaCreacion' =>  'DESC'
                                                                                ));
        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Lists all SifdaSolicitudServicio entities.
     *
     * @Route("/seguimiento", name="sifda_seguimiento")
     * @Method("GET")
     * @Template()
     */
    public function seguimientoAction()
    {
        $idusuario = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        
        
        
        $entities = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(array(),
                                                                                       array(
                                                                                'fechaRecepcion' =>  'DESC',
                                                                                'fechaRequiere' => 'ASC'           
                                                                               ));
        
        $establecimiento= $em->getRepository('MinsalsifdaBundle:CtlEstablecimiento')->findAll();
        $tiposervicio= $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->findBy(array(
                                          'idDependenciaEstablecimiento' => $usuario->getIdDependenciaEstablecimiento()
                                        ));
        
        $asignado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(2);
        $solicitudesAprobadas = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(array(
                                                                                    'idEstado' => $asignado
                                                                                ),
                                                                                    array('fechaRecepcion' => 'DESC')
                                                                                );
        
        return array(
            'solicitudesAprobadas' => $solicitudesAprobadas,
            'establecimiento'=>$establecimiento,
            'tiposervicio'=>$tiposervicio,
            'usuario'=>$usuario,
        );
    }
    
    /**
     * Lists all SifdaOrdenTrabajo entities.
     *
     * @Route("/carga_laboral", name="sifda_ordentrabajo_cargalaboral")
     * @Method("GET")
     * @Template()
     */
    public function ordenAtendidaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $idusuario = $this->getUser()->getId();
        
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        
        $entities = $em->getRepository('MinsalsifdaBundle:Vwetapassolicitud')->findBy(array(
                                                                                    'depenEstab' => $usuario->getIdDependenciaEstablecimiento()
                                                                                ),
                                                                                    array('fchcreaOrden' => 'DESC'));
        
        return array(
            'entities' => $entities,
            'usuario'  => $usuario,
        );
    }
    
    /**
     * Creates a new SifdaOrdenTrabajo entity.
     *
     * @Route("/create/{id}", name="sifda_ordentrabajo_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaOrdenTrabajo:new.html.twig")
     */
     
        public function createAction(Request $request, $id)
    {
        $entity = new SifdaOrdenTrabajo();
        $form = $this->createCreateForm($entity, $id);
        $user = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        
        $usuario = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($user);
        $entity->setIdDependenciaEstablecimiento($usuario->getIdDependenciaEstablecimiento());
        
//        $idUser=$this->getUser()->getId();
//        $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idUser);
        
        $entity->setUser($usuario);
        
        //Obtener la solicitud de servicio que se va a atender
        $idSolicitudServicio = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        $entity->setIdSolicitudServicio($idSolicitudServicio);
        
        $form->handleRequest($request);
        $parameters = $request->request->all();
        foreach($parameters as $p){
            $idEtapa = $p['idEtapa'];
        }

        $idEstado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->findOneBy(array(
                                                            'descripcion'=>'Asignado'
                                                            ));
        
        
        //Generar el codigo que se le asignara a la orden de trabajo
        $codigo = $this->generarCodigoOrden($usuario->getIdDependenciaEstablecimiento());
        $entity->setCodigo($codigo);
        $validator = $this->get('validator');
        $errors = $validator->validate($entity);
        
        if (count($errors)<=0) {
            $entity->setFechaCreacion(new \DateTime("now"));
            $entity->setIdEstado($idEstado);
            $entity->setIdEtapa($idEtapa);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $estado = $idSolicitudServicio->getIdEstado()->getId();
            if($estado==1)
            {
                $objEstado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(2);
                $idSolicitudServicio->setIdEstado($objEstado);
                $em->merge($idSolicitudServicio);
                $em->flush();
            }   
            
            return $this->redirect($this->generateUrl('sifda_ordentrabajo_gestion'));
        }

        return array(
            'entity'    => $entity,
            'solicitud' => $idSolicitudServicio,
            'form'      => $form->createView(),
            'errors'    => $errors
        );
    }

    /**
     * Creates a form to create a SifdaOrdenTrabajo entity.
     *
     * @param SifdaOrdenTrabajo $entity The entity
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaOrdenTrabajo $entity, $id)
    {        
        $form = $this->createForm(new SifdaOrdenTrabajoType(), $entity, array(
                'action' => $this->generateUrl('sifda_ordentrabajo_create', array('id' => $id)),
                'method' => 'POST',
            ));
        
        $em = $this->getDoctrine()->getManager();
        
        /* Se obtiene la informacion del servicio solicitado con sus respectivas etapas            *
         * y subetapas, ademas de obtener en que estado se encuentran y se tiene a la              *
         * persona responsable de realizar la orden de trabajo                                     */
        $solicitudEtapa = $em->getRepository('MinsalsifdaBundle:Vwetapassolicitud')->findBy(array('idSolicitud'=>$id));
        
        //Se obtiene la informacion de la solicitud de servicio que se ha seleccionado                             
        $solicitud = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        
        $solicitudOrden=array(); 
        
        //Se obtienen todas las ordenes de trabajo que tiene una determinada solicitud
        foreach ($solicitudEtapa as $value) {
            if(!is_null($value->getIdOrden())){
                $solicitudOrden[] = $value->getIdOrden();
            }
        }
        
        //Si la solicitud de servicio no tiene ordenes de trabajo registradas
        if(!$solicitudOrden){
            
            //Llenado del combobox con las actividades que tienen jerarquia 1
            $form->add('idEtapa', 'entity', array(
                        'label'         =>  'Actividad a realizar',
                        'empty_value'=>'Seleccione una actividad',
                        'class'         =>  'MinsalsifdaBundle:SifdaRutaCicloVida',
                        'query_builder' =>  function(EntityRepository $repositorio) use ( $solicitud ){
                    return $repositorio
                            ->createQueryBuilder('rcv')
                            ->where('rcv.idTipoServicio = :tiposervicio')
                            ->andWhere('rcv.idEtapa IS NULL')
                            ->andWhere('rcv.jerarquia = 1')
                            ->setParameter(':tiposervicio', $solicitud->getIdTipoServicio());
                    }));
            
            $servicioSubetapa=array();         
            $subetapajerarquia=array();         
            
            //Se obtienen todas las subetapas del servicio que se esta atendiendo
            foreach ($solicitudEtapa as $value) {
                if(!is_null($value->getIdSubetapa())){
                    $servicioSubetapa[] = $value->getIdSubetapa();
                    $subetapajerarquia[] = $value->getJerarCicloVida();
                }
            }       
            
            //Si la etapa del tipo de servicio tiene subetapas        
            if ($servicioSubetapa){ 
                
                //Se agrega un tipo de campo de la subetapa sin asignar
                $form->add('idSubEtapa', 'entity', array(
                            'label'         =>  'Subactividad a realizar',
                            'class'         =>  'MinsalsifdaBundle:SifdaRutaCicloVida',
                            'empty_value'=>'Seleccione una subactividad',
                            'mapped' => false,
                            'choices' => array()
                        ));
              }
        }
        //Si a la solicitud de servicio se le han creado ordenes de trabajo
        elseif($solicitudOrden) {
            
            // Se obtiene el id de la orden de trabajo que se esta realizando actualmente
            $idOrdenActual = max($solicitudOrden);
            
            // Se obtiene toda la informacion de la orden de trabajo actual
            $ordenActual = $em->getRepository('MinsalsifdaBundle:Vwetapassolicitud')->findOneBy(array('idOrden'=>$idOrdenActual));
            
            //Si la orden de trabajo actual ha finalizado
            if($ordenActual->getDscEstado() == 'Finalizado'){
                
                // Llenado del combobox con las actividades que tienen la siguiente jerarquia 
                $form->add('idEtapa', 'entity', array(
                            'label'         =>  'Actividad a realizar',
                            'empty_value'=>'Seleccione una actividad',
                            'class'         =>  'MinsalsifdaBundle:SifdaRutaCicloVida',
                            'query_builder' =>  function(EntityRepository $repositorio) use ( $solicitud, $ordenActual){
                        return $repositorio
                                ->createQueryBuilder('rcv')
                                ->where('rcv.idTipoServicio = :tiposervicio')
                                ->andWhere('rcv.idEtapa IS NULL')
                                ->andWhere('rcv.jerarquia = :jerarquia')
                                ->setParameter(':tiposervicio', $solicitud->getIdTipoServicio())
                                ->setParameter(':jerarquia', $ordenActual->getJerarCicloVida() + 1);
                        }));
                
                //Se obtiene la informacion de la actividad del siguiente nivel o jerarquia        
                $siguienteEtapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->findOneBy(array(
                                                                                'idTipoServicio' => $solicitud->getIdTipoServicio(),
                                                                                'jerarquia'      => $ordenActual->getJerarCicloVida() + 1,
                                                                                'idEtapa'        => NULL       
                                                                                ));        
                
                $subetapaEtapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->findBy(array(
                                                                                'idTipoServicio' => $solicitud->getIdTipoServicio(),
                                                                                'idEtapa'        => $siguienteEtapa       
                                                                                ));        
                                                                                //ladybug_dump($subetapaEtapa);
                // Si la actividad del siguiente nivel tiene subactividades
                if($subetapaEtapa)
                {
                //Se agrega un tipo de campo de las subetapas del siguiente nivel 
                $form->add('idSubEtapa', 'entity', array(
                            'label'         =>  'Subactividad a realizar',
                            'class'         =>  'MinsalsifdaBundle:SifdaRutaCicloVida',
                            'empty_value'=>'Seleccione una subactividad',
                            'mapped' => false,
                            'choices' => array()
                        ));        
                }
                
            }
            else {
                $form->add('idEtapa', 'entity', array(
                        'label'         =>  'Actividad a realizar',
                        'empty_value'=>'Seleccione una actividad',
                        'class'         =>  'MinsalsifdaBundle:SifdaRutaCicloVida',
                        'query_builder' =>  function(EntityRepository $repositorio) use (  $solicitud, $ordenActual ){
                    return $repositorio
                            ->createQueryBuilder('rcv')
                            ->where('rcv.idTipoServicio = :tiposervicio')
                            ->andWhere('rcv.id != :etapa')
                            ->andWhere('rcv.jerarquia = :jerarquia')
                            ->setParameter(':jerarquia', $ordenActual->getJerarCicloVida())
                            ->setParameter(':etapa', $ordenActual->getIdCicloVida())
                            ->setParameter(':tiposervicio', $solicitud->getIdTipoServicio());
                    }));
                
                //////
            }
        }     
        
        $form->add('submit', 'submit', array('label' => 'Crear orden de trabajo'));

        return $form;
    }

    /**
     * Displays a form to create a new SifdaOrdenTrabajo entity.
     *
     * @Route("/new{id}", name="sifda_ordentrabajo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
         $entity = new SifdaOrdenTrabajo();
        if ($id != 0) {
           $em = $this->getDoctrine()->getManager();
           $solicitud = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
           
           if (!$solicitud) {
                throw $this->createNotFoundException('Unable to find SifdaOrdenTrabajo entity.');
            }
           
           /* Se obtiene la informacion del servicio solicitado con sus respectivas etapas            *
            * y subetapas, ademas de obtener en que estado se encuentran y se tiene a la              *
            * persona responsable de realizar la orden de trabajo                                     */
           $solicitudEtapa = $em->getRepository('MinsalsifdaBundle:Vwetapassolicitud')->findBy(array('idSolicitud'=>$id));
        }
        
        $servicioSubetapa=array();         
        $solicitudOrden=array(); 
        
        // Se obtienen todas las ordenes de trabajo que tiene una determinada solicitud
        // y las subetapas de un servicio solicitado
        foreach ($solicitudEtapa as $value) {
            if(!is_null($value->getIdOrden())){
                $solicitudOrden[] = $value->getIdOrden();
            }
            
            if(!is_null($value->getIdSubetapa())){
                $servicioSubetapa[] = $value->getIdSubetapa();
            }
        }
        
        $empleados = $em->getRepository('MinsalsifdaBundle:CtlEmpleado')->findAll();
        
        // Si al servicio solicitado se le han generado ordenes de trabajo
        if ($solicitudOrden) {
            // Se obtiene el id de la orden de trabajo que se esta realizando actualmente
            $idOrdenActual = max($solicitudOrden);

            // Se obtiene toda la informacion de la orden de trabajo actual
            $ordenActual = $em->getRepository('MinsalsifdaBundle:Vwetapassolicitud')->findOneBy(array('idOrden'=>$idOrdenActual));
            
            //Si la orden de trabajo actual ha finalizado
            if($ordenActual->getDscEstado() == 'Finalizado'){
                
                //Se obtiene la informacion de la actividad del siguiente nivel o jerarquia        
                $siguienteEtapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->findOneBy(array(
                                                                                'idTipoServicio' => $solicitud->getIdTipoServicio(),
                                                                                'jerarquia'      => $ordenActual->getJerarCicloVida() + 1,
                                                                                'idEtapa'        => NULL       
                                                                                ));        
                
                $subetapaEtapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->findBy(array(
                                                                                'idTipoServicio' => $solicitud->getIdTipoServicio(),
                                                                                'idEtapa'        => $siguienteEtapa       
                                                                                ));
                
                $dql = "SELECT count(rcv.jerarquia) cantidad
                                FROM MinsalsifdaBundle:SifdaRutaCicloVida rcv
                                INNER JOIN rcv.idTipoServicio tp
                                WHERE rcv.jerarquia = :jerarquia
                                and rcv.idTipoServicio = :tiposervicio
                                and rcv.idEtapa is NULL";

                $em = $this->getDoctrine()->getManager();
                $cantidad = $em->createQuery($dql)
                                     ->setParameter(':tiposervicio', $ordenActual->getIdCicloVida())
                                     ->setParameter(':jerarquia', $ordenActual->getIdCicloVida())
                                     ->getResult();
                
                $form   = $this->createCreateForm($entity, $id);
                
                return array(
                    'entity'           => $entity,
                    'solicitud'        => $solicitud,
                    'ordenActualId'    => $ordenActual->getId(),
                    'servicioSubetapa' => $servicioSubetapa,
                    'solicitudOrden'   => $solicitudOrden,
                    'subetapaEtapa'    => $subetapaEtapa,
                    'ordenActual'      => $ordenActual,
                    'empleados'        => $empleados,
                    'form'             => $form->createView(),
                    'errors'           => null,
                    'cantidad'         => $cantidad
                );
            }
            
            // Si la orden de trabajo actual no ha finalizado
            else {
                
                //Se obtiene la informacion de la actividad del siguiente nivel o jerarquia        
                $siguienteEtapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->findOneBy(array(
                                                                                'idTipoServicio' => $solicitud->getIdTipoServicio(),
                                                                                'jerarquia'      => $ordenActual->getJerarCicloVida() + 1,
                                                                                'idEtapa'        => NULL       
                                                                                ));        
                
                $subetapaEtapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->findBy(array(
                                                                                'idTipoServicio' => $solicitud->getIdTipoServicio(),
                                                                                'idEtapa'        => $ordenActual->getId()       
                                                                                ));
                
                $dql = "SELECT count(rcv.jerarquia) cantidad
                                FROM MinsalsifdaBundle:SifdaRutaCicloVida rcv
                                INNER JOIN rcv.idTipoServicio tp
                                WHERE rcv.jerarquia = :jerarquia
                                and rcv.idTipoServicio = :tiposervicio
                                and rcv.idEtapa is NULL";

                $em = $this->getDoctrine()->getManager();
                $cantidad = $em->createQuery($dql)
                                     ->setParameter(':tiposervicio', $ordenActual->getIdTipoServicio())
                                     ->setParameter(':jerarquia', $ordenActual->getJerarCicloVida())
                                     ->getResult();
                
                $form   = $this->createCreateForm($entity, $id);
                
                return array(
                    'entity'           => $entity,
                    'solicitud'        => $solicitud,
                    'ordenActualId'   => $ordenActual->getId(),
                    'servicioSubetapa' => $servicioSubetapa,
                    'solicitudOrden'   => $solicitudOrden,
                    'ordenActual'      => $ordenActual,
                    'subetapaEtapa'    => $subetapaEtapa,
                    'empleados'        => $empleados,
                    'form'             => $form->createView(),
                    'errors'           => null,
                    'cantidad'         => $cantidad
                );
            }
        }
        
        //Si al servicio solicitado no se le han generado ordenes de trabajo
        else {
            $form   = $this->createCreateForm($entity, $id);
            
            $ordenActualId = 0;
            
            return array(
                'entity'           => $entity,
                'solicitud'        => $solicitud,
                'ordenActualId'   => $ordenActualId,
                'servicioSubetapa' => $servicioSubetapa,
                'solicitudOrden'   => $solicitudOrden,
                'empleados'        => $empleados,
                'form'             => $form->createView(),
                'errors'           => null
            );
        }
        
    }
    
            /**
    * Ajax utilizado para buscar las dependencias segun su establecimiento
    *  
    * @Route("/find_dependencia", name="sifda_ordentrabajo_find_dependencia")
    */
    public function FindDependenciaAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $idEstablecimiento = $this->get('request')->request->get('idEstablecimiento');
             $em = $this->getDoctrine()->getManager();
             $dependencia_establecimiento = $em->getRepository('MinsalsifdaBundle:CtlDependenciaEstablecimiento')->findBy(array('idEstablecimiento'=>$idEstablecimiento));
             $var= array();
             foreach($dependencia_establecimiento as $d)
             {
                 $dependencias[] = $d->getIdDependencia();
             }
             $mensaje = $this->renderView('MinsalsifdaBundle:CtlDependencia:dependenciasShow.html.twig' , array('dependencias' =>$dependencias));
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    } 

    /**
     * Finds and displays a SifdaOrdenTrabajo entity.
     *
     * @Route("/{id}", name="sifda_ordentrabajo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaOrdenTrabajo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SifdaOrdenTrabajo entity.
     *
     * @Route("/{id}/edit", name="sifda_ordentrabajo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaOrdenTrabajo entity.');
        }

        $editForm = $this->createEditForm($entity);

        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a SifdaOrdenTrabajo entity.
    *
    * @param SifdaOrdenTrabajo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaOrdenTrabajo $entity)
    {
        $form = $this->createForm(new SifdaOrdenTrabajoType(), $entity, array(
            'action' => $this->generateUrl('sifda_ordentrabajo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        
//            $form->add('dependencia','entity', array(
//                    'mapped'=>false,
//                    'empty_value'=>'Seleccione una dependencia',
//                    'class'=>'MinsalsifdaBundle:CtlDependencia',
//                    'choices' => array()
//                ));
//                    
//            $form->add('establecimiento', 'entity', array(
//                    'label'         =>  'Establecimiento',
//                    'empty_value'=>'Seleccione un establecimiento',
//                    'class'         =>  'MinsalsifdaBundle:CtlEstablecimiento',
//                    'mapped' => false
//                ));
                
        $form->add('submit', 'submit', array('label' => 'Modificar'));

        return $form;
    }
    /**
     * Edits an existing SifdaOrdenTrabajo entity.
     *
     * @Route("/{id}", name="sifda_ordentrabajo_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaOrdenTrabajo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaOrdenTrabajo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        $user = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        
        $usuario = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($user);
        $entity->setIdDependenciaEstablecimiento($usuario->getIdDependenciaEstablecimiento());
        
        $parameters = $request->request->all();
        
        $idEtapa = $parameters['minsal_sifdabundle_sifdaordentrabajo']['idEtapa'];
        $entity->setIdEtapa($idEtapa);
        
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_ordentrabajo_gestion'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * Deletes a SifdaOrdenTrabajo entity.
     *
     * @Route("/{id}", name="sifda_ordentrabajo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaOrdenTrabajo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_ordentrabajo'));
    }

    /**
     * Creates a form to delete a SifdaOrdenTrabajo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_ordentrabajo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
    
    /**
    * Ajax utilizado para buscar rango de fechas
    *  
    * @Route("/ordentrabajo", name="sifda_ordentrabajo_buscar_ordenes")
    */
    public function buscarOrdenesAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             
            
            $fechaInicio = $this->get('request')->request->get('fechaInicio');
             $fechaFin = $this->get('request')->request->get('fechaFin');
             $em = $this->getDoctrine()->getManager();
             $ordenes = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->buscarOrdenXFecha($fechaInicio, $fechaFin);
//             $ladybug_dump( $ordenes);
             $mensaje = $this->renderView('MinsalsifdaBundle:SifdaOrdenTrabajo:ordenesShow.html.twig' , array('ordenes' =>$ordenes));
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    }

    /** 
     * Metodo que sirve para generar el codigo de la orden de trabajo
     * 
     * @param \Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento $idDependenciaEstablecimiento
     * 
     * @return string
     */
    public function generarCodigoOrden(\Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento $idDependenciaEstablecimiento) 
    {
        $codigo = "";
        $dependencia = $idDependenciaEstablecimiento->getIdDependencia()->getNombre();
        $establecimiento = $idDependenciaEstablecimiento->getIdEstablecimiento()->getNombre();
        
        $codigo.= substr($establecimiento, 0, 3);
        $codigo.= substr($dependencia, 0, 2);
        $codigo = strtoupper($codigo);        
        
        $fechaActual = new \DateTime();
        $dql = "SELECT count(u.codigo) cantidad
			  FROM MinsalsifdaBundle:SifdaOrdenTrabajo u
			  WHERE u.codigo LIKE :codigo";
                  
        $em = $this->getDoctrine()->getManager();
        $cantidadCodigos= $em->createQuery($dql)
                             ->setParameter(':codigo', $codigo.'___'.$fechaActual->format('y'))
                             ->getResult();
        $cantidad = $cantidadCodigos[0]['cantidad'] + 1;
        
        switch ($cantidad){
            case ($cantidad < 10):
                $codigo.= "00".$cantidad;
                break;
            case ($cantidad >= 10 and $cantidad < 100):
                $codigo.= "0".$cantidad;
                break;
            default:
                $codigo.= $cantidad;
                break;
        }
        
        $codigo.= $fechaActual->format('y');
        
        return $codigo;
    }
    
    /**
    * Ajax utilizado para buscar las subetapas segun la etapa que se ha seleccionado
    *  
    * @Route("/find_subetapa_orden", name="sifda_sifdaordentrabajo_find_subetapa")
    */
    public function FindSubEtapaOrdenAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            //Se obtiene el id de la etapa que se ha seleccionado 
            $idEtapa = $this->get('request')->request->get('idEtapa'); 
            
            // Se obtiene el id de la actividad actual del servicio solicitado
            $ordenActualId = $this->get('request')->request->get('ordenActualId');
             
             $em = $this->getDoctrine()->getManager();
             
             //Si el servicio solicitado tiene generadas ordenes de trabajo
             if ($ordenActualId != 0){
                 
                $entities = $em->getRepository('MinsalsifdaBundle:Vwetapassolicitud')->find($ordenActualId);
                
                $dql = "SELECT count(rcv.jerarquia) cantidad
                                FROM MinsalsifdaBundle:SifdaRutaCicloVida rcv
                                WHERE rcv.idEtapa = :idEtapa
                                AND rcv.jerarquia = :jerarquia";

                $em = $this->getDoctrine()->getManager();
                $cantidad = $em->createQuery($dql)
                                     ->setParameter(':idEtapa', $idEtapa)
                                     ->setParameter(':jerarquia', $entities->getJerarquiaSubetapa())
                                     ->getResult();
               // $cantidadEtapas = $cantidad[0]['cantidad'];
                
                $estadoEtapa = $entities->getDscEstado();
                
                if ($cantidad[0]['cantidad'] > 1){
                    //Se obtienen las subetapas de una determinada etapa
                    $idSubetapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->findByIdEtapa($Etapa);
                }
                else {
                    //Se obtienen las subetapas de una determinada etapa
                    $idSubetapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->findByIdEtapa($Etapa);
                }
             }
             
            else {
                //Se obtiene la entidad de la etapa seleccionada
                $Etapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($idEtapa);

                //Se obtienen las subetapas de una determinada etapa
                $idSubetapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->findBy(array(
                                                                                'idEtapa'   => $Etapa,
                                                                                'jerarquia' => 1
                                                                                ));
            }

             //Se realiza el llenado del combobox con las subetapas de la etapa seleccionada
             $mensaje = $this->renderView('MinsalsifdaBundle:SifdaRutaCicloVida:llenadoSubetapas.html.twig' , array('subetapas' =>$idSubetapa));
             
             $response = new JsonResponse();
             
             //Retorna las subetapas de la etapa seleccionada
             return $response->setData($mensaje);
        }
        
        else {   
            return new Response('0');   
        }       
    }
    
    /** Ajax utilizado vaciar combobox de subetaoas cuando no ha seleccionado una etapa
    *  
    * @Route("/find_vacio_subetapa", name="sifda_sifdaordentrabajo_select_subetapa")
    */
    public function subetapasVacioAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $mensaje = $this->renderView('MinsalsifdaBundle:SifdaRutaCicloVida:subetapasVacio.html.twig');
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    }
    
    /**
    * Ajax utilizado para buscar rango de fechas, dependencia y tipo de servicio
    *  
    * @Route("/buscar/seguimiento", name="sifda_seguimiento_buscar_solicitud")
    */
    public function buscarSeguimientoAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $fechaInicio = $this->get('request')->request->get('fechaInicio');
             $fechaFin = $this->get('request')->request->get('fechaFin');
             $establecimiento = $this->get('request')->request->get('establecimiento');
             $dependencia = $this->get('request')->request->get('dependencia');
             $tipoServicio = $this->get('request')->request->get('tipoServicio');
             
             $em = $this->getDoctrine()->getManager();
             $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->FechaSeguimiento($fechaInicio, $fechaFin, $establecimiento, $dependencia, $tipoServicio);
             $mensaje = $this->renderView('MinsalsifdaBundle:SifdaOrdenTrabajo:seguimientoShow.html.twig' , array('solicitudes' =>$solicitudes));
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    }
}
