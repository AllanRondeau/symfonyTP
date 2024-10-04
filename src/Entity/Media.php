<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'media', orphanRemoval: true)]
    private Collection $comments;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'medias')]
    private Collection $categories;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'media')]
    private Collection $languages;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'languages')]
    private Collection $media;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setMedia($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getMedia() === $this) {
                $comment->setMedia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(self $language): static
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
        }

        return $this;
    }

    public function removeLanguage(self $language): static
    {
        $this->languages->removeElement($language);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(self $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->addLanguage($this);
        }

        return $this;
    }

    public function removeMedium(self $medium): static
    {
        if ($this->media->removeElement($medium)) {
            $medium->removeLanguage($this);
        }

        return $this;
    }
}
