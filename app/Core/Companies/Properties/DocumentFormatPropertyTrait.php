<?php
namespace ERP\Core\Companies\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait DocumentFormatPropertyTrait
{
	/**
     * @var documentFormat
     */
    private $documentFormat;
	/**
	 * @param int $documentFormat
	 */
	public function setDocumentFormat($documentFormat)
	{
		$this->documentFormat = $documentFormat;
	}
	/**
	 * @return documentFormat
	 */
	public function getDocumentFormat()
	{
		return $this->documentFormat;
	}
}