<?php

namespace App\Entity;

use App\Repository\ContainRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass=ContainRepository::class)
 */
class Contain implements \Serializable, \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Crypto::class, inversedBy="contains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $crypto;

    /**
     * @ORM\ManyToOne(targetEntity=Portfolio::class, inversedBy="contains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $portfolio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrypto(): ?Crypto
    {
        return $this->crypto;
    }

    public function setCrypto(?Crypto $crypto): self
    {
        $this->crypto = $crypto;

        return $this;
    }

    public function getPortfolio(): ?Portfolio
    {
        return $this->portfolio;
    }

    public function setPortfolio(?Portfolio $portfolio): self
    {
        $this->portfolio = $portfolio;

        return $this;
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function unserialize($data)
    {
        // TODO: Implement unserialize() method.
    }

    public function jsonSerialize()
    {
        return array( "contains" => [
            "id" => $this->getId(),
            "crypto" => $this->getCrypto(),
            "portfolio" => $this->getPortfolio(),

        ]);
    }
}
