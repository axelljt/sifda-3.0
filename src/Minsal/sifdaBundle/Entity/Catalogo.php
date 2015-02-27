<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Catalogo
 *
 * @ORM\Table(name="catalogo", uniqueConstraints={@ORM\UniqueConstraint(name="idx_catalogo", columns={"id"})})
 * @ORM\Entity
 */
class Catalogo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="catalogo_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=150, nullable=false)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="sistema", type="integer", nullable=false)
     */
    private $sistema;

    /**
     * @var string
     *
     * @ORM\Column(name="ref1", type="string", length=20, nullable=false)
     */
    private $ref1;



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
     * @return Catalogo
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Catalogo
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
     * Set sistema
     *
     * @param integer $sistema
     * @return Catalogo
     */
    public function setSistema($sistema)
    {
        $this->sistema = $sistema;

        return $this;
    }

    /**
     * Get sistema
     *
     * @return integer 
     */
    public function getSistema()
    {
        return $this->sistema;
    }

    /**
     * Set ref1
     *
     * @param string $ref1
     * @return Catalogo
     */
    public function setRef1($ref1)
    {
        $this->ref1 = $ref1;

        return $this;
    }

    /**
     * Get ref1
     *
     * @return string 
     */
    public function getRef1()
    {
        return $this->ref1;
    }
    
     public function __toString() {
        return $this->nombre;
    }
}
