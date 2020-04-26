<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /** 
    * @Assert\Length(min=3, minMessage="Trois caractères au minimum pour le username", allowEmptyString=false,
    *                max=20, maxMessage="Username trop long (Pas plus de 20 caractères)")
    * @ORM\Column(type="string", length=20, unique=true)
    */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

     /** 
     * @Assert\NotBlank()
     * 
     * @Assert\Regex("/^\S*(?=\S{8,4096})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/")
     */
    private $plainPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^\w+(-)?\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/")
     * @ORM\Column(type="string", length=180)
     */
    private $email;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Publication", mappedBy="user")
     */
    private $publications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Friendship", mappedBy="user")
     */
    private $friends;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="user")
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NotifiedFriends", mappedBy="friend")
     */
    private $notifiedFriends;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->notifiedFriends = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection|Publication[]
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications[] = $publication;
            $publication->setUser($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->contains($publication)) {
            $this->publications->removeElement($publication);
            // set the owning side to null (unless already changed)
            if ($publication->getUser() === $this) {
                $publication->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Friendship[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(Friendship $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
            $friend->setUser($this);
        }

        return $this;
    }

    public function removeFriend(Friendship $friend): self
    {
        if ($this->friends->contains($friend)) {
            $this->friends->removeElement($friend);
            // set the owning side to null (unless already changed)
            if ($friend->getUser() === $this) {
                $friend->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NotifiedFriends[]
     */
    public function getNotifiedFriends(): Collection
    {
        return $this->notifiedFriends;
    }

    public function addNotifiedFriend(NotifiedFriends $notifiedFriend): self
    {
        if (!$this->notifiedFriends->contains($notifiedFriend)) {
            $this->notifiedFriends[] = $notifiedFriend;
            $notifiedFriend->setFriend($this);
        }

        return $this;
    }

    public function removeNotifiedFriend(NotifiedFriends $notifiedFriend): self
    {
        if ($this->notifiedFriends->contains($notifiedFriend)) {
            $this->notifiedFriends->removeElement($notifiedFriend);
            // set the owning side to null (unless already changed)
            if ($notifiedFriend->getFriend() === $this) {
                $notifiedFriend->setFriend(null);
            }
        }

        return $this;
    }

}
