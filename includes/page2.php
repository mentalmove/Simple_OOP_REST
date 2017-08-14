<?php
   class Page2 {
       
       private function tell_success ($method) {
           return "This is <i>" . $method . "()</i> from <b>Page2</b>";
       }
       
       public function post () {
           return $this->tell_success("post");
       }
       /**
        * Shall not exist
        */
       //public function get () {}
   }
?>
