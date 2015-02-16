<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaTrackingEstado
 *
 * @ORM\Table(name="sifda_tracking_estado", uniqueConstraints={@ORM\UniqueConstraint(name="idx_tracking_estado", columns={"id"})}, indexes={@ORM\Index(name="almacena_fk", columns={"id_estado"}), @ORM\Index(name="se_registra_fk", columns={"id_orden_trabajo"}), @ORM\Index(name="registra_etapa_fk", columns={"id_etapa"})})
 * @ORM\Entity
 */
class SifdaTrackingEstado
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_tracking_estado_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="datetime", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true)
     */
    private $fechaFin;

    /**
     * @var string
     *
     * @ORM\Column(name="prog_actividad", type="string", length=40, nullable=false)
     */
    private $progActividad;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable=false)
     */
    private $observacion;

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
     * @var \SifdaOrdenTrabajo
     *
     * @ORM\ManyToOne(targetEntity="SifdaOrdenTrabajo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_orden_trabajo", referencedColumnName="id")
     * })
     */
    private $idOrdenTrabajo;

    /**
     * @var \SifdaRutaCicloVida
     *
     * @ORM\ManyToOne(targetEntity="SifdaRutaCicloVida")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_etapa", referencedColumnName="id")
     * })
     */
    private $idEtapa;



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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return SifdaTrackingEstado
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return SifdaTrackingEstado
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set progActividad
     *
     * @param string $progActividad
     * @return SifdaTrackingEstado
     */
    public function setProgActividad($progActividad)
    {
        $this->progActividad = $progActividad;

        return $this;
    }

    /**
     * Get progActividad
     *
     * @return string 
     */
    public function getProgActividad()
    {
        return $this->progActividad;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return SifdaTrackingEstado
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
     * Set idEstado
     *
     * @param \Minsal\sifdaBundle\Entity\CatalogoDetalle $idEstado
     * @return SifdaTrackingEstado
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
     * Set idOrdenTrabajo
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo $idOrdenTrabajo
     * @return SifdaTrackingEstado
     */
    public function setIdOrdenTrabajo(\Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo $idOrdenTrabajo = null)
    {
        $this->idOrdenTrabajo = $idOrdenTrabajo;

        return $this;
    }

    /**
     * Get idOrdenTrabajo
     *
     * @return \Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo 
     */
    public function getIdOrdenTrabajo()
    {
        return $this->idOrdenTrabajo;
    }

    /**
     * Set idEtapa
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaRutaCicloVida $idEtapa
     * @return SifdaTrackingEstado
     */
    public function setIdEtapa(\Minsal\sifdaBundle\Entity\SifdaRutaCicloVida $idEtapa = null)
    {
        $this->idEtapa = $idEtapa;

        return $this;
    }

    /**
     * Get idEtapa
     *
     * @return \Minsal\sifdaBundle\Entity\SifdaRutaCicloVida 
     */
    public function getIdEtapa()
    {
        return $this->idEtapa;
    }
}
