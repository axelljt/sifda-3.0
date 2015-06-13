<?php

namespace Minsal\sifdaBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Reportes controller.
 *
 * @Route("/sifda/reportes")
 */
class ReportesController extends Controller 
{
    /**
    * Consolidado de solicitudes por tipo de servicio
    *  
    * @Route("/solicitudes/tipoServicio", name="sifda_reporte_solicitudes_tiposervicio")
    * @Method("GET")
    * @Template() 
    */
    public function consolidadoSolicitudesTipoServicioAction()
    {
        $em = $this->getDoctrine()->getManager();

        $idusuario=  $this->getUser()->getId();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);

        $establecimiento= $em->getRepository('MinsalsifdaBundle:CtlEstablecimiento')->findAll();
        
        return array(
            'usuario' => $usuario,
            'establecimiento'=>$establecimiento,
        );
    }

    
    /**
    * Obtener consolidado solicitudes por tipo de servicio que se encuentren en el 
    * rango de fecha y dependencia seleccionado en el filtro
    *
    * @Route("/consolidadoSolicitudesTipo", name="sifda_consolidado_solicitudes_tipo")
    * @Method("POST")
    * @Template()
    */
    public function getConsolidadoSolicitudesTipoAction()
    {
        
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $establecimiento = $this->get('request')->request->get('establecimiento');
            $dependencia = $this->get('request')->request->get('dependencia');
            $fechaInicio = $this->get('request')->request->get('fechaInicio');
            $fechaFin = $this->get('request')->request->get('fechaFin');
            $em = $this->getDoctrine()->getEntityManager();

            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('corr','corr');
            $rsm->addScalarResult('tipo_servicio','tipo_servicio');
            $rsm->addScalarResult('sin_atender','sin_atender');
            $rsm->addScalarResult('en_proceso','en_proceso');
            $rsm->addScalarResult('rechazadas','rechazadas');
            $rsm->addScalarResult('finalizadas','finalizadas');
            $rsm->addScalarResult('total','total');
            
            $sql = "select sts.id as corr, sts.nombre as tipo_servicio, 
                            (select count(sol.id) from sifda_solicitud_servicio sol inner join sifda_tipo_servicio tp on sol.id_tipo_servicio = tp.id and sol.id_tipo_servicio = sts.id where id_estado = 1) as sin_atender,
                            (select count(sol.id) from sifda_solicitud_servicio sol inner join sifda_tipo_servicio tp on sol.id_tipo_servicio = tp.id and sol.id_tipo_servicio = sts.id where id_estado = 2) as en_proceso,
                            (select count(sol.id) from sifda_solicitud_servicio sol inner join sifda_tipo_servicio tp on sol.id_tipo_servicio = tp.id and sol.id_tipo_servicio = sts.id where id_estado = 3) as rechazadas,
                            (select count(sol.id) from sifda_solicitud_servicio sol inner join sifda_tipo_servicio tp on sol.id_tipo_servicio = tp.id and sol.id_tipo_servicio = sts.id where id_estado = 4) as finalizadas,       
                            count(ss.id) as total
                    from sifda_solicitud_servicio ss
                         inner join fos_user_user us on ss.user_id = us.id
                         inner join ctl_dependencia_establecimiento depest on us.id_dependencia_establecimiento = depest.id
                         inner join ctl_dependencia dep on depest.id_dependencia = dep.id
                         inner join ctl_establecimiento est on depest.id_establecimiento = est.id
                         left outer join sifda_tipo_servicio sts on ss.id_tipo_servicio = sts.id
                         where 1 = 1";

            if ($establecimiento != 0){
                $sql.= " and de.id_establecimiento = '$establecimiento'";
            }
            
            if ($dependencia != 0){
                " and de.id_dependencia = '$dependencia'";
            }
            
            if ( $fechaInicio != null && $fechaFin != null){
                $sql.=" and ss.fecha_recepcion >= '$fechaInicio' and ss.fecha_recepcion <= '$fechaFin'";
            }
            
            $sql.= " group by sts.nombre,
                      (select count(sol.id) from sifda_solicitud_servicio sol inner join sifda_tipo_servicio tp on sol.id_tipo_servicio = tp.id and sol.id_tipo_servicio = sts.id where id_estado = 1),
                      (select count(sol.id) from sifda_solicitud_servicio sol inner join sifda_tipo_servicio tp on sol.id_tipo_servicio = tp.id and sol.id_tipo_servicio = sts.id where id_estado = 2),
                      (select count(sol.id) from sifda_solicitud_servicio sol inner join sifda_tipo_servicio tp on sol.id_tipo_servicio = tp.id and sol.id_tipo_servicio = sts.id where id_estado = 3),
                      (select count(sol.id) from sifda_solicitud_servicio sol inner join sifda_tipo_servicio tp on sol.id_tipo_servicio = tp.id and sol.id_tipo_servicio = sts.id where id_estado = 4)";
            
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
