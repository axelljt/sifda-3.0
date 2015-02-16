<?php
namespace Minsal\sifdaBundle\Repository;
use Doctrine\ORM\EntityRepository;


class SifdaOrdenTrabajoRepository extends EntityRepository
{
   /*Repositorio que consulta las solicitudes por rango de fechas*/
   public function buscarOrdenXFecha($fechaInicio, $fechaFin)
    {        
       $dql = "SELECT o FROM MinsalsifdaBundle:SifdaOrdenTrabajo o WHERE o.fechaCreacion BETWEEN '$fechaInicio' AND '$fechaFin'";	     
       $repositorio = $this->getEntityManager()->createQuery($dql);       
       return $repositorio->getResult();	
    }
    
}

?>
