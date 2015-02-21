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
        $sql = "select distinct e.id id, e.nombre|| ' ' ||e.apellido tecnico, count(distinct eq.id_orden_trabajo) as atendidas,
                (select count(distinct eqp.id_orden_trabajo) 
                   from  sifda_equipo_trabajo eqp 
                   left outer join sifda_orden_trabajo ot on ot.id = eqp.id_orden_trabajo
                   where ot.id_estado = 2 and eqp.id_empleado = e.id) as pendientes,
                (select count(distinct eqp.id_orden_trabajo) 
                   from  sifda_equipo_trabajo eqp 
                   left outer join sifda_orden_trabajo ot on ot.id = eqp.id_orden_trabajo
                   where ot.id_estado = 4 and eqp.id_empleado = e.id) as finalizadas         
                from sifda_equipo_trabajo eq 
                full outer join ctl_empleado e on e.id = eq.id_empleado
                left outer join sifda_orden_trabajo ord on ord.id = eq.id_orden_trabajo
                where 1=1";
        if($temp_fi!="" && $temp_ff!=""){
            $sql.=" and ord.fecha_creacion >= '$temp_fi' and ord.fecha_creacion <= '$temp_ff'";
        }
        $sql.=" and e.id_dependencia_establecimiento = $temp_tdest
                group by e.id, e.nombre|| ' ' ||e.apellido
                order by count(distinct eq.id_orden_trabajo) desc,
                (select count(distinct eqp.id_orden_trabajo) 
                   from  sifda_equipo_trabajo eqp 
                   left outer join sifda_orden_trabajo ot on ot.id = eqp.id_orden_trabajo
                   where ot.id_estado = 2 and eqp.id_empleado = e.id) desc,
                (select count(distinct eqp.id_orden_trabajo) 
                   from  sifda_equipo_trabajo eqp 
                   left outer join sifda_orden_trabajo ot on ot.id = eqp.id_orden_trabajo
                   where ot.id_estado = 4 and eqp.id_empleado = e.id) desc";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $resultado = $query->getResult();
        $response = new JsonResponse();
            $response->setData(array(
            'query' => $resultado
                    ));
            return $response;
    }
}
