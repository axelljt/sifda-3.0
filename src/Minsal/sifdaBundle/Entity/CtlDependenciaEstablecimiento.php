<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlDependenciaEstablecimiento
 *
 * @ORM\Table(name="ctl_dependencia_establecimiento", indexes={@ORM\Index(name="idx_id_dep_establecimiento", columns={"id_establecimiento"}), @ORM\Index(name="idx_id_dependencia_padre", columns={"id_dependencia_padre"}), @ORM\Index(name="idx_id_dependencia_est", columns={"id_dependencia"})})
 * @ORM\Entity
 */
class CtlDependenciaEstablecimiento
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ctl_dependencia_establecimiento_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="abreviatura", type="string", length=255, nullable=true)
     */
    private $abreviatura;

    /**
     * @var boolean
     *
     * @ORM\Column(name="habilitado", type="boolean", nullable=false)
     */
    private $habilitado;

    /**
     * @var \CtlDependencia
     *
     * @ORM\ManyToOne(targetEntity="CtlDependencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_dependencia", referencedColumnName="id")
     * })
     */
    private $idDependencia;

    /**
     * @var \CtlDependenciaEstablecimiento
     *
     * @ORM\ManyToOne(targetEntity="CtlDependenciaEstablecimiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_dependencia_padre", referencedColumnName="id")
     * })
     */
    private $idDependenciaPadre;

    /**
     * @var \CtlEstablecimiento
     *
     * @ORM\ManyToOne(targetEntity="CtlEstablecimiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id")
     * })
     */
    private $idEstablecimiento;



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
     * Set abreviatura
     *
     * @param string $abreviatura
     * @return CtlDependenciaEstablecimiento
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string 
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * Set habilitado
     *
     * @param boolean $habilitado
     * @return CtlDependenciaEstablecimiento
     */
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    /**
     * Get habilitado
     *
     * @return boolean 
     */
    public function getHabilitado()
    {
        return $this->habilitado;
    }

    /**
     * Set idDependencia
     *
     * @param \Minsal\sifdaBundle\Entity\CtlDependencia $idDependencia
     * @return CtlDependenciaEstablecimiento
     */
    public function setIdDependencia(\Minsal\sifdaBundle\Entity\CtlDependencia $idDependencia = null)
    {
        $this->idDependencia = $idDependencia;

        return $this;
    }

    /**
     * Get idDependencia
     *
     * @return \Minsal\sifdaBundle\Entity\CtlDependencia 
     */
    public function getIdDependencia()
    {
        return $this->idDependencia;
    }

    /**
     * Set idDependenciaPadre
     *
     * @param \Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento $idDependenciaPadre
     * @return CtlDependenciaEstablecimiento
     */
    public function setIdDependenciaPadre(\Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento $idDependenciaPadre = null)
    {
        $this->idDependenciaPadre = $idDependenciaPadre;

        return $this;
    }

    /**
     * Get idDependenciaPadre
     *
     * @return \Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento 
     */
    public function getIdDependenciaPadre()
    {
        return $this->idDependenciaPadre;
    }

    /**
     * Set idEstablecimiento
     *
     * @param \Minsal\sifdaBundle\Entity\CtlEstablecimiento $idEstablecimiento
     * @return CtlDependenciaEstablecimiento
     */
    public function setIdEstablecimiento(\Minsal\sifdaBundle\Entity\CtlEstablecimiento $idEstablecimiento = null)
    {
        $this->idEstablecimiento = $idEstablecimiento;

        return $this;
    }

    /**
     * Get idEstablecimiento
     *
     * @return \Minsal\sifdaBundle\Entity\CtlEstablecimiento 
     */
    public function getIdEstablecimiento()
    {
        return $this->idEstablecimiento;
    }
    
    
     public function __toString() 
   {
        return $this->id.' - '.$this->idDependencia->getNombre().'('.$this->abreviatura.')';
    }
}
