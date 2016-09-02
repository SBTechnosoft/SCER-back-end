<?php
namespace ERP\Core\Sample\Persistables;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class DocumentPersistable
{
    /**
     * @var $location
     * @var $suggestedName
     */
    private $location;
	private $suggestedName;

    /**
     * @param string $name
     */
    public function setSuggestedName($name)
    {
        $this->suggestedName = $name;
	}

    /**
     * @return string suggestedName
     */
    public function getSuggestedName()
    {
        return $this->suggestedName;
    }

    /**
     * @param string location
     */
    public function setLocation($location)
    {
        $this->location = $location;
	}

    /**
     * @return string location
     */
    public function getLocation()
    {
        return $this->location;
    }
} 