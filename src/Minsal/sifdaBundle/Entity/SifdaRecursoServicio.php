<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaRecursoServicio
 *
 * @ORM\Table(name="sifda_recurso_servicio", indexes={@ORM\Index(name="define_recurso_fk", columns={"id_tipo_recurso_dependencia"}), @ORM\Index(name="valora_fk", columns={"id_informe_orden_trabajo"})})
 * @ORM\Entity
 */
class SifdaRecursoServicio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_recurso_servicio_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
     */
    private $cantidad;

    /**
     * @var float
     *
     * @ORM\Column(name="costo_total", type="float", precision=10, scale=0, nullable=false)
     */
    private $costoTotal;

    /**
     * @var \SifdaInformeOrdenTrabajo
     *
     * @ORM\ManyToOne(targetEntity="SifdaInformeOrdenTrabajo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_informe_orden_trabajo", referencedColumnName="id")
     * })
     */
    private $idInformeOrdenTrabajo;

    /**
     * @var \SifdaTipoRecursoDependencia
     *
     * @ORM\ManyToOne(targetEntity="SifdaTipoRecursoDependencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipo_recurso_dependencia", referencedColumnName="id")
     * })
     */
    private $idTipoRecursoDependencia;



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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return SifdaRecursoServicio
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set costoTotal
     *
     * @param float $costoTotal
     * @return SifdaRecursoServicio
     */
    public function setCostoTotal($costoTotal)
    {
        $this->costoTotal = $costoTotal;

        return $this;
    }

    /**
     * Get costoTotal
     *
     * @return float 
     */
    public function getCostoTotal()
    {
        return $this->costoTotal;
    }

    /**
     * Set idInformeOrdenTrabajo
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaInformeOrdenTrabajo $idInformeOrdenTrabajo
     * @return SifdaRecursoServicio
     */
    public function setIdInformeOrdenTrabajo(\Minsal\sifdaBundle\Entity\SifdaInformeOrdenTrabajo $idInformeOrdenTrabajo = null)
    {
        $this->idInformeOrdenTrabajo = $idInformeOrdenTrabajo;

        return $this;
    }

    /**
     * Get idInformeOrdenTrabajo
     *
     * @return \Minsal\sifdaBundle\Entity\SifdaInformeOrdenTrabajo 
     */
    public function getIdInformeOrdenTrabajo()
    {
        return $this->idInformeOrdenTrabajo;
    }

    /**
     * Set idTipoRecursoDependencia
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaTipoRecursoDependencia $idTipoRecursoDependencia
     * @return SifdaRecursoServicio
     */
    public function setIdTipoRecursoDependencia(\Minsal\sifdaBundle\Entity\SifdaTipoRecursoDependencia $idTipoRecursoDependencia = null)
    {
        $this->idTipoRecursoDependencia = $idTipoRecursoDependencia;

        return $this;
    }

    /**
     * Get idTipoRecursoDependencia
     *
     * @return \Minsal\sifdaBundle\Entity\SifdaTipoRecursoDependencia 
     */
    public function getIdTipoRecursoDependencia()
    {
        return $this->idTipoRecursoDependencia;
    }
}
