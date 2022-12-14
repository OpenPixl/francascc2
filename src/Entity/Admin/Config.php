<?php

namespace App\Entity\Admin;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Admin\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 * @Vich\Uploadable
 */
class Config
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOffline;

    //code d'insertion pour le logo
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="collection_image", fileNameProperty="logoName", size="logoSize")
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


    //code d'insertion pour une image
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="collection_image", fileNameProperty="headerName", size="headerSize")
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
    private $isHeaderShow;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowTitleSiteHome = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vignetteFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vignetteName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vignetteSize;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsOffline(): ?bool
    {
        return $this->isOffline;
    }

    public function setIsOffline(bool $isOffline): self
    {
        $this->isOffline = $isOffline;

        return $this;
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

    public function getIsHeaderShow(): ?bool
    {
        return $this->isHeaderShow;
    }

    public function setIsHeaderShow(?bool $isHeaderShow): self
    {
        $this->isHeaderShow = $isHeaderShow;

        return $this;
    }

    public function getIsShowTitleSiteHome(): ?bool
    {
        return $this->isShowTitleSiteHome;
    }

    public function setIsShowTitleSiteHome(bool $isShowTitleSiteHome): self
    {
        $this->isShowTitleSiteHome = $isShowTitleSiteHome;

        return $this;
    }

    public function getVignetteFile(): ?string
    {
        return $this->vignetteFile;
    }

    public function setVignetteFile(string $vignetteFile): self
    {
        $this->vignetteFile = $vignetteFile;

        return $this;
    }

    public function getVignetteName(): ?string
    {
        return $this->vignetteName;
    }

    public function setVignetteName(string $vignetteName): self
    {
        $this->vignetteName = $vignetteName;

        return $this;
    }

    public function getVignetteSize(): ?string
    {
        return $this->vignetteSize;
    }

    public function setVignetteSize(string $vignetteSize): self
    {
        $this->vignetteSize = $vignetteSize;

        return $this;
    }
}
