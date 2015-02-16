<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SidplaSubactividad
 *
 * @ORM\Table(name="sidpla_subactividad", uniqueConstraints={@ORM\UniqueConstraint(name="idx_sidpla_subactividad", columns={"id"})}, indexes={@ORM\Index(name="es_conformado_fk", columns={"id_actividad"}), @ORM\Index(name="gestiona_fk", columns={"id_empleado"})})
 * @ORM\Entity
 */
class SidplaSubactividad
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sidpla_subactividad_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

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
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=false)
     */
    private $activo;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_anual", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $metaAnual;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion_meta_anual", type="string", length=50, nullable=false)
     */
    private $descripcionMetaAnual;

    /**
     * @var string
     *
     * @ORM\Column(name="indicador", type="text", nullable=false)
     */
    private $indicador;

    /**
     * @var string
     *
     * @ORM\Column(name="medio_verificacion", type="string", length=300, nullable=true)
     */
    private $medioVerificacion;

    /**
     * @var \SidplaActividad
     *
     * @ORM\ManyToOne(targetEntity="SidplaActividad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_actividad", referencedColumnName="id")
     * })
     */
    private $idActividad;

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
     * @return SidplaSubactividad
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
     * @return SidplaSubactividad
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
     * Set activo
     *
     * @param boolean $activo
     * @return SidplaSubactividad
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set metaAnual
     *
     * @param string $metaAnual
     * @return SidplaSubactividad
     */
    public function setMetaAnual($metaAnual)
    {
        $this->metaAnual = $metaAnual;

        return $this;
    }

    /**
     * Get metaAnual
     *
     * @return string 
     */
    public function getMetaAnual()
    {
        return $this->metaAnual;
    }

    /**
     * Set descripcionMetaAnual
     *
     * @param string $descripcionMetaAnual
     * @return SidplaSubactividad
     */
    public function setDescripcionMetaAnual($descripcionMetaAnual)
    {
        $this->descripcionMetaAnual = $descripcionMetaAnual;

        return $this;
    }

    /**
     * Get descripcionMetaAnual
     *
     * @return string 
     */
    public function getDescripcionMetaAnual()
    {
        return $this->descripcionMetaAnual;
    }

    /**
     * Set indicador
     *
     * @param string $indicador
     * @return SidplaSubactividad
     */
    public function setIndicador($indicador)
    {
        $this->indicador = $indicador;

        return $this;
    }

    /**
     * Get indicador
     *
     * @return string 
     */
    public function getIndicador()
    {
        return $this->indicador;
    }

    /**
     * Set medioVerificacion
     *
     * @param string $medioVerificacion
     * @return SidplaSubactividad
     */
    public function setMedioVerificacion($medioVerificacion)
    {
        $this->medioVerificacion = $medioVerificacion;

        return $this;
    }

    /**
     * Get medioVerificacion
     *
     * @return string 
     */
    public function getMedioVerificacion()
    {
        return $this->medioVerificacion;
    }

    /**
     * Set idActividad
     *
     * @param \Minsal\sifdaBundle\Entity\SidplaActividad $idActividad
     * @return SidplaSubactividad
     */
    public function setIdActividad(\Minsal\sifdaBundle\Entity\SidplaActividad $idActividad = null)
    {
        $this->idActividad = $idActividad;

        return $this;
    }

    /**
     * Get idActividad
     *
     * @return \Minsal\sifdaBundle\Entity\SidplaActividad 
     */
    public function getIdActividad()
    {
        return $this->idActividad;
    }

    /**
     * Set idEmpleado
     *
     * @param \Minsal\sifdaBundle\Entity\CtlEmpleado $idEmpleado
     * @return SidplaSubactividad
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
    
    public function __toString() 
    {
        return $this->descripcion;
    }
}
