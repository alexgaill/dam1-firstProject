<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[
        ORM\Column(type: 'string', length: 120),
        Assert\NotBlank(
            allowNull:false,
            message: "Ce champ doit être complété"
        ),
        Assert\Length(
            min:5,
            minMessage: "La catégorie doit avoir au minimum {{ limit }} caractères.",
            max:120,
            maxMessage: "La catégorie doit avoir au maximum {{ limit }} caractères."
        )
    ]
    private $title;

    #[
        ORM\Column(type: 'text'),
        Assert\NotBlank(
            allowNull:false,
            message: "Ce champ doit être complété"
        ),
        Assert\Length(
            min:100,
            minMessage: "La catégorie doit avoir au minimum {{ limit }} caractères.",
        )
    ]
    private $description;

    /**
     * Contient les 30 premiers mots de la description
     *
     * @var string
     */
    private $subDescription;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[
        ORM\Column(type: 'string', length: 40, nullable: true),
        Assert\File(
            mimeTypes:["image/jpeg", "image/png"],
            mimeTypesMessage: "Le format attendu est jpg, jpeg ou png"
        )
    ]
    private $picture;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSubDescription () :string
    {
        $subArray = explode(' ', $this->description, 26);
        $subArray = array_slice($subArray, 0, 24);
        return implode(' ', $subArray);
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPicture(): string|File|null
    {
        return $this->picture;
    }

    public function setPicture(string|File|null $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    #[ORM\PostRemove]
    public function deletePicture()
    {
        if (file_exists(__DIR__.'/../../public/assets/img/upload/'.$this->picture)) {
            unlink(__DIR__.'/../../public/assets/img/upload/'.$this->picture);
        }
    }
}
