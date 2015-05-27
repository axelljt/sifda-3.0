<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaSolicitudServicio
 *
 * @ORM\Table(name="sifda_solicitud_servicio", uniqueConstraints={@ORM\UniqueConstraint(name="idx_sifda_solicitud_servicio", columns={"id"})}, indexes={@ORM\Index(name="establece_estado_fk", columns={"id_estado"}), @ORM\Index(name="solicita_fk", columns={"id_dependencia_establecimiento"}), @ORM\Index(name="define_fk", columns={"id_tipo_servicio"}), @ORM\Index(name="define_medio_fk", columns={"id_medio_solicita"}), @ORM\Index(name="ingresa_fk", columns={"user_id"})})
  * @ORM\Entity(repositoryClass="Minsal\sifdaBundle\Repository\SifdaSolicitudServicioRepository")
 */
class SifdaSolicitudServicio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_solicitud_servicio_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=false)
     */
    private $descripcion;
    
    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_recepcion", type="datetime", nullable=false)
     */
    private $fechaRecepcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_requiere", type="datetime", nullable=true)
     */
    private $fechaRequiere;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_finaliza", type="datetime", nullable=true)
     */
    private $fechaFinaliza;
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
     * @var \CatalogoDetalle
     *
     * @ORM\ManyToOne(targetEntity="CatalogoDetalle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_medio_solicita", referencedColumnName="id")
     * })
     */
    private $idMedioSolicita;

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
     * @var \FosUserUser
     *
     * @ORM\ManyToOne(targetEntity="FosUserUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

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
     * @return SifdaSolicitudServicio
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
     * Set fechaRecepcion
     *
     * @param \DateTime $fechaRecepcion
     * @return SifdaSolicitudServicio
     */
    public function setFechaRecepcion($fechaRecepcion)
    {
        $this->fechaRecepcion = $fechaRecepcion;

        return $this;
    }
    public function getFechaFinaliza() {
        return $this->fechaFinaliza;
    }

    public function setFechaFinaliza(\DateTime $fechaFinaliza) {
        $this->fechaFinaliza = $fechaFinaliza;
    }
    /**
     * Get fechaRecepcion
     *
     * @return \DateTime 
     */
    public function getFechaRecepcion()
    {
        return $this->fechaRecepcion;
    }

    /**
     * Set fechaRequiere
     *
     * @param \DateTime $fechaRequiere
     * @return SifdaSolicitudServicio
     */
    public function setFechaRequiere($fechaRequiere)
    {
        $this->fechaRequiere = $fechaRequiere;

        return $this;
    }

    /**
     * Get fechaRequiere
     *
     * @return \DateTime 
     */
    public function getFechaRequiere()
    {
        return $this->fechaRequiere;
    }

    /**
     * Set idEstado
     *
     * @param \Minsal\sifdaBundle\Entity\CatalogoDetalle $idEstado
     * @return SifdaSolicitudServicio
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
     * Set idMedioSolicita
     *
     * @param \Minsal\sifdaBundle\Entity\CatalogoDetalle $idMedioSolicita
     * @return SifdaSolicitudServicio
     */
    public function setIdMedioSolicita(\Minsal\sifdaBundle\Entity\CatalogoDetalle $idMedioSolicita = null)
    {
        $this->idMedioSolicita = $idMedioSolicita;

        return $this;
    }

    /**
     * Get idMedioSolicita
     *
     * @return \Minsal\sifdaBundle\Entity\CatalogoDetalle 
     */
    public function getIdMedioSolicita()
    {
        return $this->idMedioSolicita;
    }

    /**
     * Set idDependenciaEstablecimiento
     *
     * @param \Minsal\sifdaBundle\Entity\CtlDependenciaEstablecimiento $idDependenciaEstablecimiento
     * @return SifdaSolicitudServicio
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
     * Set user
     *
     * @param \Minsal\sifdaBundle\Entity\FosUserUser $user
     * @return SifdaSolicitudServicio
     */
    public function setUser(\Minsal\sifdaBundle\Entity\FosUserUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Minsal\sifdaBundle\Entity\FosUserUser 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set idTipoServicio
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaTipoServicio $idTipoServicio
     * @return SifdaSolicitudServicio
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
    
     public function __toString()
    {
        return $this->id.' - '.$this->descripcion;
    }
}
