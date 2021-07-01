<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompteBancaire
 *
 * @ORM\Table(name="compte_bancaire")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompteBancaireRepository")
 */
class Comptebancaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="rib", type="string", length=255, nullable=true)
     */
    private $rib;

    /**
     * @var string
     *
     * @ORM\Column(name="banque", type="string", length=255, nullable=true)
     */
    private $banque;

    /**
     * @var string
     *
     * @ORM\Column(name="agence", type="string", length=255, nullable=true)
     */
    private $agence;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    private $label;
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;
    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     */
    private $pays;
    /**
     * @var string
     *
     * @ORM\Column(name="numero_compte", type="string", length=255, nullable=true)
     */
    private $numeroCompte;
    /**
     * @var string
     *
     * @ORM\Column(name="swift_code", type="string", length=255, nullable=true)
     */
    private $swiftCode;
    /**
     * @var string
     *
     * @ORM\Column(name="code_rib", type="string", length=255, nullable=true)
     */
    private $codeRib;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Facture", mappedBy="comptebancaire",cascade={"persist", "remove"})
     */
    private $factures;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Virementf", mappedBy="comptebancaire",cascade={"persist", "remove"})
     */
    private $virements;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rib
     *
     * @param string $rib
     *
     * @return CompteBancaire
     */
    public function setRib($rib)
    {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set banque
     *
     * @param string $banque
     *
     * @return CompteBancaire
     */
    public function setBanque($banque)
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * Get banque
     *
     * @return string
     */
    public function getBanque()
    {
        return $this->banque;
    }

    /**
     * Set agence
     *
     * @param string $agence
     *
     * @return CompteBancaire
     */
    public function setAgence($agence)
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * Get agence
     *
     * @return string
     */
    public function getAgence()
    {
        return $this->agence;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return CompteBancaire
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    public function __toString()
    {
        return $this->getBanque();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->factures = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add facture
     *
     * @param \AppBundle\Entity\Facture $facture
     *
     * @return Comptebancaire
     */
    public function addFacture(\AppBundle\Entity\Facture $facture)
    {
        $this->factures[] = $facture;

        return $this;
    }

    /**
     * Remove facture
     *
     * @param \AppBundle\Entity\Facture $facture
     */
    public function removeFacture(\AppBundle\Entity\Facture $facture)
    {
        $this->factures->removeElement($facture);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFactures()
    {
        return $this->factures;
    }

    /**
     * Add virement
     *
     * @param \AppBundle\Entity\Virementf $virement
     *
     * @return Comptebancaire
     */
    public function addVirement(\AppBundle\Entity\Virementf $virement)
    {
        $this->virements[] = $virement;

        return $this;
    }

    /**
     * Remove virement
     *
     * @param \AppBundle\Entity\Virementf $virement
     */
    public function removeVirement(\AppBundle\Entity\Virementf $virement)
    {
        $this->virements->removeElement($virement);
    }

    /**
     * Get virements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVirements()
    {
        return $this->virements;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Comptebancaire
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Comptebancaire
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Comptebancaire
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set numeroCompte
     *
     * @param string $numeroCompte
     *
     * @return Comptebancaire
     */
    public function setNumeroCompte($numeroCompte)
    {
        $this->numeroCompte = $numeroCompte;

        return $this;
    }

    /**
     * Get numeroCompte
     *
     * @return string
     */
    public function getNumeroCompte()
    {
        return $this->numeroCompte;
    }

    /**
     * Set swiftCode
     *
     * @param string $swiftCode
     *
     * @return Comptebancaire
     */
    public function setSwiftCode($swiftCode)
    {
        $this->swiftCode = $swiftCode;

        return $this;
    }

    /**
     * Get swiftCode
     *
     * @return string
     */
    public function getSwiftCode()
    {
        return $this->swiftCode;
    }

    /**
     * Set codeRib
     *
     * @param string $codeRib
     *
     * @return Comptebancaire
     */
    public function setCodeRib($codeRib)
    {
        $this->codeRib = $codeRib;

        return $this;
    }

    /**
     * Get codeRib
     *
     * @return string
     */
    public function getCodeRib()
    {
        return $this->codeRib;
    }
}
