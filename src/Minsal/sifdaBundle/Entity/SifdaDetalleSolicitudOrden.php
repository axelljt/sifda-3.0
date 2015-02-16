<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaDetalleSolicitudOrden
 *
 * @ORM\Table(name="sifda_detalle_solicitud_orden", uniqueConstraints={@ORM\UniqueConstraint(name="idx_det_solic_serv", columns={"id"})}, indexes={@ORM\Index(name="idx_id_det_sol_serv", columns={"id_detalle_solicitud_servicio"}), @ORM\Index(name="idx_det_orden_trabajo", columns={"id_orden_trabajo"})})
 * @ORM\Entity
 */
class SifdaDetalleSolicitudOrden
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_detalle_solicitud_orden_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \SifdaDetalleSolicitudServicio
     *
     * @ORM\ManyToOne(targetEntity="SifdaDetalleSolicitudServicio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_detalle_solicitud_servicio", referencedColumnName="id")
     * })
     */
    private $idDetalleSolicitudServicio;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idDetalleSolicitudServicio
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaDetalleSolicitudServicio $idDetalleSolicitudServicio
     * @return SifdaDetalleSolicitudOrden
     */
    public function setIdDetalleSolicitudServicio(\Minsal\sifdaBundle\Entity\SifdaDetalleSolicitudServicio $idDetalleSolicitudServicio = null)
    {
        $this->idDetalleSolicitudServicio = $idDetalleSolicitudServicio;

        return $this;
    }

    /**
     * Get idDetalleSolicitudServicio
     *
     * @return \Minsal\sifdaBundle\Entity\SifdaDetalleSolicitudServicio 
     */
    public function getIdDetalleSolicitudServicio()
    {
        return $this->idDetalleSolicitudServicio;
    }

    /**
     * Set idOrdenTrabajo
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo $idOrdenTrabajo
     * @return SifdaDetalleSolicitudOrden
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
}
