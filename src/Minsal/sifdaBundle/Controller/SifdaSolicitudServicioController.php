<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SifdaSolicitudServicio;
use Minsal\sifdaBundle\Form\SifdaSolicitudServicioType;
use Symfony\Component\Validator\Constraints\Count;
use Doctrine\ORM\EntityRepository;

/**
 * SifdaSolicitudServicio controller.
 *
 * @Route("/sifda/solicitudservicio")
 */
class SifdaSolicitudServicioController extends Controller
{

    /**
     * Lists all SifdaSolicitudServicio entities.
     *
     * @Route("/sifda/redirection", name="sifda_solicitudservicio_redirection_email")
     * @Method("GET")
     * @Template()
     */
    
    public function redirectAction()
    {
        return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:FormEmail.html.twig');     
        
    }
  
    
    /**
     * Ajax utilizado para Enviar Correos
     *
     * @Route("/sifda/administrador/send/{texto}", name="sifda_solicitudservicio_send_email")
     * @Method("GET")
     * * @Template()
     */
    
     
    public function SendAction($texto)
    {
        
//        $isAjax = $this->get('Request')->isXMLhttpRequest();
//        if($isAjax){
            
//                $texto = $this->get('request')->request->get('texto');
//		$id=$this->get('request')->request->get('id');
       
        $correos=array('axelljt@gmail.com','karensita8421@gmail.com','anthony.huezo@gmail.com');
       
        foreach ($correos as $correo){
            
            
             $message = \Swift_Message::newInstance()
			->setSubject('Envio de prueba')
			->setFrom('testing@sifda.gob.sv')
			->setTo($correo)
			->setBody($texto)
#			->setBody($this->renderView('HelloBundle:Hello:email.txt.twig', array('name' => $name)))
    ;
        $this->get('mailer')->send($message);     // then we send the message.
            
        }
        
//        for($i=0;$i<$correos.length();$i++){
           
          
            
            
           
//       }
            
             
         return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:SendEmail.html.twig');
     }
//   }
    
    
    
    /**
     * Lists all SifdaSolicitudServicio entities.
     *
     * @Route("/{exito}",requirements={"exito"="\d+"}, defaults={"exito"=0}, name="sifda_solicitudservicio")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($exito)
    {
        $idusuario=$this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        
        
        
        $entities = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(array('idMedioSolicita' =>  array(5, 7, 8)),
                                                                                       array(
                                                                                'fechaRecepcion' =>  'DESC',
                                                                                'fechaRequiere' => 'ASC'
                                                                                            ));

        $estado=1;
//        $em = $this->getDoctrine()->getManager();
        
         $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->ContarSolicitudesIngresadas($estado);
//         $solicitudes2=  implode("",$solicitudes);
//         ladybug_dump($solicitudes);
         if(!$solicitudes)
             {
                throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
             }
            
                          
       $valor=  array_shift($solicitudes);
             
        return array(
            'entities' => $entities,
            'dependencias'=>$valor,
            'exito'=>$exito,
            'usuario'=>$usuario,
         
           
        );
    }
    
        /**
     * Lists all SifdaSolicitudServicio entities.
     *
     * @Route("/gestion_solicitudes", name="sifda_gestionSolicitudes")
     * @Method("GET")
     * @Template()
     */
    public function gestionSolicitudesAction()
    {
        $id_usuario=$this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        
        $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id_usuario);

        $establecimiento= $em->getRepository('MinsalsifdaBundle:CtlEstablecimiento')->findAll();
        $tiposervicio= $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->findBy(array('idDependenciaEstablecimiento'=>$usuario->getIdDependenciaEstablecimiento()));
        
        $objEstado1 = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(1);
        $ingresados = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(array(
                                                                                    'idEstado' => $objEstado1
                                                                                ),
                                                                                   array('fechaRecepcion' => 'DESC')
                                                                                );
        
        $objEstado2 = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(3);
        $rechazados = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(array(
                                                                                    'idEstado' => $objEstado2
                                                                                ),
                                                                                    array('fechaRecepcion' => 'DESC')
                                                                                );
       
        $objEstado3 = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(2);
        $asignados = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(array(
                                                                                    'idEstado' => $objEstado3
                                                                                ),
                                                                                    array('fechaRecepcion' => 'DESC')
                                                                                );
        $obCatalogo = $em->getRepository('MinsalsifdaBundle:Catalogo')->find(2);
        $Estados= $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->findBy(array(
            'idCatalogo'=> $obCatalogo
        ));
        
        return array(
            'entities' => $ingresados,
            'rechazados'=>$rechazados,
            'asignados'=>$asignados,
            'estados'=> $Estados,
            'usuario'=>$usuario,
            'establecimiento'=>$establecimiento,
            'tiposervicio'=>$tiposervicio,
        );
      
        
    }
    
    
        /**
     * Metodo Nuevo que .
     *
     * @Route("/solicitudes/ingresadas2", name="sifda_solicitudes_ingresadas2")
     * @Method("GET")Lista todas las solicitudes de Servicio.
     * @Template()
     */
    
    public function solicitudesIngNewAction()
    {
            
        $id_usuario=$this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        
         $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id_usuario);
         $tiposervicio= $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->findBy(array('idDependenciaEstablecimiento'=>$usuario->getIdDependenciaEstablecimiento()));
         
         $objEstado1 = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(1);
         $ingresados = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(['idEstado' => $objEstado1,'idDependenciaEstablecimiento'=>$usuario->getIdDependenciaEstablecimiento()
                                                                                    ],
                                                                                   array('fechaRecepcion' => 'DESC')
                                                                                );
          
        return array(
            'entities' => $ingresados,
            'usuario'=>$usuario,
            'establecimiento'=>$tiposervicio,
        );
        
    }
    
    
        /**
     * Metodo Nuevo que Enlaza la Pantalla de SolicitudesRechazadas.
     *
     * @Route("/solicitudes/rechazadas2", name="sifda_solicitudes_rechazadas2")
     * @Method("GET")Lista todas las solicitudes de Servicio.
     * @Template()
     */
    
    public function solicitudesRechNewAction()
    {
            
        $id_usuario=$this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        
         $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id_usuario);
         $tiposervicio= $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->findBy(array('idDependenciaEstablecimiento'=>$usuario->getIdDependenciaEstablecimiento()));
         
          $objEstado2 = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(3);
        $rechazados = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(['idEstado' => $objEstado2,'idDependenciaEstablecimiento'=>$usuario->getIdDependenciaEstablecimiento()
                                                                                    ],
                                                                                    array('fechaRecepcion' => 'DESC')
                                                                                );
                
        return array(
            'entities' => $rechazados,
            'usuario'=>$usuario,
            'establecimiento'=>$tiposervicio,
        );
        
    }
    
       /**
     * Metodo Nuevo que Enlaza la Pantalla de Reprogramacion.
     *
     * @Route("/solicitudes/reprogromadas", name="sifda_solicitudes_reprogramadas")
     * @Method("GET")Lista todas las solicitudes de Servicio.
     * @Template()
     */
    
    public function solicitudesReproNewAction()
    {
        
//        $id_usuario=1;
//        $em = $this->getDoctrine()->getManager();
//        
//         $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id_usuario);
//         
//         $vista=$em->getRepository('MinsalsifdaBundle:SifdaReprogracionServicio')->findAll();
        
        return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:solicitudesReproNew.html.twig');
    }
    
    
     /**
     * Metodo Nuevo que Enlaza la Pantalla de SolicitudesRechazadas.
     *
     * @Route("/solicitudes/finalizadas2", name="sifda_solicitudes_fializadas2")
     * @Method("GET")Lista todas las solicitudes de Servicio.
     * @Template()
     */
    
    public function solicitudesFinalNewAction()
    {
            
        $id_usuario=$this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        
         $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id_usuario);
         
         $vista=$em->getRepository('MinsalsifdaBundle:Vwetapassolicitud')->findAll();
         
         
         $tiposervicio= $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->findBy(array('idDependenciaEstablecimiento'=>$usuario->getIdDependenciaEstablecimiento()));
         
        $objEstado2 = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(4);
        $finalizadas = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(array(
                                                                                    'idEstado' => $objEstado2
                                                                                ),
                                                                                    array('fechaRecepcion' => 'DESC')
                                                                                );     
        return array(
            'entities' => $finalizadas,
            'usuario'=>$usuario,
            'establecimiento'=>$tiposervicio,
            'vista'=>$vista,
        );
        
    }
    
     /**
     * Metodo Nuevo que Enlaza la Pantalla de Solicitudes Consolidadas
     *
     * @Route("/solicitudes/consolidadas", name="sifda_solicitudes_consolidadas")
     * @Method("GET")Lista todas las solicitudes de Servicio.
     * @Template()
     */
    
    
    public function solicitudesConsolNewAction()
    {
        $id_usuario=$this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        
         $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id_usuario);
//         $tiposervicio= $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->findBy(array('idDependenciaEstablecimiento'=>$usuario->getIdDependenciaEstablecimiento()));
         
//        $objEstado2 = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(3);
//        $rechazados = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(['idEstado' => $objEstado2,'idDependenciaEstablecimiento'=>$usuario->getIdDependenciaEstablecimiento()
//                                                                                    ],
//                                                                                    array('fechaRecepcion' => 'DESC')
//                                                                                );
                
        return array(
//            'entities' => $rechazados,
            'usuario'=>$usuario,
//            'establecimiento'=>$tiposervicio,
        );
        
        
        
//        return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:solicitudesConsolNew.html.twig');
    }
    
     
    /**
    * Ajax utilizado para buscar rango de fechas
    *  
    * @Route("/buscarSolicitudes", name="sifda_solicitudservicio_buscar_solicitudes")
    */
    public function buscarSolicitudesAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $fechaInicio = $this->get('request')->request->get('fechaInicio');
             $fechaFin = $this->get('request')->request->get('fechaFin');
             $em = $this->getDoctrine()->getManager();
             $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->FechaSolicitud($fechaInicio, $fechaFin);
             $mensaje = $this->renderView('MinsalsifdaBundle:SifdaSolicitudServicio:solicitudesShow.html.twig' , array('solicitudes' =>$solicitudes));
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    }    
    
    
    
    /**
    * Ajax utilizado para buscar rango de fechas
    *  
    * @Route("/buscarSolicitudesIngresadas", name="sifda_solicitudservicio_buscar_solicitudes_ingresadas")
    */
    public function buscarSolicitudesIngresadasAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        $estado=1;
        if($isAjax){
             $fechaInicio = $this->get('request')->request->get('fechaInicio');
             $fechaFin = $this->get('request')->request->get('fechaFin');
             $tipoServicio = $this->get('request')->request->get('tipoServicio');
             $em = $this->getDoctrine()->getManager();
//             $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->FechaSolicitudIngresada($fechaInicio, $fechaFin,$tipoServicio);
             $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->buscarFechasSolicitudGenerico($fechaInicio, $fechaFin,$tipoServicio,$estado);   
            $tam= Count($solicitudes);
            if($tam>0)
                {
                  $mensaje = $this->renderView('MinsalsifdaBundle:SifdaSolicitudServicio:solicitudesIngShow.html.twig' , array('solicitudes' =>$solicitudes));
                  $response = new JsonResponse();
                  return $response->setData($mensaje);
                }else
            {    $response = new JsonResponse();
                 return $response->setData(array('val'=>0));
                
            }
             
            }
        else
            {    $response = new JsonResponse();
                 return $response->setData(array('val'=>0));
                
            } 
            
    }   
    
     /**
    * Ajax utilizado para buscar rango de fechas
    *  
    * @Route("/buscarSolicitudes2", name="sifda_solicitudservicio_buscar2_solicitudes")
    */
    public function buscarSolicitudesRechazadasAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        $estado=3;
        if($isAjax){
             $fechaInicio = $this->get('request')->request->get('fechaInicio');
             $fechaFin = $this->get('request')->request->get('fechaFin');
             $tipoServicio=$this->get('request')->request->get('tipoServicio');
             $em = $this->getDoctrine()->getManager();
             $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->buscarFechasSolicitudGenerico($fechaInicio, $fechaFin,$tipoServicio,$estado);
            
              $tam= Count($solicitudes);
             if($tam>0)
                 {
                     $mensaje = $this->renderView('MinsalsifdaBundle:SifdaSolicitudServicio:solicitudesRechShow.html.twig' , array('solicitudes' =>$solicitudes));
                     $response = new JsonResponse();
                     return $response->setData($mensaje);
                 }
             else{
                    $response = new JsonResponse();
                    return $response->setData(array('val'=>0));
                 
                 }
           } 
       else
            {    $response = new JsonResponse();
                 return $response->setData(array('val'=>0));
                
            }  
    }    
    
    
    /**
    * Ajax utilizado para buscar rango de fechas
    *  
    * @Route("/buscarSolicitudesFinalizadas2", name="sifda_solicitudservicio_buscar_finalizadas2")
    */
    public function buscarSolicitudesFinalNewAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        $estado=4;
        if($isAjax){
             $fechaInicio = $this->get('request')->request->get('fechaInicio');
             $fechaFin = $this->get('request')->request->get('fechaFin');
             $tipoServicio=$this->get('request')->request->get('tipoServicio');
             $em = $this->getDoctrine()->getManager();
             
             $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->buscarFechasSolicitudGenerico($fechaInicio, $fechaFin,$tipoServicio,$estado);
            
              $tam= Count($solicitudes);
             if($tam>0)
                 {
                     $mensaje = $this->renderView('MinsalsifdaBundle:SifdaSolicitudServicio:solicitudesRechShow.html.twig' , array('solicitudes' =>$solicitudes));
                     $response = new JsonResponse();
                     return $response->setData($mensaje);
                 }
             else{
                    $response = new JsonResponse();
                    return $response->setData(array('val'=>0));
                 
                 }
           } 
       else
            {    $response = new JsonResponse();
                 return $response->setData(array('val'=>0));
                
            }  
    }
    
    
    
    
    
    /**
    * Ajax utilizado para buscar rango de fechas
    *  
    * @Route("/buscarSolicitudes3", name="sifda_solicitudservicio_buscar3_solicitudes")
    */
    public function buscarSolicitudesAsignadasAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $fechaInicio = $this->get('request')->request->get('fechaInicio');
             $fechaFin = $this->get('request')->request->get('fechaFin');
             $em = $this->getDoctrine()->getManager();
             $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->FechaSolicitudAsignadas($fechaInicio, $fechaFin);
             $mensaje = $this->renderView('MinsalsifdaBundle:SifdaSolicitudServicio:solicitudesAsigShow.html.twig' , array('solicitudes' =>$solicitudes));
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    } 
    
    
    /**
     * Creates a new SifdaSolicitudServicio entity.
     *
     * @Route("/", name="sifda_solicitudservicio_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaSolicitudServicio:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new SifdaSolicitudServicio();
        $form = $this->createCreateForm($entity);
        
        $idEstado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->findOneBy(array('descripcion'=>'Ingresado'));
        
        $idMedioSolicita = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->findOneBy(array('descripcion'=>'Sistema'));
//        $idUser = $em->getRepository('MinsalsifdaBundle:FosUserUser')->findOneBy(array('username'=>'anthony'));
        $idUser=$this->getUser()->getId();
        $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idUser);
        $entity->setIdEstado($idEstado);
        $entity->setIdMedioSolicita($idMedioSolicita);
        $entity->setUser($usuario);
        $entity->setFechaRecepcion(new \DateTime());
        $form->handleRequest($request);
        
       
        $idDependencia=$this->get('request')->request->get('dependencia');
        
        
        //ladybug_dump($idDependencia);
        //Obtener la dependencia y establecimiento de la orden de trabajo
        $parameters = $request->request->all();
        //ladybug_dump($parameters);
        foreach($parameters as $p){
            $dependencia = $p['dependencia'];
            $establecimiento = $p['establecimiento'];
            $tipoServicio = $p['idTipoServicio'];
        }
        
        $tipoServicio2=$em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->find($tipoServicio);
        $entity->setIdTipoServicio($tipoServicio2);
        
        //$establecimiento = $form->get('establecimiento')->getData();
        //$dependencia = $form->get('dependencia')->getData();
        $idDependenciaEstablecimiento = $em->getRepository('MinsalsifdaBundle:CtlDependenciaEstablecimiento')->findOneBy(array(
                                                           'idEstablecimiento' => $establecimiento,
                                                           'idDependencia' => $dependencia,
//                                                           'idTipoServicio'=> $tipoServicio,
                                                            ));
        
        if (!$idDependenciaEstablecimiento) {
            throw $this->createNotFoundException('Unable to find CtlDependenciaEstablecimiento entity.');
        }
        $entity->setIdDependenciaEstablecimiento($idDependenciaEstablecimiento);
        
        $validator = $this->get('validator');
        $errors = $validator->validate($entity);
        $exito=0;
        if (count($errors)<=0) {
            $em->persist($entity);
            $em->flush();
            $exito=1;
            return $this->redirect($this->generateUrl('sifda_solicitudservicio', array('id' => $entity->getId(),'exito'=>$exito))); 
            
        }

      
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errors' => $errors,
            'exito'=>$exito,
        );
    }

    /**
     * Creates a form to create a SifdaSolicitudServicio entity.
     *
     * @param SifdaSolicitudServicio $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaSolicitudServicio $entity)
    {
        $form = $this->createForm(new SifdaSolicitudServicioType(), $entity, array(
            'action' => $this->generateUrl('sifda_solicitudservicio_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));
        
        return $form;
    }

    /**
     * Displays a form to create a new SifdaSolicitudServicio entity.
     *
     * @Route("/new", name="sifda_solicitudservicio_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SifdaSolicitudServicio();
        
        $form   = $this->createCreateForm($entity);
        
            return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errors' => null,
            
        );
    }
    
    /**
    * Ajax utilizado para buscar las dependencias segun su establecimiento
    *  
    * @Route("/find_dependencia_solicitud", name="sifda_solicitudservicio_find_dependencia")
    */
    public function FindDependenciaSolicitudAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $idEstablecimiento = $this->get('request')->request->get('idEstablecimiento');
             $em = $this->getDoctrine()->getManager();
             $dependencia_establecimiento = $em->getRepository('MinsalsifdaBundle:CtlDependenciaEstablecimiento')->findBy(array('idEstablecimiento'=>$idEstablecimiento));
             $dependencias=array();   
             
             foreach($dependencia_establecimiento as $d)
             {
                 $dependencias[] = $d->getIdDependencia()->getNombre();
                  
             }
             
             
             
             $dependencia = $em->getRepository('MinsalsifdaBundle:CtlDependencia')->findBy(array('nombre'=>$dependencias),array('nombre' => 'ASC'));
             
             
//             ladybug_dump($dependencia);
             
             $mensaje = $this->renderView('MinsalsifdaBundle:CtlDependencia:dependenciasShow.html.twig' , array('dependencias' =>$dependencia));
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    }
    
        /**
    * Ajax utilizado para mostrar seleccione una dependencia
    *  
    * @Route("/find_select_dependencia", name="sifda_solicitudservicio_select_dependencia")
    */
    public function SelectDependenciaAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $mensaje = $this->renderView('MinsalsifdaBundle:CtlDependencia:SelectDependencia.html.twig');
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    }
    
        /**
    * Ajax utilizado para buscar las dependencias segun su establecimiento
    *  
    * @Route("/find_tipo_servicio", name="sifda_solicitudservicio_find_tipo_servicio")
    */
    public function FindTiposervicioAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $idDependencia = $this->get('request')->request->get('IdDependencia');
             $em = $this->getDoctrine()->getManager();
             
             $dependencia_establecimiento = $em->getRepository('MinsalsifdaBundle:CtlDependenciaEstablecimiento')->findBy(array('idDependencia'=>$idDependencia));

             $SifdaTipoServicio = $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->findBy(array('idDependenciaEstablecimiento'=>$dependencia_establecimiento));

             $mensaje = $this->renderView('MinsalsifdaBundle:SifdaTipoServicio:tipoServiciosShow.html.twig' , array('tipoServicio' =>$SifdaTipoServicio));
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    }
    
        /**
    * Ajax que muestra selecciona un tipo de dependencia
    *  
    * @Route("/select_tipo_servicio", name="sifda_solicitudservicio_select_tipo_servicio")
    */
    public function SelectTiposervicioAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $mensaje = $this->renderView('MinsalsifdaBundle:SifdaTipoServicio:SelectTipoServicio.html.twig');
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    }    

    /**
     * Finds and displays a SifdaSolicitudServicio entity.
     *
     * @Route("/{id}", name="sifda_solicitudservicio_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    
    /**
     * Controlador para la busqueda de Estados.
     *
     * @Route("/showestado/{id}", name="sifda_solicitudservicio_show_estado")
     * @Method("GET")
     * @Template()
     */
    public function showEstadoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    
    /*Controlador que permite recuperar la informacion de un objeto especifico de la bd*/
    
    /**
     * Controlador para la busqueda de Estados.
     *
     * @Route("/buscar_estado/{id}", name="sifda_solicitudservicio_buscar_estado")
     * @Method("GET")
     * @Template()
     */
    public function BuscarEstadoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        $dependencia=$em->getRepository('MinsalsifdaBundle:CtlDependencia')->findBy(array('id'=>$id));
        //ladybug_dump($dependencia);

        if (!$entity) {
            throw $this->createNotFoundException('Solicitud de Servicio con id: '.$id.'No encontrado');
        }

//        $deleteForm = $this->createDeleteForm($id);
        
          $estado=$entity->getIdEstado();
          
          
          if($estado == "Ingresado")
          
              return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:showEstado2.html.twig' , array('entity' =>$entity, 'dependencia'=>$dependencia));


          elseif($estado == "Asignado")
                return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:showEstado3.html.twig' , array('entity' =>$entity, 'dependencia'=>$dependencia));
          
          elseif($estado == "Rechazado")
                return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:showEstado4.html.twig' , array('entity' =>$entity, 'dependencia'=>$dependencia));
          
          elseif($estado == "Finalizado")
                return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:showEstado5.html.twig' , array('entity' =>$entity, 'dependencia'=>$dependencia));

    }
    
    
       
    /**
     * Controlador para la busqueda de Informacion de la Solicitud.
     *
     * @Route("/buscar_informacion_solicitud/{id}", name="sifda_solicitudservicio_buscar_informacion")
     * @Method("GET")
     * @Template()
     */
    public function BuscarInfoSolicitudAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $solicitud = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        
        if (!$solicitud) {
            throw $this->createNotFoundException('Solicitud de Servicio con id: '.$id.'No encontrado');
        }
        
        $idestablecimiento=$solicitud->getIdDependenciaEstablecimiento();
        $establecimiento=$em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($idestablecimiento);
        
        
        return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:solicitudesIngNewShow.html.twig',
                array('solicitud'=>$solicitud,
                        'establecimiento'=>$establecimiento));
    }
    
    
    
    /*Fin de la prueba recuperar estado*/

    
    /*Controlador que permite recuperar el nombre de la dependencia*/
    /**
     * Controlador para la busqueda de Estados.
     *
     * @Route("/buscar_dependencia1/{id}", name="sifda_solicitudservicio_buscar_dependencia1")
     * @Method("GET")
     * @Template()
     */
    
    public function GetDependenciasAction($id){
        
        $em = $this->getDoctrine()->getManager();
        
        $dependencia=$em->getRepository('MinsalsifdaBundle:CtlDependencia')->findBy(array('id'=>$id));
        ladybug_dump($dependencia);
        if(!$dependencia){
            return $this->render('MinsalsifdaBundle:Default:index.html.twig',array('dependencia'=>0));
            //throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity');
        }
            
        return $this->render('MinsalsifdaBundle:Default:index.html.twig',array('dependencia'=>$dependencia));
        
        }
        
     /*controlador que permite recuperar cuantas solicitudes estan Ingresados
     * @Route("/contar_dependencia", name="sifda_solicitudservicio_contar_dependencia")
     * @Method("GET")
     * @Template()
     */
        
    public function GetNumeroSolicitudesIngresadas()
     {
        $estado="Ingresado";
        $em = $this->getDoctrine()->getManager();
        
         $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->ContarSolicitudesIngresadas($estado);
         
         if(!$solicitudes)
             {
                throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
             }
          return $this->render('MinsalsifdaBundle:Default:index.html.twig',array('dependencia'=>$solicitudes));   
     }    
    
    /**
     * Displays a form to edit an existing SifdaSolicitudServicio entity.
     *
     * @Route("/{id}/edit", name="sifda_solicitudservicio_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
        }
        if($entity->getIdEstado()->getId() == 1){
            $editForm = $this->createEditForm($entity);
            $editForm->get('establecimiento')->setData($entity
                                                        ->getIdDependenciaEstablecimiento()
                                                        ->getIdEstablecimiento()
                                                       );
            $editForm->get('dependencia')->setData($entity
                                                    ->getIdDependenciaEstablecimiento()
                                                    ->getIdDependencia()
                                                   );

            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        else {
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            );
        }
    }

    /**
    * Creates a form to edit a SifdaSolicitudServicio entity.
    *
    * @param SifdaSolicitudServicio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaSolicitudServicio $entity)
    {
        $form = $this->createForm(new SifdaSolicitudServicioType(), $entity, array(
            'action' => $this->generateUrl('sifda_solicitudservicio_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

    //Asignando un valor a un control especifico.    
        
       $form->add('establecimiento', 'entity', array(
                    'label'         =>  'Establecimiento',
                    'empty_value'=>'Seleccione un establecimiento',
                    'class'         =>  'MinsalsifdaBundle:CtlEstablecimiento',
                    'mapped' => false,
                    'data' => $entity->getIdDependenciaEstablecimiento()->getIdEstablecimiento()
               
                ));
        
       
        
       $form->add('dependencia', 'entity', array(
                    'label'         =>  'dependencia',
                    'empty_value'=>'Seleccione un dependencia',
                    'class'         =>  'MinsalsifdaBundle:CtlDependencia',
                    'mapped' => false,
                    'data' => $entity->getIdDependenciaEstablecimiento()->getIdDependencia()
               
                ));
    
       $form->add('idTipoServicio', 'entity', array(
                    'label'         =>  'Tipo de servicio',
                    'empty_value'=>'Seleccione un tipo de servicio',
                    'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                    'mapped' => false,
                    'data' => $entity->getIdTipoServicio()
               
                ));
    
       
       
        
        $form->add('submit', 'submit', array('label' => 'Modificar solicitud'));

        return $form;
    }
    /**
     * Edits an existing SifdaSolicitudServicio entity.
     *
     * @Route("/{id}", name="sifda_solicitudservicio_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaSolicitudServicio:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        //Obtener la dependencia y establecimiento de la orden de trabajo
        $establecimiento = $editForm->get('establecimiento')->getData();
        $dependencia = $editForm->get('dependencia')->getData();
        $idDependenciaEstablecimiento = $em->getRepository('MinsalsifdaBundle:CtlDependenciaEstablecimiento')->findOneBy(array(
                                                           'idEstablecimiento' => $establecimiento,
                                                           'idDependencia' => $dependencia         
                                                            ));
        
        if (!$idDependenciaEstablecimiento) {
            throw $this->createNotFoundException('Unable to find CtlDependenciaEstablecimiento entity.');
        }
        $entity->setIdDependenciaEstablecimiento($idDependenciaEstablecimiento);
        
        if ($editForm->isValid()) {
            $em->flush();

//            return $this->redirect($this->generateUrl('sifda_solicitudservicio_edit', array('id' => $id)));
            return $this->redirect($this->generateUrl('sifda_solicitudservicio', array('id' => $id)));
            
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SifdaSolicitudServicio entity.
     *
     * @Route("/{id}", name="sifda_solicitudservicio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);

            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_solicitudservicio'));
    }
    
    
    /**
     * Deletes a SifdaSolicitudServicio entity.
     *
     * @Route("/sifda/delete2", name="sifda_solicitudservicio_delete2")
      
     */
    
    
    public function delete2Action(){
        
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
        $id = $this->get('request')->request->get('id');
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        
        if($entity->getIdEstado()->getId() == 1){
        
            if (!$entity) {
                    throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
                }

                $em->remove($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('sifda_solicitudservicio'));
        }
        else {
          
            return $this->redirect($this->generateUrl('sifda_solicitudservicio_eliminar', array('id' => $id)));
        }
      }
    }
    
    /**
     * Permite mostrar la informacion de solicitud de servicio a eliminar.
     *
     * @Route("/eliminar/{id}", name="sifda_solicitudservicio_eliminar")
     * @Method("GET")
     * 
     * @Template()
     */
    
    
    public function informacionEliminarAction($id){
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
        }

        return array(
            'entity'      => $entity,
        //    'edit_form'   => $editForm->createView(),
        //    'delete_form' => $deleteForm->createView(),
        );                
        
    }
    
    /**
    * Ajax utilizado para buscar rango de fechas
    *  
    * @Route("/sifda/rechazar", name="sifda_solicitudservicio_rechazar")
    */
    
    
    public function rechazaraction(){
        
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            
            $id = $this->get('request')->request->get('id');
            $texto = $this->get('request')->request->get('texto');
        
        $res=0;
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
         
        if (!$entity) {
                throw $this->createNotFoundException('No encontre la Entidad.');
            }

            $estado=$entity->getIdEstado()->getId();
            if($estado==1)
            {
                $objEstado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(3);   
            if (!$objEstado) {
                throw $this->createNotFoundException('No encontre el Estado.');
            }
                
                $entity->setIdEstado($objEstado);
                $em->merge($entity);
                $em->flush();
                
                $res=$estado;
                
                return $this->redirect($this->generateUrl('sifda_solicitudservicio_send_email',array('texto'=>$texto)));
//                  return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:rechazarSolicitudes.html.twig' , array('entity' =>$entity,'val'=>$res));
                    //return $this->redirect($this->generateUrl('sifda_gestionSolicitudes'));
                    
//                return new Response('1');
                  
            }     
            
         else 
             {
                $res=$estado;
//                return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:rechazarSolicitudes.html.twig' , array('entity' =>$entity,'val'=>$res));
//                return new Response('0');
             } 
//                throw $this->createNotFoundException('No pude rechazar la solicitud.');
        }    
            
    } 
    
    
    /**
    * Ajax utilizado para buscar rango de fechas
    *  
    * @Route("/sifda/finalizacion", name="sifda_solicitudservicio_finalizacion")
    */
    
         public function FinalizacionAction()        
         {
             $response = new JsonResponse();
             $isAjax = $this->get('Request')->isXMLhttpRequest();
             if($isAjax){
                 
             $id = $this->get('request')->request->get('id');    
            
             $res=0;
             $em = $this->getDoctrine()->getManager();
             $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
             
                if (!$entity) {
                     throw $this->createNotFoundException('No encontre la Entidad.');
                }
                $dql = "SELECT MAX(e.id) "
               . "FROM MinsalsifdaBundle:Vwetapassolicitud e where e.idSolicitud = :idSol";
               $repositorio = $em->createQuery($dql);
               $repositorio->setParameter(':idSol', $entity->getId());
               
               $resultado = $repositorio->getResult();
             $resultado;
               $vwUltimaEtapa = new \Minsal\sifdaBundle\Entity\Vwetapassolicitud(); 
               $vwUltimaEtapa = $em->getRepository('MinsalsifdaBundle:Vwetapassolicitud')->find($resultado[0][1]);
               if($vwUltimaEtapa->getIdEstado() != 4){
                   return $response->setData(array('val'=>0));;
               }
            $estado=$entity->getIdEstado()->getId();
            if($estado!=4)
                {
                    $objEstado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(4);   
                if (!$objEstado) {
                    throw $this->createNotFoundException('No encontre el Estado.');
                }

//                    $entity->setIdEstado($objEstado);
//                    $em->merge($entity);
//                    $em->flush();

                    return $response->setData(array('val'=>1));       
               }
            else {
                    return $response->setData(array('val'=>2));
                }
                

                
         }//Fin del Ajax
    
      }
    
    /**
     * Deletes a SifdaSolicitudServicio entity.
     ** @Method("GET")
     * @Route("/rechazo/{id}", name="sifda_solicitudservicio_rechazo")
     
     */
    
    public function rechazoAction($id){
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
        }

              
        return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:Rechazo.html.twig',array('entity'=>$entity));
    }
    
    
    /**
     * Deletes a SifdaSolicitudServicio entity.
     ** @Method("GET")
     * @Route("/finalizar/{id}", name="sifda_solicitudservicio_finalizar")
     
     */
    
    public function FinalizarAction($id){
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
        }

              
        return $this->render('MinsalsifdaBundle:SifdaSolicitudServicio:Finalizar.html.twig',array('entity'=>$entity));
    }


    
    
        /**
    * Ajax utilizado para cambiar el estado de la solicitud de servicio
    *  
    * @Route("/sifda/aceptar", name="sifda_solicitudservicio_aceptar")
    */
    
    
    public function aceptarSolicitudAction(){
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            
        $id = $this->get('request')->request->get('id');
        //ladybug_dump($id);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        ladybug_dump($entity); 
        if (!$entity) {
                throw $this->createNotFoundException('No encontre la Entidad.');
            }
            $estado=$entity->getIdEstado()->getId();
            if($estado==3)
            {
                $objEstado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(1);   
                $entity->setIdEstado($objEstado);
                $em->merge($entity);
                $em->flush();                    
                return new Response('1');  
            }     
        }    
            
    } 
    
    

    /**
     * Creates a form to delete a SifdaSolicitudServicio entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_solicitudservicio_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
     
    
}
