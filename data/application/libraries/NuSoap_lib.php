<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class NuSoap_lib 
{
    public function nusoap_lib()
    {
        require_once(realpath(APPPATH . '/libraries/NuSOAP/lib/nusoap'));
    }        
    
}

?>
