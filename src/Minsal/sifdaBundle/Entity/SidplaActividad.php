<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SidplaActividad
 *
 * @ORM\Table(name="sidpla_actividad", uniqueConstraints={@ORM\UniqueConstraint(name="idx_sidpla_actividad", columns={"id"})}, indexes={@ORM\Index(name="ejecuta_fk", columns={"id_empleado"}), @ORM\Index(name="se_identifica_fk", columns={"id_linea_estrategica"})})
 * @ORM\Entity
 */
class SidplaActividad
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sidpla_actividad_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="medio_verificacion", type="string", length=300, nullable=false)
     */
    private $medioVerificacion;

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
     * @var \SidplaLineaEstrategica
     *
     * @ORM\ManyToOne(targetEntity="SidplaLineaEstrategica")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_linea_estrategica", referencedColumnName="id")
     * })
     */
    private $idLineaEstrategica;

    /**
     * @var boolean
     *
     * @ORM\Column(name="generado", type="boolean")
     */
    private $esGenerado;


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
     * @return SidplaActividad
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
     * @return SidplaActividad
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
     * @return SidplaActividad
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
     * @return SidplaActividad
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
     * @return SidplaActividad
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
     * @return SidplaActividad
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
     * @return SidplaActividad
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
     * Set idEmpleado
     *
     * @param \Minsal\sifdaBundle\Entity\CtlEmpleado $idEmpleado
     * @return SidplaActividad
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
     * Set idLineaEstrategica
     *
     * @param \Minsal\sifdaBundle\Entity\SidplaLineaEstrategica $idLineaEstrategica
     * @return SidplaActividad
     */
    public function setIdLineaEstrategica(\Minsal\sifdaBundle\Entity\SidplaLineaEstrategica $idLineaEstrategica = null)
    {
        $this->idLineaEstrategica = $idLineaEstrategica;

        return $this;
    }

    /**
     * Get idLineaEstrategica
     *
     * @return \Minsal\sifdaBundle\Entity\SidplaLineaEstrategica 
     */
    public function getIdLineaEstrategica()
    {
        return $this->idLineaEstrategica;
    }
    public function getEsGenerado() {
        return $this->esGenerado;
    }

    public function setEsGenerado($esGenerado) {
        $this->esGenerado = $esGenerado;
    }

         public function __toString() 
    {
        return $this->descripcion;
    }
}