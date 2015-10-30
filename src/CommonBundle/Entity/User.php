<?php

namespace CommonBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use CommonBundle\Utils\McryptHelper;

class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $adminId;

    /**
     * @var string
     */
    private $account;

    /**
     * @var string
     */
    private $passwd;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $role;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     */
    private $deletedAt;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        //...
    }
    
    /**
     * Get User ID
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->adminId;
    }
    
    /**
     * Get adminId
     *
     * @return integer 
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * Set account
     *
     * @param string $account
     * @return Admin
     */
    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Get account
     *
     * @return string 
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set password
     *
     * @param string $passwd
     * @return Admin
     */
    public function setPasswd($passwd)
    {
        $this->passwd = $passwd;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPasswd()
    {
        return $this->passwd;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return Admin
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Admin
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Admin
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Admin
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Admin
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
    
    /**
     * Returns the username used to authenticate the user.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->getAccount();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     * This can return null if the password was not encoded using a salt.
     * 
     * @return string|null
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return 'admin';
    }

    /**
     * Returns the roles granted to the user.
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return array($this->getRole());
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
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 100);
        $decryptPasswd = McryptHelper::mcryptDecrypt($this->getPasswd());
        $passwd = $encoder->encodePassword($decryptPasswd, $this->getSalt());
        return $passwd;
    }

    /**
     * Returns the password setted.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function setPassword($passwd)
    {
        return McryptHelper::mcryptEncrypt($passwd);
    }

    public function eraseCredentials()
    {
    }
    
    /**
     * 
     * @return boolean
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->deletedAt===null ? true : false;
    }

    // serialize and unserialize must be updated - see below
    public function serialize()
    {
        return serialize(array(
            $this->adminId,
            $this->account,
            $this->passwd,
        ));
    }
    
    public function unserialize($serialized)
    {
        list (
            $this->adminId,
            $this->account,
            $this->passwd,
        ) = unserialize($serialized);
    }
}
