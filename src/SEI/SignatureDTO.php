<?php

namespace xIDMONx\SEI;

/**
 * Class SignatureDTO
 *
 * @author  xIDMONx
 * @package xIDMONx\SEI
 */
class SignatureDTO
{
    /**
     * @var string
     */
    private $id;
    
    /**
     * @var string
     */
    private $signature;
    
    /**
     * @var string
     */
    private $cuts;
    
    /**
     * @var string
     */
    private $xml;
    
    /**
     * @var array
     */
    private $documento;
    
    /**
     * @var array
     */
    private $hash;
    
    /**
     * @var string
     */
    private $subject;
    
    /**
     * @var string
     */
    private $documentName;
    
    /**
     * @var string
     */
    private $application;
    
    /**
     * @var string
     */
    private $requester;
    
    /**
     * @var string
     */
    private $requestDate;
    
    /**
     * @var string
     */
    private $endDate;
    
    /**
     * @var int
     */
    private $DocumentType;
    
    /**
     * @var array
     */
    private $signers;
    
    /**
     * @var string
     */
    private $applicant;
    
    public function __construct() { }
    
    /**
     * @return string
     */
    public function getId()
    : string
    {
        return $this->id;
    }
    
    /**
     * @param string $id
     */
    public function setId( string $id )
    : void
    {
        $this->id = $id;
    }
    
    /**
     * @return string
     */
    public function getSignature()
    : string
    {
        return $this->signature;
    }
    
    /**
     * @param string $signature
     */
    public function setSignature( string $signature )
    : void
    {
        $this->signature = $signature;
    }
    
    /**
     * @return string
     */
    public function getCuts()
    : string
    {
        return $this->cuts;
    }
    
    /**
     * @param string $cuts
     */
    public function setCuts( string $cuts )
    : void
    {
        $this->cuts = $cuts;
    }
    
    /**
     * @return string
     */
    public function getXml()
    : string
    {
        return $this->xml;
    }
    
    /**
     * @param string $xml
     */
    public function setXml( string $xml )
    : void
    {
        $this->xml = $xml;
    }
    
    /**
     * @return array
     */
    public function getDocumento()
    : array
    {
        return $this->documento;
    }
    
    /**
     * @param array $documento
     */
    public function setDocumento( array $documento )
    : void
    {
        $this->documento = $documento;
    }
    
    /**
     * @return array
     */
    public function getHash()
    : array
    {
        return $this->hash;
    }
    
    /**
     * @param array $hash
     */
    public function setHash( array $hash )
    : void
    {
        $this->hash = $hash;
    }
    
    /**
     * @return string
     */
    public function getSubject()
    : string
    {
        return $this->subject;
    }
    
    /**
     * @param string $subject
     */
    public function setSubject( string $subject )
    : void
    {
        $this->subject = $subject;
    }
    
    /**
     * @return string
     */
    public function getDocumentName()
    : string
    {
        return $this->documentName;
    }
    
    /**
     * @param string $documentName
     */
    public function setDocumentName( string $documentName )
    : void
    {
        $this->documentName = $documentName;
    }
    
    /**
     * @return string
     */
    public function getApplication()
    : string
    {
        return $this->application;
    }
    
    /**
     * @param string $application
     */
    public function setApplication( string $application )
    : void
    {
        $this->application = $application;
    }
    
    /**
     * @return string
     */
    public function getRequester()
    : string
    {
        return $this->requester;
    }
    
    /**
     * @param string $requester
     */
    public function setRequester( string $requester )
    : void
    {
        $this->requester = $requester;
    }
    
    /**
     * @return string
     */
    public function getRequestDate()
    : string
    {
        return $this->requestDate;
    }
    
    /**
     * @param string $requestDate
     */
    public function setRequestDate( string $requestDate )
    : void
    {
        $this->requestDate = $requestDate;
    }
    
    /**
     * @return string
     */
    public function getEndDate()
    : string
    {
        return $this->endDate;
    }
    
    /**
     * @param string $endDate
     */
    public function setEndDate( string $endDate )
    : void
    {
        $this->endDate = $endDate;
    }
    
    /**
     * @return int
     */
    public function getDocumentType()
    : int
    {
        return $this->DocumentType;
    }
    
    /**
     * @param int $DocumentType
     */
    public function setDocumentType( int $DocumentType )
    : void
    {
        $this->DocumentType = $DocumentType;
    }
    
    /**
     * @return array
     */
    public function getSigners()
    : array
    {
        return $this->signers;
    }
    
    /**
     * @param array $signers
     */
    public function setSigners( array $signers )
    : void
    {
        $this->signers = $signers;
    }
    
    /**
     * @return string
     */
    public function getApplicant()
    : string
    {
        return $this->applicant;
    }
    
    /**
     * @param string $applicant
     */
    public function setApplicant( string $applicant )
    : void
    {
        $this->applicant = $applicant;
    }
}
