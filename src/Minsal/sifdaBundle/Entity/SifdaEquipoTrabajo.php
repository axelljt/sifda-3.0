<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaEquipoTrabajo
 *
 * @ORM\Table(name="sifda_equipo_trabajo", indexes={@ORM\Index(name="es_atendida_fk", columns={"id_orden_trabajo"}), @ORM\Index(name="forma_parte_fk", columns={"id_empleado"})})
 * @ORM\Entity
 */
class SifdaEquipoTrabajo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_equipo_trabajo_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="responsable_equipo", type="boolean", nullable=false)
     */
    private $responsableEquipo;

    /**
     * @var \CtlEmpleado
     *
     * @ORM\ManyToOne(targetEntity="CtlEmpleado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_empleado", referencedColumnName="id")
     * })
     */
    private $idEmpleado;

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
     * Set responsableEquipo
     *
     * @param boolean $responsableEquipo
     * @return SifdaEquipoTrabajo
     */
    public function setResponsableEquipo($responsableEquipo)
    {
        $this->responsableEquipo = $responsableEquipo;

        return $this;
    }

    /**
     * Get responsableEquipo
     *
     * @return boolean 
     */
    public function getResponsableEquipo()
    {
        return $this->responsableEquipo;
    }

    /**
     * Set idEmpleado
     *
     * @param \Minsal\sifdaBundle\Entity\CtlEmpleado $idEmpleado
     * @return SifdaEquipoTrabajo
     */
    public function setIdEmpleado(\Minsal\sifdaBundle\Entity\CtlEmpleado $idEmpleado = null)
    {
        $this->idEmpleado = $idEmpleado;

        return $this;
    }

    /**
     * Get idEmpleado
     *
     * @return \Minsal\sifdaBundle\Entity\CtlEmpleado 
     */
    public function getIdEmpleado()
    {
        return $this->idEmpleado;
    }

    /**
     * Set idOrdenTrabajo
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo $idOrdenTrabajo
     * @return SifdaEquipoTrabajo
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
