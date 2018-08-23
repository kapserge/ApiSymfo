<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @SWG\Property(description="The unique identifier of the user.")
     */
    private $id;
    /**
     *
     * @Groups("user")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $firstname;
    /**
     * @Groups("user")
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(type="string", maxLength=255)
     * @Assert\NotBlank()
     */
    private $lastname;
    /**
     * @Groups("user")
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(type="string", maxLength=255)
     *  @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $email;
    /**
     * @Groups("user")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthday;
    /**
     * @ORM\Column(type="simple_array")
     */
    private $roles;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @SWG\Property(type="string", maxLength=255)
     */
    private $apiKey;
    /**
     * @Groups("user")
     * @Groups("article")
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $articles;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function __construct()
    {
        $this->roles = array('ROLE_USER');
        $this->apiKey = uniqid('', true);
        $this->articles = new ArrayCollection();
    }
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }
    public function getLastname(): ?string
    {
        return $this->lastname;
    }
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }
    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;
        return $this;
    }
    public function getRoles(): ?array
    {
        return $this->roles;
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }
    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }
    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }
        return $this;
    }
    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }
        return $this;
    }
    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }
    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return $this->email;
    }
    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}