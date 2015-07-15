<?php

class HotelsCURL{
	private $url;
	private $requestData;
	private $header;

    /**
     * Gets the value of url.
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the value of url.
     *
     * @param mixed $url the url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Gets the value of requestData.
     *
     * @return mixed
     */
    public function getRequestData()
    {
        return $this->requestData;
    }

    /**
     * Sets the value of requestData.
     *
     * @param mixed $requestData the request data
     *
     * @return self
     */
    public function setRequestData($requestData)
    {
        $this->requestData = $requestData;

        return $this;
    }

    /**
     * Gets the value of header.
     *
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Sets the value of header.
     *
     * @param mixed $header the header
     *
     * @return self
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    public function initCURL(){
    	return curl_init($this->url);
    }

    public function makeRequest($curlObj){
    	curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $this->requestData);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
        
        return curl_exec($curlObj);
        curl_close($curlObj);
    }
}

?>