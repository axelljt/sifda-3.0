<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaServicioPrioridad
 *
 * @ORM\Table(name="sifda_servicio_prioridad", indexes={@ORM\Index(name="se_designa_fk", columns={"id_dependencia_establecimiento"}), @ORM\Index(name="se_atiende_fk", columns={"id_tipo_servicio"}), @ORM\Index(name="debe_ser_atendido_fk", columns={"id_prioridad"})})
 * @ORM\Entity
 */
class SifdaServicioPrioridad
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_servicio_prioridad_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;


    /**
     * @var \CtlDependenciaEstablecimiento
     *
     * @ORM\ManyToOne(targetEntity="CtlDependenciaEstablecimiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_dependencia_establecimiento", referencedColumnName="id")
     * })
     */
    private $idDependenciaEstablecimiento;

    /**
     * @var \SifdaTipoServicio
     *
     * @ORM\ManyToOne(targetEntity="SifdaTipoServicio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipo_servicio", referencedColumnName="id")
     * })
     */
    private $idTipoServicio;

    
    /**
     * @var \CatalogoDetalle
     *
     * @ORM\ManyToOne(targetEntity="CatalogoDetalle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_prioridad", referencedColumnName="id")
     * })
     */
    private $idPrioridad;
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    
    /**
     * Set idDependenciaEstablecimiento
     *
     * @param \Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento $idDependenciaEstablecimiento
     * @return SifdaServicioPrioridad
     */
    public function setIdDependenciaEstablecimiento(\Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento $idDependenciaEstablecimiento = null)
    {
        $this->idDependenciaEstablecimiento = $idDependenciaEstablecimiento;

        return $this;
    }

    /**
     * Get idDependenciaEstablecimiento
     *
     * @return \Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento 
     */
    public function getIdDependenciaEstablecimiento()
    {
        return $this->idDependenciaEstablecimiento;
    }


    /**
     * Set idTipoServicio
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaTipoServicio $idTipoServicio
     * @return SifdaServicioPrioridad
     */
    public function setIdTipoServicio(\Minsal\sifdaBundle\Entity\SifdaTipoServicio $idTipoServicio = null)
    {
        $this->idTipoServicio = $idTipoServicio;

        return $this;
    }

    /**
     * Get idTipoServicio
     *
     * @return \Minsal\sifdaBundle\Entity\SifdaTipoServicio 
     */
    public function getIdTipoServicio()
    {
        return $this->idTipoServicio;
    }
    
    /**
     * Set idPrioridad
     *
     * @param \Minsal\sifdaBundle\Entity\CatalogoDetalle $idPrioridad
     * @return SifdaServicioPrioridad
     */
    public function setIdPrioridad(\Minsal\sifdaBundle\Entity\CatalogoDetalle $idPrioridad = null)
    {
        $this->idPrioridad = $idPrioridad;

        return $this;
    }

    /**
     * Get idPrioridad
     *
     * @return \Minsal\sifdaBundle\Entity\CatalogoDetalle 
     */
    public function getIdPrioridad()
    {
        return $this->idPrioridad;
    }
}
