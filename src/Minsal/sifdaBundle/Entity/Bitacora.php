<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bitacora
 *
 * @ORM\Table(name="bitacora", uniqueConstraints={@ORM\UniqueConstraint(name="idx_bitacora", columns={"id"})}, indexes={@ORM\Index(name="genera_fk", columns={"user_id"}), @ORM\Index(name="define_evento_fk", columns={"id_evento"})})
 * @ORM\Entity
 */
class Bitacora
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="bitacora_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_evento", type="date", nullable=false)
     */
    private $fechaEvento;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable=false)
     */
    private $observacion;

    /**
     * @var \CatalogoDetalle
     *
     * @ORM\ManyToOne(targetEntity="CatalogoDetalle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_evento", referencedColumnName="id")
     * })
     */
    private $idEvento;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fechaEvento
     *
     * @param \DateTime $fechaEvento
     * @return Bitacora
     */
    public function setFechaEvento($fechaEvento)
    {
        $this->fechaEvento = $fechaEvento;

        return $this;
    }

    /**
     * Get fechaEvento
     *
     * @return \DateTime 
     */
    public function getFechaEvento()
    {
        return $this->fechaEvento;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return Bitacora
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set idEvento
     *
     * @param \Minsal\sifdaBundle\Entity\CatalogoDetalle $idEvento
     * @return Bitacora
     */
    public function setIdEvento(\Minsal\sifdaBundle\Entity\CatalogoDetalle $idEvento = null)
    {
        $this->idEvento = $idEvento;

        return $this;
    }

    /**
     * Get idEvento
     *
     * @return \Minsal\sifdaBundle\Entity\CatalogoDetalle 
     */
    public function getIdEvento()
    {
        return $this->idEvento;
    }

    /**
     * Set user
     *
     * @param \Minsal\sifdaBundle\Entity\FosUserUser $user
     * @return Bitacora
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
}
