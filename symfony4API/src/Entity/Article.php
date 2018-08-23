<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @Groups("user")
     * @Groups("article")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;
    /**
     * @Groups("user")
     * @Groups("article")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $description;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     */
    private $user;
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
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}