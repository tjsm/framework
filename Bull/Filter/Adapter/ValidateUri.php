<?php                                                       
/**                                                         
 *                                                          
 * Validates that a value is a URI (or URL).                
 *                                                          
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                          
 */

class Bull_Filter_Adapter_ValidateUri extends Bull_Filter_Adapter_Abstract
{
    /**                                                                                           
     *                                                                                            
     * Validates the value as a URI.                                                              
     *                                                                                            
     * The value must match a generic URI format; for example,                                    
     * ``http://example.com``, ``mms://example.org``, and so on.                                  
     *                                                                                            
     * @param mixed $value The value to validate.                                                 
     *                                                                                            
     * @return bool True if valid, false if not.                                                  
     *                                                                                            
     */
    public function __invoke($value)
    {
        if ($this->objManager->validateBlank($value)) {
            return ! $this->objManager->getRequire();
        }

        // first, make sure there are no invalid chars, list from ext/filter                      
        $other = "$-_.+"        // safe                                                           
            . "!*'(),"       // extra                                                          
            . "{}|\\^~[]`"   // national                                                       
            . "<>#%\""       // punctuation                                                    
            . ";/?:@&=";     // reserved                                                       

        $valid = 'a-zA-Z0-9' . preg_quote($other, '/');
        $clean = preg_replace("/[^$valid]/", '', $value);
        if ($value != $clean) {
            return false;
        }

        // now make sure it parses as a URL with scheme and host                                  
        $result = @parse_url($value);
        if (empty($result['scheme']) || trim($result['scheme']) == '' ||
            empty($result['host'])   || trim($result['host']) == '') {
            // need a scheme and host                                                             
            return false;
        } else {
            // looks ok                                                                           
            return true;
        }
    }
}
