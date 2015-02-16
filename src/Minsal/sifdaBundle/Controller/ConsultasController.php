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
}
