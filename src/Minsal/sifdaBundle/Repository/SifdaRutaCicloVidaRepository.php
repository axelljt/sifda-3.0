<?php
namespace Minsal\sifdaBundle\Repository;
use Doctrine\ORM\EntityRepository;

class SifdaRutaCicloVidaRepository extends EntityRepository{
   
    public function obtenerEtapas($tipoServicio)
    {        
       $dql = "SELECT e "
               . "FROM MinsalsifdaBundle:SifdaRutaCicloVida e "
               . "INNER JOIN e.idTipoServicio tp "
               . "WHERE tp.id = :tiposervicio "
               . "AND e.idEtapa IS NULL "
               . "ORDER BY e.jerarquia";
       
       $repositorio = $this->getEntityManager()
               ->createQuery($dql)
               ->setParameter(':tiposervicio', $tipoServicio);;       
       
        return $repositorio->getResult();	
    }
    
    public function obtenerSubetapas($etapa)
    {        
       $dql = "SELECT s "
               . "FROM MinsalsifdaBundle:SifdaRutaCicloVida s "
               . "INNER JOIN s.idEtapa e "
               . "WHERE e.id = :etapa "
               . "ORDER BY s.jerarquia";
       
       $repositorio = $this->getEntityManager()
               ->createQuery($dql)
                ->setParameter(':etapa', $etapa);;       
       
        return $repositorio->getResult();	
    }
    
    public function obtenerRuta($tipoServicio)
    {        
       $dql = "select r "
               . "from MinsalsifdaBundle:SifdaRuta r "
               . "where r.idTipoServicio =:tiposervicio";
       
       $repositorio = $this->getEntityManager()
               ->createQuery($dql)
               ->setParameter(':tiposervicio', $tipoServicio);;       
       
        return $repositorio->getResult();	
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
}
