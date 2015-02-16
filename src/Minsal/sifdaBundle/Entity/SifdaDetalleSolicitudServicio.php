<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaDetalleSolicitudServicio
 *
 * @ORM\Table(name="sifda_detalle_solicitud_servicio", uniqueConstraints={@ORM\UniqueConstraint(name="idx_detalle_solicitud_servicio", columns={"id"})}, indexes={@ORM\Index(name="describe_fk", columns={"id_solicitud_servicio"})})
 * @ORM\Entity
 */
class SifdaDetalleSolicitudServicio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_detalle_solicitud_servicio_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=false)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_solicitada", type="integer", nullable=false)
     */
    private $cantidadSolicitada;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_aprobada", type="integer", nullable=true)
     */
    private $cantidadAprobada;

    /**
     * @var string
     *
     * @ORM\Column(name="justificacion", type="text", nullable=true)
     */
    private $justificacion;

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
     * Set descripcion
     *
     * @param string $descripcion
     * @return SifdaDetalleSolicitudServicio
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
     * Set cantidadSolicitada
     *
     * @param integer $cantidadSolicitada
     * @return SifdaDetalleSolicitudServicio
     */
    public function setCantidadSolicitada($cantidadSolicitada)
    {
        $this->cantidadSolicitada = $cantidadSolicitada;

        return $this;
    }

    /**
     * Get cantidadSolicitada
     *
     * @return integer 
     */
    public function getCantidadSolicitada()
    {
        return $this->cantidadSolicitada;
    }

    /**
     * Set cantidadAprobada
     *
     * @param integer $cantidadAprobada
     * @return SifdaDetalleSolicitudServicio
     */
    public function setCantidadAprobada($cantidadAprobada)
    {
        $this->cantidadAprobada = $cantidadAprobada;

        return $this;
    }

    /**
     * Get cantidadAprobada
     *
     * @return integer 
     */
    public function getCantidadAprobada()
    {
        return $this->cantidadAprobada;
    }

    /**
     * Set justificacion
     *
     * @param string $justificacion
     * @return SifdaDetalleSolicitudServicio
     */
    public function setJustificacion($justificacion)
    {
        $this->justificacion = $justificacion;

        return $this;
    }

    /**
     * Get justificacion
     *
     * @return string 
     */
    public function getJustificacion()
    {
        return $this->justificacion;
    }

    /**
     * Set idSolicitudServicio
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaSolicitudServicio $idSolicitudServicio
     * @return SifdaDetalleSolicitudServicio
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
}
