<?php
    class Message {     
        function getElementById($id, $doc)
        {
            $xpath = new DOMXPath($doc);
            // Return the message with given id.
            return $xpath->query("//*[@id='$id']")->item(0);
        }
    }
?>