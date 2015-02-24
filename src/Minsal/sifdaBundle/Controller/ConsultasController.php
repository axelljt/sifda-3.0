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
}
