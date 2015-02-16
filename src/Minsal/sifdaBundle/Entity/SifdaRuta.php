<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaRuta
 *
 * @ORM\Table(name="sifda_ruta", uniqueConstraints={@ORM\UniqueConstraint(name="idx_sifda_ruta", columns={"id"})}, indexes={@ORM\Index(name="tiene_fk", columns={"id_tipo_servicio"})})
 * @ORM\Entity
 */
class SifdaRuta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_ruta_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_tipo_servicio", type="integer", nullable=true)
     */
    private $idTipoServicio;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=75, nullable=false)
     */
    private $tipo;



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
     * Set idTipoServicio
     *
     * @param integer $idTipoServicio
     * @return SifdaRuta
     */
    public function setIdTipoServicio($idTipoServicio)
    {
        $this->idTipoServicio = $idTipoServicio;

        return $this;
    }

    /**
     * Get idTipoServicio
     *
     * @return integer 
     */
    public function getIdTipoServicio()
    {
        return $this->idTipoServicio;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return SifdaRuta
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
     * Set tipo
     *
     * @param string $tipo
     * @return SifdaRuta
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
