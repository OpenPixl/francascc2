<?php

namespace App\Entity\Admin;

use App\Entity\Webapp\Articles;
use App\Entity\Webapp\Ressources;
use App\Entity\Webapp\Section;
use App\Repository\Admin\CollegeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CollegeRepository::class)
 * @Vich\Uploadable
 */
class College
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $complement;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $collegeEmail;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $groupEmail;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $collegePhone;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)

     */
    private $groupPhone;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $animateur;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="college")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity=Section::class, inversedBy="colleges")
     */
    private $section;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $GroupDescription;

    //code d'insertion pour le logo
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="college_image", fileNameProperty="logoName", size="logoSize")
     *
     * @var File|null
     */
    private $logoFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $logoName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $logoSize;

    // BLOC Header college
    //code d'insertion pour une image
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="college_image", fileNameProperty="headerName", size="headerSize")
     *
     * @var File|null
     */
    private $headerFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $headerName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $headerSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="singleCollege")
     */
    private $sections;

    /**
     * @ORM\OneToMany(targetEntity=Ressources::class, mappedBy="college")
     */
    private $ressources;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $workMeeting;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="college")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSupprHeader = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSupprLogo = false;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->section = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->ressources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCollegeEmail(): ?string
    {
        return $this->collegeEmail;
    }

    public function setCollegeEmail(?string $collegeEmail): self
    {
        $this->collegeEmail = $collegeEmail;

        return $this;
    }

    public function getGroupEmail(): ?string
    {
        return $this->groupEmail;
    }

    public function setGroupEmail(?string $groupEmail): self
    {
        $this->groupEmail = $groupEmail;

        return $this;
    }

    public function getCollegePhone(): ?string
    {
        return $this->collegePhone;
    }

    public function setCollegePhone(?string $collegePhone): self
    {
        $this->collegePhone = $collegePhone;

        return $this;
    }

    public function getGroupPhone(): ?string
    {
        return $this->groupPhone;
    }

    public function setGroupPhone(?string $groupPhone): self
    {
        $this->groupPhone = $groupPhone;

        return $this;
    }

    public function getAnimateur(): ?string
    {
        return $this->animateur;
    }

    public function setAnimateur(?string $animateur): self
    {
        $this->animateur = $animateur;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreateAt(): self
    {
        $this->createAt = new \DateTime();

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCollege($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getCollege() === $this) {
                $article->setCollege(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSection(): Collection
    {
        return $this->section;
    }

    public function addSection(Section $section): self
    {
        if (!$this->section->contains($section)) {
            $this->section[] = $section;
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->section->contains($section)) {
            $this->section->removeElement($section);
        }

        return $this;
    }

    public function getGroupDescription(): ?string
    {
        return $this->GroupDescription;
    }

    public function setGroupDescription(?string $GroupDescription): self
    {
        $this->GroupDescription = $GroupDescription;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    // code get et set logo
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $logoFile
     */
    public function setLogoFile(?File $logoFile = null): void
    {
        $this->logoFile = $logoFile;

        if (null !== $logoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setLogoName(?string $logoName): void
    {
        $this->logoName = $logoName;
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoSize(?int $logoSize): void
    {
        $this->logoSize = $logoSize;
    }

    public function getLogoSize(): ?int
    {
        return $this->logoSize;
    }


    // code get et set header
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $headerFile
     */
    public function setHeaderFile(?File $headerFile = null): void
    {
        $this->headerFile = $headerFile;

        if (null !== $headerFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getHeaderFile(): ?File
    {
        return $this->headerFile;
    }

    public function setHeaderName(?string $headerName): void
    {
        $this->headerName = $headerName;
    }

    public function getHeaderName(): ?string
    {
        return $this->headerName;
    }

    public function setHeaderSize(?int $headerSize): void
    {
        $this->headerSize = $headerSize;
    }

    public function getHeaderSize(): ?int
    {
        return $this->headerSize;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    /**
     * @return Collection|Ressources[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressources $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setCollege($this);
        }

        return $this;
    }

    public function removeRessource(Ressources $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getCollege() === $this) {
                $ressource->setCollege(null);
            }
        }

        return $this;
    }

    public function getWorkMeeting(): ?string
    {
        return $this->workMeeting;
    }

    public function setWorkMeeting(?string $workMeeting): self
    {
        $this->workMeeting = $workMeeting;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIsSupprHeader(): ?bool
    {
        return $this->isSupprHeader;
    }

    public function setIsSupprHeader(?bool $isSupprHeader): self
    {
        $this->isSupprHeader = $isSupprHeader;

        return $this;
    }

    public function getIsSupprLogo(): ?bool
    {
        return $this->isSupprLogo;
    }

    public function setIsSupprLogo(?bool $isSupprLogo): self
    {
        $this->isSupprLogo = $isSupprLogo;

        return $this;
    }

}
