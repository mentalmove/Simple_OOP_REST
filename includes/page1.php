<?php
   class Page1 {
       
       private function tell_success ($method) {
           return "This is <i>" . $method . "()</i> from <b>Page1</b>";
       }
       
       public function post () {
           return $this->tell_success("post");
       }
       public function get () {
           return $this->tell_success("get");
       }
   }
?>
