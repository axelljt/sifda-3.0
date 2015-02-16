<?php

namespace Minsal\sifdaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vwetapassolicitud
 *
 * @ORM\Table(name="vwetapassolicitud")
 * @ORM\Entity
 */
class Vwetapassolicitud {
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $id;
    
 /**
     * @var integer
     *
     * @ORM\Column(name="id_solicitud", type="integer", nullable=false)
     */
    private $idSolicitud;
            
    /**
     * @var string
     *
     * @ORM\Column(name="dsc_solicitud", type="text", nullable=false)
     */
    private $dscSolicitud;        
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fchrecep_solicitud", type="datetime", nullable=false)
     */
    private $fchrecepSolicitud;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fchreq_solicitud", type="datetime", nullable=true)
     */
    private $fchreqSolicitud;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_tipo_servicio", type="integer", nullable=false)
     */
    private $idTipoServicio;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nombre_tipo_servicio", type="string", length=75, nullable=false)
     */
    private $nombreTipoServicio;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dsc_tipo_servicio", type="text", nullable=false)
     */
    private $dscTipoServicio;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_ciclo_vida", type="integer", nullable=false)
     */
    private $idCicloVida;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="jerar_ciclo_vida", type="integer", nullable=false)
     */
    private $jerarCicloVida;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dsc_ciclo_vida", type="string", length=100, nullable=false)
     */
    private $dscCicloVida;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="etapa_peso", type="integer", nullable=false)
     */
    private $etapaPeso;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="ignorar_sig_etapa", type="boolean", nullable=false)
     */
    private $ignorarSigEtapa;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_subetapa", type="integer", nullable=true)
     */
    private $idSubetapa;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dsc_subetapa", type="string", length=100, nullable=true)
     */
    private $dscSubetapa;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="subetapa_peso", type="integer", nullable=false)
     */
    private $subetapaPeso;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="jerarquia_subetapa", type="integer", nullable=false)
     */
    private $jerarquiaSubetapa;
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="ignorar_sig_subetapa", type="boolean", nullable=false)
     */
    private $ignorarSigSubetapa;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_orden", type="integer", nullable=false)
     */
    private $idOrden;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="dsc_orden", type="string", length=100, nullable=false)
     */
    private $dscOrden;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fchcrea_orden", type="datetime", nullable=false)
     */
    private $fchcreaOrden;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fchfin_orden", type="datetime", nullable=false)
     */
    private $fchfinOrden;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_estado", type="integer", nullable=false)
     */
    private $idEstado;
        
    /**
     * @var string
     *
     * @ORM\Column(name="dsc_estado", type="string", length=100, nullable=false)
     */
    private $dscEstado;
       
    /**
     * @var integer
     *
     * @ORM\Column(name="id_empleado", type="integer", nullable=false)
     */
    private $idEmpleado;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom_empleado", type="string", length=100, nullable=false)
     */
    private $nomEmpleado;
    
     /**
     * @var integer
     *
     * @ORM\Column(name="depen_estab", type="integer", nullable=false)
     */
    private $depenEstab;
    
    public function getDepenEstab() {
        return $this->depenEstab;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getIdSolicitud() {
        return $this->idSolicitud;
    }

    public function getDscSolicitud() {
        return $this->dscSolicitud;
    }

    public function getFchrecepSolicitud() {
        return $this->fchrecepSolicitud;
    }

    public function getFchreqSolicitud() {
        return $this->fchreqSolicitud;
    }

    public function getIdTipoServicio() {
        return $this->idTipoServicio;
    }

    public function getNombreTipoServicio() {
        return $this->nombreTipoServicio;
    }

    public function getDscTipoServicio() {
        return $this->dscTipoServicio;
    }

    public function getIdCicloVida() {
        return $this->idCicloVida;
    }

    public function getJerarCicloVida() {
        return $this->jerarCicloVida;
    }

    public function getDscCicloVida() {
        return $this->dscCicloVida;
    }

    public function getEtapaPeso() {
        return $this->etapaPeso;
    }
    
    public function getIgnorarSigEtapa() {
        return $this->ignorarSigEtapa;
    }
    
    public function getIdSubetapa() {
        return $this->idSubetapa;
    }
    
    public function getDscSubetapa() {
        return $this->dscSubetapa;
    }
    
    public function getSubetapaPeso() {
        return $this->subetapaPeso;
    }
    
    public function getJerarquiaSubetapa() {
        return $this->jerarquiaSubetapa;
    }
    
    public function getIgnorarSigSubetapa() {
        return $this->ignorarSigSubetapa;
    }
    
    public function getIdOrden() {
        return $this->idOrden;
    }

    public function getDscOrden() {
        return $this->dscOrden;
    }

    public function getFchcreaOrden() {
        return $this->fchcreaOrden;
    }

    public function getFchfinOrden() {
        return $this->fchfinOrden;
    }

    public function getIdEstado() {
        return $this->idEstado;
    }

    public function getDscEstado() {
        return $this->dscEstado;
    }

    public function getIdEmpleado() {
        return $this->idEmpleado;
    }

    public function getNomEmpleado() {
        return $this->nomEmpleado;
    }

    
    public function setDepenEstab($depenEstab) {
        $this->depenEstab = $depenEstab;
    }
    
    public function setId($id) {
        $this->id = $id;
    }

    public function setIdSolicitud($idSolicitud) {
        $this->idSolicitud = $idSolicitud;
    }

    public function setDscSolicitud($dscSolicitud) {
        $this->dscSolicitud = $dscSolicitud;
    }

    public function setFchrecepSolicitud(\DateTime $fchrecepSolicitud) {
        $this->fchrecepSolicitud = $fchrecepSolicitud;
    }

    public function setFchreqSolicitud(\DateTime $fchreqSolicitud) {
        $this->fchreqSolicitud = $fchreqSolicitud;
    }

    public function setIdTipoServicio($idTipoServicio) {
        $this->idTipoServicio = $idTipoServicio;
    }

    public function setNombreTipoServicio($nombreTipoServicio) {
        $this->nombreTipoServicio = $nombreTipoServicio;
    }

    public function setDscTipoServicio($dscTipoServicio) {
        $this->dscTipoServicio = $dscTipoServicio;
    }

    public function setIdCicloVida($idCicloVida) {
        $this->idCicloVida = $idCicloVida;
    }

    public function setJerarCicloVida($jerarCicloVida) {
        $this->jerarCicloVida = $jerarCicloVida;
    }

    public function setDscCicloVida($dscCicloVida) {
        $this->dscCicloVida = $dscCicloVida;
    }

    public function setEtapaPeso($etapaPeso) {
        $this->etapaPeso = $etapaPeso;
    }

    public function setIdSubetapa($idSubetapa) {
        $this->idSubetapa = $idSubetapa;
    }
    
    public function setDscSubetapa($dscSubetapa) {
        $this->dscSubetapa = $dscSubetapa;
    }
    
    public function setJerarquiaSubetapa($jerarquiaSubetapa) {
        $this->jerarquiaSubetapa = $jerarquiaSubetapa;
    }
    
    public function setIdOrden($idOrden) {
        $this->idOrden = $idOrden;
    }

    public function setDscOrden($dscOrden) {
        $this->dscOrden = $dscOrden;
    }

    public function setFchcreaOrden(\DateTime $fchcreaOrden) {
        $this->fchcreaOrden = $fchcreaOrden;
    }

    public function setFchfinOrden(\DateTime $fchfinOrden) {
        $this->fchfinOrden = $fchfinOrden;
    }

    public function setIdEstado($idEstado) {
        $this->idEstado = $idEstado;
    }

    public function setDscEstado($dscEstado) {
        $this->dscEstado = $dscEstado;
    }

    public function setIdEmpleado($idEmpleado) {
        $this->idEmpleado = $idEmpleado;
    }

    public function setNomEmpleado($nomEmpleado) {
        $this->nomEmpleado = $nomEmpleado;
    }


}
