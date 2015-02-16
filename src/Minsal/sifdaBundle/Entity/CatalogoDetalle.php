<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CatalogoDetalle
 *
 * @ORM\Table(name="catalogo_detalle", indexes={@ORM\Index(name="especifica_fk", columns={"id_catalogo"})})
 * @ORM\Entity
 */
class CatalogoDetalle
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="catalogo_detalle_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="ref1", type="string", length=20, nullable=false)
     */
    private $ref1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estatus", type="boolean", nullable=false)
     */
    private $estatus;

    /**
     * @var \Catalogo
     *
     * @ORM\ManyToOne(targetEntity="Catalogo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_catalogo", referencedColumnName="id")
     * })
     */
    private $idCatalogo;



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
     * @return CatalogoDetalle
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
     * Set ref1
     *
     * @param string $ref1
     * @return CatalogoDetalle
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

    /**
     * Set estatus
     *
     * @param boolean $estatus
     * @return CatalogoDetalle
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * Get estatus
     *
     * @return boolean 
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set idCatalogo
     *
     * @param \Minsal\sifdaBundle\Entity\Catalogo $idCatalogo
     * @return CatalogoDetalle
     */
    public function setIdCatalogo(\Minsal\sifdaBundle\Entity\Catalogo $idCatalogo = null)
    {
        $this->idCatalogo = $idCatalogo;

        return $this;
    }

    /**
     * Get idCatalogo
     *
     * @return \Minsal\sifdaBundle\Entity\Catalogo 
     */
    public function getIdCatalogo()
    {
        return $this->idCatalogo;
    }

     public function __toString() {
        return $this->descripcion;
    }
    
}

