<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'service_type')]
class ServiceType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'serviceType', targetEntity: CompanyService::class)]
    private Collection $companyServices;

    public function __construct()
    {
        $this->companyServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCompanyServices(): Collection
    {
        return $this->companyServices;
    }

    public function addCompanyService(CompanyService $companyService): self
    {
        if (!$this->companyServices->contains($companyService)) {
            $this->companyServices[] = $companyService;
            $companyService->setServiceType($this);
        }

        return $this;
    }

    public function removeCompanyService(CompanyService $companyService): self
    {
        if ($this->companyServices->removeElement($companyService)) {
            // set the owning side to null (unless already changed)
            if ($companyService->getServiceType() === $this) {
                $companyService->setServiceType(null);
            }
        }

        return $this;
    }
}
