<?php

namespace Minsal\sifdaBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Minsal\sifdaBundle\Entity\Catalogo;
//use Minsal\sifdaBundle\Form\CatalogoType;

/**
 * Consultas controller.
 *
 * @Route("/sifda/consultas")
 */
class ConsultasController extends Controller
{
/**
     * Lists all Vwetapassolicitud entities.
     *
     * @Route("/carga", name="sifda_carga")
     * @Method("GET")
     * @Template()
     */
    public function cargaLaboralAction()
    {
        return $this->render('MinsalsifdaBundle:Consultas:cargaLaboral.html.twig');
    }
/**
     * Lists all Vwetapassolicitud entities.
     *
     * @Route("/buscarga", name="buscar_carga_laboral")
     */
    public function buscarCargaLaboral()
    {
            $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($this->getUser()->getId());
        $temp_tdest = $user->getIdDependenciaEstablecimiento()->getId();
            $temp_fi = $this->get('request')->request->get('fechaInicio');
            $temp_ff = $this->get('request')->request->get('fechaFin');
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('tecnico','tecnico');
        $rsm->addScalarResult('atendidas','atendidas');
        $rsm->addScalarResult('pendientes','pendientes');
        $rsm->addScalarResult('finalizadas','finalizadas');
        $sql = "select distinct(e.id),e.nombre|| ' ' ||e.apellido as tecnico,count(distinct id_orden) as atendidas,
(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 2 and v.id_empleado = vw.id_empleado) as pendientes,
(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 4 and v.id_empleado = vw.id_empleado) as finalizadas
from ctl_empleado e left outer join
vwetapassolicitud vw on e.id = vw.id_empleado where 1=1";
if($temp_fi!="" && $temp_ff!=""){
$sql.=" and fchcrea_orden >= '$temp_fi' and fchcrea_orden <= '$temp_ff'";
    }
$sql.=" and e.id_dependencia_establecimiento = $temp_tdest
group by e.id,e.nombre|| ' ' ||e.apellido,(select count(distinct v.id_orden) from 
vwetapassolicitud v where v.id_estado = 2 and v.id_empleado = vw.id_empleado),
(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 
4 and v.id_empleado = vw.id_empleado) order by (select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 2 and v.id_empleado = vw.id_empleado) desc";
//        $sql = "select id, nombre || ' ' ||apellido as tecnico, id_cargo as atendidas,id_cargo+3 as asignadas,id+2 as terminadas from ctl_empleado where 1 = 0";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $resultado = $query->getResult();
        $response = new JsonResponse();
            $response->setData(array(
            'query' => $resultado
                    ));
            return $response;
    }
/**
     * Lists all Vwetapassolicitud entities.
     *
     * @Route("/consulpao", name="sifda_consulta_pao")
     * @Method("GET")
     * @Template()
     */
    public function consultaPaoAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($this->getUser()->getId());
        
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('resultado','resultado');
        
        $sql = "select DISTINCT(anio) as resultado from sidpla_linea_estrategica ORDER BY anio";
        $query = $em->createNativeQuery($sql, $rsm);
//        $query ->setParameter(1, $anio);
        $resultados = $query->getResult();
        $bool = null;
        return $this->render('MinsalsifdaBundle:Consultas:consultaPao.html.twig',array('resultados'=>$resultados,'user' => $user));
    }
/**
     * Lists all Vwetapassolicitud entities.
     *
     * @Route("/busclinest", name="buscar_lineas_estrategicas")
     */
    public function buscarLineasEstrategicas()
    {
            $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($this->getUser()->getId());
        $temp_tdest = $user->getIdDependenciaEstablecimiento()->getId();
            
            $anio = $this->get('request')->request->get('anio');
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('descripcion','descripcion');
        $rsm->addScalarResult('codigo','codigo');
        $rsm->addScalarResult('activo','activo');
        $rsm->addScalarResult('recurrente','recurrente');
        $sql = "select id,descripcion,codigo,activo,recurrente from sidpla_linea_estrategica a where anio = ? ";
//        $sql.="and id_dependencia_establecimiento = ?";
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $anio);
//        $query->setParameter(2, $temp_tdest);
        $resultado = $query->getResult();
        $response = new JsonResponse();
            $response->setData(array(
            'query' => $resultado
                    ));
            return $response;
    }
/**
     * Lists all Vwetapassolicitud entities.
     *
     * @Route("/buscact", name="buscar_actividades")
     */
    public function buscarActividades()
    {
            $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($this->getUser()->getId());
        $temp_tdest = $user->getIdDependenciaEstablecimiento()->getId();
            
            $ln = $this->get('request')->request->get('idln');
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('descripcion','descripcion');
        $rsm->addScalarResult('meta_anual','meta_anual');
        $rsm->addScalarResult('indicador','indicador');
        $rsm->addScalarResult('generado','generado');
        $sql = "select id,descripcion,meta_anual,indicador,generado from sidpla_actividad where id_linea_estrategica=?";
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $ln);
        $resultado = $query->getResult();
        $response = new JsonResponse();
            $response->setData(array(
            'query' => $resultado
                    ));
            return $response;
    }
    /**
     * Consulta de solicitudes atendidas por linea estrategica.
     *
     * @Route("/conact", name="sifda_consulta_actividad")
     * @Method("GET")
     * @Template()
     */
    public function solAtendPaoAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($this->getUser()->getId());
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('resultado','resultado');
        $fecha = new \DateTime();
        $lineas= $em->getRepository('MinsalsifdaBundle:SidplaLineaEstrategica')->findBy(array('idDependenciaEstablecimiento'=>$user->getIdDependenciaEstablecimiento(),'anio'=>$fecha->format('Y')));
        $bool = null;
        return $this->render('MinsalsifdaBundle:Consultas:solAtendPao.html.twig',array('lineas'=>$lineas,'user' => $user));
    }
    
    /**
    * Ajax utilizado para buscar las dependencias segun su establecimiento
    *  
    * @Route("/find_actividad_pao", name="sifda_consulta_find_acividad")
    */
    public function findActividadesAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $idLinea = $this->get('request')->request->get('idLinea');
             $em = $this->getDoctrine()->getManager();
             $actividades_linea = $em->getRepository('MinsalsifdaBundle:SidplaActividad')->findBy(array('idLineaEstrategica'=>$idLinea));
             $actividades=array();   
             
             foreach($actividades_linea as $a)
             {
                 $actividades[] = $a->getDescripcion();
                  
             }
             
             
             
             $activs = $em->getRepository('MinsalsifdaBundle:SidplaActividad')->findBy(array('descripcion'=>$actividades),array('descripcion' => 'ASC'));
             
             
//             ladybug_dump($dependencia);
             
             $mensaje = $this->renderView('MinsalsifdaBundle:Consultas:actividadesShow.html.twig' , array('actividades' =>$activs));
             $response = new JsonResponse();
             return $response->setData($mensaje);
        }else
            {   return new Response('0');   }       
    }
    
    /**
     * Busca una lista de las solicitudes atendidas para las actividades estrategicas.
     *
     * @Route("/buscsolact", name="buscar_solicitudes_actividad")
     */
    public function buscarSolicitudesLinea()
    {
            $lin = $this->get('request')->request->get('linea');
            $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($this->getUser()->getId());
        $temp_tdest = $user->getIdDependenciaEstablecimiento()->getId();
            $fecha = new \DateTime();
            $anio = $fecha->format('Y');
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('descripcion','descripcion');
        $rsm->addScalarResult('codigo','codigo');
        $rsm->addScalarResult('activo','activo');
        $rsm->addScalarResult('recurrente','recurrente');
        $sql = "select id,descripcion,codigo,activo,recurrente from sidpla_linea_estrategica a where anio = ? ";
        $sql.="and id_dependencia_establecimiento = ?";
        if ($lin != 0){
        $sql.="and id = ?";
        }
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $anio);
        $query->setParameter(2, $temp_tdest);
        if ($lin != 0){
        $query->setParameter(3, $lin);
        }
        $resultado = $query->getResult();
        $response = new JsonResponse();
            $response->setData(array(
            'query' => $resultado
                    ));
            return $response;
    }
    
    /**
     * Lists all Vwetapassolicitud entities.
     *
     * @Route("/buscact", name="buscar_actividades")
     */
    public function buscarActividadesPao()
    {
            $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($this->getUser()->getId());
        $temp_tdest = $user->getIdDependenciaEstablecimiento()->getId();
            $act = $this->get('request')->request->get('actividad');
            $ln = $this->get('request')->request->get('idln');
            $temp_fi = $this->get('request')->request->get('fechaIni');
            $temp_ff = $this->get('request')->request->get('fechaFin');
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('descripcion','descripcion');
        $rsm->addScalarResult('meta_anual','meta_anual');
        $rsm->addScalarResult('indicador','indicador');
        $rsm->addScalarResult('generado','generado');        
        $rsm->addScalarResult('finalizadas','finalizadas');
        $sql = "select a.id as id,a.descripcion as descripcion,a.meta_anual as meta_anual,a.indicador as indicador,a.generado as generado,count(ss.id) as finalizadas
                from sidpla_actividad a 
                left outer join sifda_tipo_servicio s on a.id = s.id_actividad
                left outer join sifda_solicitud_servicio ss on ss.id_tipo_servicio = s.id and ss.id_estado = 4 ";
        if ( $temp_fi != null && $temp_ff != null){
        $sql.=" and fecha_finaliza between ? and ?";
        }
               $sql.=" where id_linea_estrategica = ?";
        
        if ( $act != 0){
        $sql.= " and a.id = ?";
        }
        $sql.=" group by a.id,a.descripcion,a.meta_anual,a.indicador,a.generado,s.id,s.nombre;";
//        $sql = "select id,descripcion,meta_anual,indicador,generado from sidpla_actividad where id_linea_estrategica=?";
//select a.id,a.descripcion,a.meta_anual,a.indicador,a.generado,s.id,s.nombre,count(ss.id) as finalizadas
//from sidpla_actividad a 
//left outer join sifda_tipo_servicio s on a.id = s.id_actividad
//left outer join sifda_solicitud_servicio ss on ss.id_tipo_servicio = s.id and ss.id_estado = 4 and fecha_finaliza between '2015-05-15' and '2015-05-15'
//group by a.id,a.descripcion,a.meta_anual,a.indicador,a.generado,s.id,s.nombre;
        $query = $em->createNativeQuery($sql, $rsm);
        if ( $temp_fi != null && $temp_ff != null){
            $query->setParameter(1, $temp_fi);
            $query->setParameter(2, $temp_ff);
        }
        $query->setParameter(3, $ln);
        if ( $act != 0){
        $query->setParameter(4, $act);
        }
        $resultado = $query->getResult();
        $response = new JsonResponse();
            $response->setData(array(
            'query' => $resultado
                    ));
            return $response;
    }
    
    /**
    * Ajax utilizado para buscar solicitudes proximas a vencer
    *  
    * @Route("/buscarSolicitudesVencer", name="sifda_buscar_solicitudes_vencer")
    */
    public function buscarSolicitudesVencerAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $establecimiento = $this->get('request')->request->get('establecimiento');
            $dependencia = $this->get('request')->request->get('dependencia');
            $fechaInicio = $this->get('request')->request->get('fechaInicio');
            $fechaFin = $this->get('request')->request->get('fechaFin');
//            $fecha = $this->get('request')->request->get('fechaSistema');
            $em = $this->getDoctrine()->getEntityManager();

            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('corr','corr');
            $rsm->addScalarResult('dependencia','dependencia');
            $rsm->addScalarResult('establecimiento','establecimiento');
            $rsm->addScalarResult('descripcion','descripcion');
            $rsm->addScalarResult('fecha_requiere','fecha_requiere');
            $rsm->addScalarResult('dias_vencer','dias_vencer');
            
            $sql = "select ss.id as corr, de.nombre as dependencia, "
                    . "est.nombre as establecimiento, "
                    . "ss.descripcion as descripcion, "
                    . "ss.fecha_requiere as fecha_requiere, "
                    . "EXTRACT(DAY FROM (date(ss.fecha_requiere) - timestamp 'now()' ) ) as dias_vencer "
                . "from sifda_solicitud_servicio ss "
                    . "inner join fos_user_user us on ss.user_id = us.id "
                    . "inner join ctl_dependencia_establecimiento depest on us.id_dependencia_establecimiento = depest.id "
                    . "inner join ctl_establecimiento est on depest.id_establecimiento = est.id "
                    . "inner join ctl_dependencia de on depest.id_dependencia = de.id "
                . "where EXTRACT(DAY FROM (date(ss.fecha_requiere) - timestamp 'now()' ) ) >= 0 "
                    . "and EXTRACT(DAY FROM (date(ss.fecha_requiere) - timestamp 'now()' ) ) <= 15 "
                    . "and ss.id_estado NOT IN (3, 4)";

            if ($establecimiento != 0){
                $sql.= " and depest.id_establecimiento = '$establecimiento'";
            }
            if ($dependencia != 0){
                $sql.= " and depest.id_dependencia = '$dependencia'";
            }              
            if ( $fechaInicio != null && $fechaFin != null){
                $sql.=" and ss.fecha_recepcion >= '$fechaInicio' and ss.fecha_recepcion <= '$fechaFin'";
            }
            $sql.= " order by ss.fecha_requiere";
            
            $query = $em->createNativeQuery($sql, $rsm);
            $resultado = $query->getResult();
            
            $response = new JsonResponse();
            $response->setData(array(
                                'query' => $resultado
                                ));
            
            return $response; 
            
        }else
        {   
            return new Response('0');              
        } 
    }
    
    /**
    * Ajax utilizado para obtener el numero de solicitudes proximas a vencer
    *  
    * @Route("/numeroSolicitudesVencer", name="sifda_numero_solicitudes_vencer")
    */
    public function obtenerNumeroSolicitudesVencerAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
//            $establecimiento = $this->get('request')->request->get('establecimiento');
//            $dependencia = $this->get('request')->request->get('dependencia');
//            $fechaInicio = $this->get('request')->request->get('fechaInicio');
//            $fechaFin = $this->get('request')->request->get('fechaFin');
             $fecha = $this->get('request')->request->get('fechaSistema');
            $em = $this->getDoctrine()->getEntityManager();

            
            $rsm = new ResultSetMapping();
//            $rsm->addScalarResult('corr','corr');
//            $rsm->addScalarResult('dependencia','dependencia');
//            $rsm->addScalarResult('establecimiento','establecimiento');
//            $rsm->addScalarResult('descripcion','descripcion');
//            $rsm->addScalarResult('fecha_requiere','fecha_requiere');
            $rsm->addScalarResult('cant_dias','cant_dias');
            
            $sql = "select count(EXTRACT(DAY FROM (date(ss.fecha_requiere) - timestamp 'now()' ) ) >= 0 "
                              . "and EXTRACT(DAY FROM (date(ss.fecha_requiere) - timestamp 'now()' ) ) <= 15) as cant_dias "
                . "from sifda_solicitud_servicio ss "
                    . "inner join fos_user_user us on ss.user_id = us.id "
                    . "inner join ctl_dependencia_establecimiento depest on us.id_dependencia_establecimiento = depest.id "
                    . "inner join ctl_establecimiento est on depest.id_establecimiento = est.id "
                    . "inner join ctl_dependencia de on depest.id_dependencia = de.id "
                . "where EXTRACT(DAY FROM (date(ss.fecha_requiere) - timestamp 'now()' ) ) >= 0 "
                    . "and EXTRACT(DAY FROM (date(ss.fecha_requiere) - timestamp 'now()' ) ) <= 15 "
                    . "and ss.id_estado NOT IN (3, 4)";
            
//            if ($establecimiento != 0){
//                $sql.= " and depest.id_establecimiento = '$establecimiento'";
//            }            
//            if ($dependencia != 0){
//                $sql.= " and depest.id_dependencia = '$dependencia'";
//            }                        
//            $sql.= " order by ss.fecha_requiere";
            
            $query = $em->createNativeQuery($sql, $rsm);
            $resultado = $query->getResult();
            
            $response = new JsonResponse();
            $response->setData(array(
                                'query' => $resultado
                              ));
            
            return $response; 
            
        }else
        {   
            return new Response('0');              
        } 
    }
}
