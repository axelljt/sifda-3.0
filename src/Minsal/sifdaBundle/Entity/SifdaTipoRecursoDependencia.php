<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaTipoRecursoDependencia
 *
 * @ORM\Table(name="sifda_tipo_recurso_dependencia", indexes={@ORM\Index(name="utiliza_fk", columns={"id_dependencia_establecimiento"}), @ORM\Index(name="se_compone_fk", columns={"id_tipo_recurso"})})
 * @ORM\Entity
 */
class SifdaTipoRecursoDependencia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_tipo_recurso_dependencia_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="costo_unitario", type="float", precision=10, scale=0, nullable=false)
     */
    private $costoUnitario;

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
     * @var \SifdaTipoRecurso
     *
     * @ORM\ManyToOne(targetEntity="SifdaTipoRecurso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipo_recurso", referencedColumnName="id")
     * })
     */
    private $idTipoRecurso;



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
     * Set costoUnitario
     *
     * @param float $costoUnitario
     * @return SifdaTipoRecursoDependencia
     */
    public function setCostoUnitario($costoUnitario)
    {
        $this->costoUnitario = $costoUnitario;

        return $this;
    }

    /**
     * Get costoUnitario
     *
     * @return float 
     */
    public function getCostoUnitario()
    {
        return $this->costoUnitario;
    }

    /**
     * Set idDependenciaEstablecimiento
     *
     * @param \Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento $idDependenciaEstablecimiento
     * @return SifdaTipoRecursoDependencia
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
     * Set idTipoRecurso
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaTipoRecurso $idTipoRecurso
     * @return SifdaTipoRecursoDependencia
     */
    public function setIdTipoRecurso(\Minsal\sifdaBundle\Entity\SifdaTipoRecurso $idTipoRecurso = null)
    {
        $this->idTipoRecurso = $idTipoRecurso;

        return $this;
    }

    /**
     * Get idTipoRecurso
     *
     * @return \Minsal\sifdaBundle\Entity\SifdaTipoRecurso 
     */
    public function getIdTipoRecurso()
    {
        return $this->idTipoRecurso;
    }
    
    public function __toString() 
    {
        return $this->getIdTipoRecurso()->getNombre();
    }
}
