<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaTipoRecurso
 *
 * @ORM\Table(name="sifda_tipo_recurso", uniqueConstraints={@ORM\UniqueConstraint(name="idx_sifda_tipo_recurso", columns={"id"})})
 * @ORM\Entity
 */
class SifdaTipoRecurso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_tipo_recurso_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=false)
     */
    private $descripcion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rrhh", type="boolean", nullable=false)
     */
    private $rrhh;



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
     * @return SifdaTipoRecurso
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
     * @return SifdaTipoRecurso
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
     * Set rrhh
     *
     * @param boolean $rrhh
     * @return SifdaTipoRecurso
     */
    public function setRrhh($rrhh)
    {
        $this->rrhh = $rrhh;

        return $this;
    }

    /**
     * Get rrhh
     *
     * @return boolean 
     */
    public function getRrhh()
    {
        return $this->rrhh;
    }
    
     public function __toString() {
        return $this->nombre;
    }
}
