<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaRutaCicloVida
 *
 * @ORM\Table(name="sifda_ruta_ciclo_vida", uniqueConstraints={@ORM\UniqueConstraint(name="idx_sifda_ruta_ciclo_vida", columns={"id"})}, indexes={@ORM\Index(name="idx_id_ruta_etapa", columns={"id_etapa"}), @ORM\Index(name="IDX_26B328FEA36B7986", columns={"id_tipo_servicio"})})
 * @ORM\Entity(repositoryClass="Minsal\sifdaBundle\Repository\SifdaRutaCicloVidaRepository")
 */
class SifdaRutaCicloVida
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_ruta_ciclo_vida_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="referencia", type="text", nullable=true)
     */
    private $referencia;

    /**
     * @var integer
     *
     * @ORM\Column(name="jerarquia", type="integer", nullable=false)
     */
    private $jerarquia;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ignorar_sig", type="boolean", nullable=false)
     */
    private $ignorarSig;

    /**
     * @var integer
     *
     * @ORM\Column(name="peso", type="integer", nullable=false)
     */
    private $peso;

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
     * @var \SifdaTipoServicio
     *
     * @ORM\ManyToOne(targetEntity="SifdaTipoServicio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipo_servicio", referencedColumnName="id")
     * })
     */
    private $idTipoServicio;



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
     * @return SifdaRutaCicloVida
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
     * Set referencia
     *
     * @param string $referencia
     * @return SifdaRutaCicloVida
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * Get referencia
     *
     * @return string 
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set jerarquia
     *
     * @param integer $jerarquia
     * @return SifdaRutaCicloVida
     */
    public function setJerarquia($jerarquia)
    {
        $this->jerarquia = $jerarquia;

        return $this;
    }

    /**
     * Get jerarquia
     *
     * @return integer 
     */
    public function getJerarquia()
    {
        return $this->jerarquia;
    }

    /**
     * Set ignorarSig
     *
     * @param boolean $ignorarSig
     * @return SifdaRutaCicloVida
     */
    public function setIgnorarSig($ignorarSig)
    {
        $this->ignorarSig = $ignorarSig;

        return $this;
    }

    /**
     * Get ignorarSig
     *
     * @return boolean 
     */
    public function getIgnorarSig()
    {
        return $this->ignorarSig;
    }

    /**
     * Set peso
     *
     * @param integer $peso
     * @return SifdaRutaCicloVida
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return integer 
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set idEtapa
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaRutaCicloVida $idEtapa
     * @return SifdaRutaCicloVida
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

    /**
     * Set idTipoServicio
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaTipoServicio $idTipoServicio
     * @return SifdaRutaCicloVida
     */
    public function setIdTipoServicio(\Minsal\sifdaBundle\Entity\SifdaTipoServicio $idTipoServicio = null)
    {
        $this->idTipoServicio = $idTipoServicio;

        return $this;
    }

    /**
     * Get idTipoServicio
     *
     * @return \Minsal\sifdaBundle\Entity\SifdaTipoServicio 
     */
    public function getIdTipoServicio()
    {
        return $this->idTipoServicio;
    }
    
     public function __toString() {
        return $this->descripcion;
    }
}
