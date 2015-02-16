<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlDependencia
 *
 * @ORM\Table(name="ctl_dependencia", indexes={@ORM\Index(name="idx_id_dependencia", columns={"id_tipo_dependencia"})})
 * @ORM\Entity
 */
class CtlDependencia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ctl_dependencia_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false)
     */
    private $nombre;

    /**
     * @var \CtlTipoDependencia
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoDependencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipo_dependencia", referencedColumnName="id")
     * })
     */
    private $idTipoDependencia;



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
     * Set nombre
     *
     * @param string $nombre
     * @return CtlDependencia
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set idTipoDependencia
     *
     * @param \Minsal\sifdaBundle\Entity\CtlTipoDependencia $idTipoDependencia
     * @return CtlDependencia
     */
    public function setIdTipoDependencia(\Minsal\sifdaBundle\Entity\CtlTipoDependencia $idTipoDependencia = null)
    {
        $this->idTipoDependencia = $idTipoDependencia;

        return $this;
    }

    /**
     * Get idTipoDependencia
     *
     * @return \Minsal\sifdaBundle\Entity\CtlTipoDependencia 
     */
    public function getIdTipoDependencia()
    {
        return $this->idTipoDependencia;
    }
    
     public function __toString() 
    {
        return $this->nombre;
    }
}
