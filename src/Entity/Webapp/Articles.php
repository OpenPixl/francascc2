<?php

namespace App\Entity\Webapp;

use App\Entity\Admin\College;
use App\Entity\Admin\User;
use App\Repository\Webapp\ArticlesRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable()
 */
class Articles
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=College::class, inversedBy="articles")
     */
    private $college;

    /**
     * @ORM\ManyToMany(targetEntity=Section::class, inversedBy="articles")
     */
    private $section;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isTitleShow;

    /**
     * @ORM\ManyToOne(targetEntity=Theme::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $theme;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="article_images", fileNameProperty="imageName", size="imageSize")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $imageSize;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isShowReadMore;

    /**
     * @ORM\ManyToOne(targetEntity=Support::class)
     */
    private $support;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isShowCategory;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isShowSupport;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isShowTheme;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $doc;

    /**
     * @Vich\UploadableField(mapping="article_docs", fileNameProperty="doc")
     * @var File|null
     */
    private $docFile;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="articles")
     */
    private $category;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $intro;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCode;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isShowIntro;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="oneArticle")
     */
    private $sections;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowCreated = false;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->section = new ArrayCollection();
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime('now');
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime('now');
        return $this;
    }

    public function getCollege(): ?College
    {
        return $this->college;
    }

    public function setCollege(?College $college): self
    {
        $this->college = $college;

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

    public function __toString()
    {
        return $this->title;
    }

    public function getIsTitleShow(): ?bool
    {
        return $this->isTitleShow;
    }

    public function setIsTitleShow(?bool $isTitleShow): self
    {
        $this->isTitleShow = $isTitleShow;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    // fonction pour le slug du titre de l'article
    /**
     * Permet d'initialiser le slug ! Utilisation de slugify pour transformer une chaine de caractères en slug
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function initializeSlug() {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    public function getIsShowReadMore(): ?bool
    {
        return $this->isShowReadMore;
    }

    public function setIsShowReadMore(?bool $isShowReadMore): self
    {
        $this->isShowReadMore = $isShowReadMore;

        return $this;
    }

    public function getSupport(): ?Support
    {
        return $this->support;
    }

    public function setSupport(?Support $support): self
    {
        $this->support = $support;

        return $this;
    }

    public function getIsShowCategory(): ?bool
    {
        return $this->isShowCategory;
    }

    public function setIsShowCategory(bool $isShowCategory): self
    {
        $this->isShowCategory = $isShowCategory;

        return $this;
    }

    public function getIsShowSupport(): ?bool
    {
        return $this->isShowSupport;
    }

    public function setIsShowSupport(bool $isShowSupport): self
    {
        $this->isShowSupport = $isShowSupport;

        return $this;
    }

    public function getIsShowTheme(): ?bool
    {
        return $this->isShowTheme;
    }

    public function setIsShowTheme(bool $isShowTheme): self
    {
        $this->isShowTheme = $isShowTheme;

        return $this;
    }
    
    // Bloc insertion de Fichiers
    public function setDocFile(File $doc = null)
    {
        $this->docFile = $doc;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($doc) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getDocFile()
    {
        return $this->docFile;
    }

    public function setDoc($doc)
    {
        $this->doc = $doc;
    }

    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(?string $intro): self
    {
        $this->intro = $intro;

        return $this;
    }

    public function getIsCode(): ?bool
    {
        return $this->isCode;
    }

    public function setIsCode(bool $isCode): self
    {
        $this->isCode = $isCode;

        return $this;
    }

    public function getIsShowIntro(): ?bool
    {
        return $this->isShowIntro;
    }

    public function setIsShowIntro(?bool $isShowIntro): self
    {
        $this->isShowIntro = $isShowIntro;

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    public function getIsShowCreated(): ?bool
    {
        return $this->isShowCreated;
    }

    public function setIsShowCreated(bool $isShowCreated): self
    {
        $this->isShowCreated = $isShowCreated;

        return $this;
    }
}
