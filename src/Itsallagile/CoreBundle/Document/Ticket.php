<?php

namespace Itsallagile\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/** 
 * @MongoDB\EmbeddedDocument 
 */
class Ticket
{
    const STATUS_NEW = 'new';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_REVIEW = 'review';
    const STATUS_DONE = 'done';

    /**
     * @JMS\Exclude     
     */
    protected static $statuses = array(
        self::STATUS_NEW => 'New',
        self::STATUS_ASSIGNED => 'Assigned',
        self::STATUS_DONE => 'Done'
    );

    /**
     * Get the possible statuses for a ticket
     * @return array
     */
    public static function getStatuses()
    {
        return self::$statuses;
    }

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
    }

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\Field(type="string")
     */
    protected $type;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @Assert\NotBlank()
     * @MongoDB\Field(type="string")
     */
    protected $status;

    /**
     * @MongoDB\EmbedMany(targetDocument="TicketHistory")
     */
    protected $history = array();

    /**
     * @MongoDB\ReferenceOne(targetDocument="User", simple="true")
     * @JMS\Accessor(getter="getAssignedUserDetails")
     */
    protected $assignedUser;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $created;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $modified;

    /**
     * Get id
     *
     * @return custom_id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Ticket
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Ticket
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Ticket
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     */
    public function setModified(\DateTime $modified)
    {
        $this->modified = $modified;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Add History
     *
     * @param TicketHistory $history
     */
    public function addHistory(TicketHistory $history)
    {
        $this->history[] = $history;
    }

    /**
     * Get history
     *
     * @return Doctrine\Common\Collections\Collection $history
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Assign user to the ticket
     *
     * @param User $user
     * @return Ticket
     */
    public function setAssignedUser(User $user)
    {
        $this->assignedUser = $user;
        return $this;
    }

    /**
     * Unassign an assigned user from the ticket
     *
     * @return Ticket
     */
    public function unassignUser()
    {
        $this->assignedUser = null;
        return $this;
    }

    /**
     * Get the full name of the assigned user
     *
     * @return string
     */
    public function getAssignedUserName()
    {
        return $this->assignedUser->getFullName();
    }

    /**
     * Get the Assigned User
     *
     * @return User
     */
    public function getAssignedUser()
    {
        return $this->assignedUser;
    }

    /**
     * Get relevant Assigned User details
     * so we don't return the whole object (with password/salt hash, etc)
     *
     * @return array
     */
    public function getAssignedUserDetails()
    {
        if (!$this->assignedUser) {
            return;
        }

        $user = array();
        $user['id'] = $this->assignedUser->getId();
        $user['name'] = $this->assignedUser->getFullName();
        return $user;
    }
}
