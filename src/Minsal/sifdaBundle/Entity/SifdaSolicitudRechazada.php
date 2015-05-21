<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaSolicitudRechazada
 *
 * @ORM\Table(name="sifda_solicitud_rechazada", indexes={@ORM\Index(name="es_rechazada_fk", columns={"id_solicitud_servicio"}), @ORM\Index(name="muestra_motivo_fk", columns={"id_razon_rechazo"})})
 * @ORM\Entity
 */
class SifdaSolicitudRechazada
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_solicitud_rechazada_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \CatalogoDetalle
     *
     * @ORM\ManyToOne(targetEntity="CatalogoDetalle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_razon_rechazo", referencedColumnName="id")
     * })
     */
    private $idRazonRechazo;

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
     * Set idRazonRechazo
     *
     * @param \Minsal\sifdaBundle\Entity\CatalogoDetalle $idRazonRechazo
     * @return SifdaSolicitudRechazada
     */
    public function setIdRazonRechazo(\Minsal\sifdaBundle\Entity\CatalogoDetalle $idRazonRechazo = null)
    {
        $this->idRazonRechazo = $idRazonRechazo;

        return $this;
    }

    /**
     * Get idRazonRechazo
     *
     * @return \Minsal\sifdaBundle\Entity\CatalogoDetalle 
     */
    public function getIdRazonRechazo()
    {
        return $this->idRazonRechazo;
    }

    /**
     * Set idSolicitudServicio
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaSolicitudServicio $idSolicitudServicio
     * @return SifdaSolicitudRechazada
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
