<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaOrdenTrabajo
 *
 * @ORM\Table(name="sifda_orden_trabajo", indexes={@ORM\Index(name="crea_fk", columns={"id_solicitud_servicio"}), @ORM\Index(name="define_etapa_fk", columns={"id_etapa"}), @ORM\Index(name="atiende_fk", columns={"id_dependencia_establecimiento"}), @ORM\Index(name="define_estado_fk", columns={"id_estado"}), @ORM\Index(name="define_prioridad_fk", columns={"id_prioridad"})})
 * @ORM\Entity
 */
class SifdaOrdenTrabajo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_orden_trabajo_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_etapa", type="integer", nullable=true)
     */
    private $idEtapa;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=15, nullable=false)
     */
    private $codigo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=false)
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_finalizacion", type="datetime", nullable=true)
     */
    private $fechaFinalizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable=true)
     */
    private $observacion;

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
     * @var \CatalogoDetalle
     *
     * @ORM\ManyToOne(targetEntity="CatalogoDetalle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_estado", referencedColumnName="id")
     * })
     */
    private $idEstado;

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
     * @var \SifdaSolicitudServicio
     *
     * @ORM\ManyToOne(targetEntity="SifdaSolicitudServicio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_solicitud_servicio", referencedColumnName="id")
     * })
     */
    private $idSolicitudServicio;



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
     * Set idEtapa
     *
     * @param integer $idEtapa
     * @return SifdaOrdenTrabajo
     */
    public function setIdEtapa($idEtapa)
    {
        $this->idEtapa = $idEtapa;

        return $this;
    }

    /**
     * Get idEtapa
     *
     * @return integer 
     */
    public function getIdEtapa()
    {
        return $this->idEtapa;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return SifdaOrdenTrabajo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return SifdaOrdenTrabajo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return SifdaOrdenTrabajo
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaFinalizacion
     *
     * @param \DateTime $fechaFinalizacion
     * @return SifdaOrdenTrabajo
     */
    public function setFechaFinalizacion($fechaFinalizacion)
    {
        $this->fechaFinalizacion = $fechaFinalizacion;

        return $this;
    }

    /**
     * Get fechaFinalizacion
     *
     * @return \DateTime 
     */
    public function getFechaFinalizacion()
    {
        return $this->fechaFinalizacion;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return SifdaOrdenTrabajo
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set idPrioridad
     *
     * @param \Minsal\sifdaBundle\Entity\CatalogoDetalle $idPrioridad
     * @return SifdaOrdenTrabajo
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

    /**
     * Set idEstado
     *
     * @param \Minsal\sifdaBundle\Entity\CatalogoDetalle $idEstado
     * @return SifdaOrdenTrabajo
     */
    public function setIdEstado(\Minsal\sifdaBundle\Entity\CatalogoDetalle $idEstado = null)
    {
        $this->idEstado = $idEstado;

        return $this;
    }

    /**
     * Get idEstado
     *
     * @return \Minsal\sifdaBundle\Entity\CatalogoDetalle 
     */
    public function getIdEstado()
    {
        return $this->idEstado;
    }

    /**
     * Set idDependenciaEstablecimiento
     *
     * @param \Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento $idDependenciaEstablecimiento
     * @return SifdaOrdenTrabajo
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
     * Set idSolicitudServicio
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaSolicitudServicio $idSolicitudServicio
     * @return SifdaOrdenTrabajo
     */
    public function setIdSolicitudServicio(\Minsal\sifdaBundle\Entity\SifdaSolicitudServicio $idSolicitudServicio = null)
    {
        $this->idSolicitudServicio = $idSolicitudServicio;

        return $this;
    }

    /**
     * Get idSolicitudServicio
     *
     * @return \Minsal\sifdaBundle\Entity\SifdaSolicitudServicio 
     */
    public function getIdSolicitudServicio()
    {
        return $this->idSolicitudServicio;
    }
    
     public function __toString()
    {
        return $this->id.' - '.$this->descripcion;
    }
}
