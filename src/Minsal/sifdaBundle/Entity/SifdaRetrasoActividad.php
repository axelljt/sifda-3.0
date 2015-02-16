<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SifdaRetrasoActividad
 *
 * @ORM\Table(name="sifda_retraso_actividad", uniqueConstraints={@ORM\UniqueConstraint(name="idx_sifda_retraso_actividad", columns={"id"})}, indexes={@ORM\Index(name="puede_tener_fk", columns={"id_orden_trabajo"})})
 * @ORM\Entity
 */
class SifdaRetrasoActividad
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sifda_retraso_actividad_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="razon_retraso", type="text", nullable=false)
     */
    private $razonRetraso;

    /**
     * @var \SifdaOrdenTrabajo
     *
     * @ORM\ManyToOne(targetEntity="SifdaOrdenTrabajo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_orden_trabajo", referencedColumnName="id")
     * })
     */
    private $idOrdenTrabajo;



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
     * Set razonRetraso
     *
     * @param string $razonRetraso
     * @return SifdaRetrasoActividad
     */
    public function setRazonRetraso($razonRetraso)
    {
        $this->razonRetraso = $razonRetraso;

        return $this;
    }

    /**
     * Get razonRetraso
     *
     * @return string 
     */
    public function getRazonRetraso()
    {
        return $this->razonRetraso;
    }

    /**
     * Set idOrdenTrabajo
     *
     * @param \Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo $idOrdenTrabajo
     * @return SifdaRetrasoActividad
     */
    public function setIdOrdenTrabajo(\Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo $idOrdenTrabajo = null)
    {
        $this->idOrdenTrabajo = $idOrdenTrabajo;

        return $this;
    }

    /**
     * Get idOrdenTrabajo
     *
     * @return \Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo 
     */
    public function getIdOrdenTrabajo()
    {
        return $this->idOrdenTrabajo;
    }
}
