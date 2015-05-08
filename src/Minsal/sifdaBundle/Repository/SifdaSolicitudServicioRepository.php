<?php
namespace Minsal\sifdaBundle\Repository;
use Doctrine\ORM\EntityRepository;


class SifdaSolicitudServicioRepository extends EntityRepository
{
   /*Repositorio que consulta las solicitudes por rango de fechas*/
   public function FechaSolicitud($fechaInicio, $fechaFin)
    {        
       $dql = "SELECT s FROM MinsalsifdaBundle:SifdaSolicitudServicio s WHERE s.fechaRecepcion >= '$fechaInicio' AND s.fechaRecepcion <='$fechaFin' ORDER BY s.fechaRecepcion DESC";	     
       $repositorio = $this->getEntityManager()->createQuery($dql);       
       return $repositorio->getResult();	
    }
    
    
    /*Repositorio que consulta las solicitudes por rango de fechas*/
   public function FechaSolicitudIngresada($fechaInicio, $fechaFin,$tipoServicio)
    {        
       $dql = "SELECT s FROM MinsalsifdaBundle:SifdaSolicitudServicio s WHERE s.fechaRecepcion >= '$fechaInicio' AND s.fechaRecepcion <='$fechaFin' AND s.idTipoServicio='$tipoServicio' AND s.idEstado=1 ORDER BY s.fechaRecepcion DESC";	     
       $repositorio = $this->getEntityManager()->createQuery($dql);       
       return $repositorio->getResult();	
    }
    
    /*Repositorio que consulta las solicitudes por rango de fechas*/
   public function FechaSolicitudRechazadas($fechaInicio, $fechaFin,$tipoServicio)
    {        
       $dql = "SELECT s FROM MinsalsifdaBundle:SifdaSolicitudServicio s WHERE s.fechaRecepcion >= '$fechaInicio' AND s.fechaRecepcion <='$fechaFin' AND s.idTipoServicio='$tipoServicio' AND s.idEstado=3 ORDER BY s.fechaRecepcion DESC";	     
       $repositorio = $this->getEntityManager()->createQuery($dql);       
       return $repositorio->getResult();	
    }
    
    /*Repositorio Generico que consulta las solicitudes por rango de Fechas*/
    
    public  function buscarFechasSolicitudGenerico($fechaInicio,$fechaFin,$dependencia,$estado){
        $fechaFinFormato = $fechaFin.' 23:59:59';
        
        $dql = "SELECT s FROM MinsalsifdaBundle:SifdaSolicitudServicio s WHERE s.fechaRecepcion >= '$fechaInicio' AND s.fechaRecepcion<='$fechaFinFormato' AND s.idDependenciaEstablecimiento='$dependencia' AND s.idEstado='$estado' ORDER BY s.fechaRecepcion DESC";	     
        $repositorio = $this->getEntityManager()->createQuery($dql);      
        return $repositorio->getResult();
        
        
        
    }




    /* Se obtiene la dependencia a la que se le hace la solicitud de servicio */
    public function ObtenerDependencia($id)
    {
        $dql="SELECT ss, de, d.nombre nombre FROM MinsalsifdaBundle:SifdaSolicitudServicio ss JOIN ss.idDependenciaEstablecimiento de JOIN de.idDependencia d WHERE de.id=$id";
         $repositorio = $this->getEntityManager()->createQuery($dql);       
       return $repositorio->getResult();
       //$repositorio = $this->getEntityManager()->createQuery($dql)->setParameter(':solicitud', $id);
        
        //return $repositorio->getResult();     
    }
    
    //Consulta para obtener el numero de solicitudes Ingresadas
    
    public function ContarSolicitudesIngresadas($estado)
    {
        $dql = "SELECT COUNT(s.idEstado) FROM MinsalsifdaBundle:SifdaSolicitudServicio s WHERE s.idEstado=$estado";
        $repositorio=$this->getEntityManager()->createQuery($dql);
        return $repositorio->getOneOrNullResult();
    }
    
    //Consulta para obtener el numero de solicitudes Ingresadas
    
    public function RechazarSolicitudServicio($id)
    {
        $dql = "UPDATE MinsalsifdaBundle:SifdaSolicitudServicio s SET s.idEstado = 3  WHERE s.idEstado=$id";
        $repositorio=$this->getEntityManager()->createQuery($dql);
        return $repositorio->getOneOrNullResult();
            
    }
    
 /*Repositorio que consulta las solicitudes por rango de fechas*/
    public function FechaSeguimiento($fechaInicio, $fechaFin, $establecimiento, $dependencia, $tipoServicio)
    {  
       $em = $this->getDoctrine()->getManager();
       $establecimientoid = $em->getRepository('MinsalsifdaBundle:CtlEstablecimiento')->find($establecimiento);
       $dependenciaid = $em->getRepository('MinsalsifdaBundle:CtlDependencia')->find($dependencia);
       $tipoServicioid = $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->find($tipoServicio);
            
       $dql = "SELECT s "
               . "FROM MinsalsifdaBundle:SifdaSolicitudServicio s "
               . "INNER JOIN s.userId us "
               . "INNER JOIN us.idDependenciaEstablecimiento de "
               . "WHERE s.fechaRecepcion >= '$fechaInicio' "
               . "AND s.fechaRecepcion <='$fechaFin' "
               . "AND s.idTipoServicio = '$tipoServicioid' "
               . "AND de.idDependencia = '$dependenciaid' "
               . "AND de.idEstablecimiento = '$establecimientoid' "
               . "ORDER BY s.fechaRecepcion DESC";	     
       $repositorio = $this->getEntityManager()->createQuery($dql);       
       return $repositorio->getResult();	
    }
    
    
    
}

?>
