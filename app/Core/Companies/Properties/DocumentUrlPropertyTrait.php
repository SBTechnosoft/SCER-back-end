<?php
namespace ERP\Core\Companies\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait DocumentUrlPropertyTrait
{
	/**
     * @var documentUrl
     */
    private $documentUrl;
	/**
	 * @param int $documentUrl
	 */
	public function setDocumentUrl($documentUrl)
	{
		$this->documentUrl = $documentUrl;
	}
	/**
	 * @return documentUrl
	 */
	public function getDocumentUrl()
	{
		return $this->documentUrl;
	}
}