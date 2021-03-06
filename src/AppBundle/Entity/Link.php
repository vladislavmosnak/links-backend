<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
//TODO add tags from meta:keywords
/**
 * Link
 *
 * @ORM\Table(name="link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LinkRepository")
 */
class Link
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\LinkCategory", inversedBy="links")
     */
    private $category;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotNull();
     */
    private $url;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotNull();
     * @Assert\NotBlank()
     */
    private $title;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotNull();
     * @Assert\NotBlank()
     */
    private $description;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotNull();
     * @Assert\NotBlank()
     */
    private $image;


    /**
     * @ORM\Column(type="string")
     */
    private $author;


    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\LinkTags", mappedBy="link")
     */
    private $linkTag;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }


    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * @param mixed $deleted_at
     */
    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
    }

    /**
     * @return mixed
     */
    public function getLinkTag()
    {
        return $this->linkTag;
    }

    /**
     * @param mixed $linkTag
     */
    public function setLinkTag(LinkTags $linkTag)
    {
        $this->linkTag[] = $linkTag;
    }

}
