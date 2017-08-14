<?php    
    function find_controller ($classname) {
        $file = "includes/" . strtolower($classname) . ".php";
        if ( file_exists($file) ) {
            include_once $file;
        }
        else
            throw new Exception( "File <b>" . $file . "</b> not found" );
    }
    spl_autoload_register("find_controller");
    
    define("BASE_URL", str_replace("index.php", "", $_SERVER['SCRIPT_NAME']));
    
    $headline = "Click a link below";
    
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    $page = explode("/", trim($_SERVER['PATH_INFO'], "/"))[0];
    
    if ( $page ) {
        $class_name = ucfirst($page);
        try {
            $control = new $class_name();
            if ( is_callable( Array($control, $method) ) )
                $headline = $control->$method();
            else
                $headline = "<b>" . $class_name . "</b> has no method <i>" . $method . "()</i>";
        } catch (Exception $ex) {
            $headline = $ex->getMessage();
        }
    }
    
?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Representational State Transfer</title>
        <style type="text/css">
            html, body {
                margin: 20px;
                background-color: #F8F8F8;
                font-family: Arial, Verdana;
                font-size: 20px;
                text-align: center;
            }
            .expectation {
                position: relative;
                margin-left: 20px;
            }
            .explanation {
                position: absolute;
                top: 0px;
                right: -240px;
                width: 240px;
                color: gray;
            }
            a:link {
                color: #707868;
                text-decoration: none;
            }
            a:visited {
                color: #707868;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        
        <h2><?=$headline?></h2>
        
        <br><br>
        
        <a href="<?=BASE_URL?>page1">page1 GET</a>
        <small class="expectation">
            &nbsp;&nbsp;&nbsp;&nbsp;
            will succeed
        </small>
        
        <br>
        
        <form method="POST" action="<?=BASE_URL?>page1">
            <a href="" onclick="this.parentNode.submit(); return false">page1 POST</a>
            <small class="expectation">
                &nbsp;&nbsp;
                will succeed
            </small>
        </form>
        
        <a href="<?=BASE_URL?>page2">page2 GET</a>
        <small class="expectation">
            will not succeed
            <div class="explanation">
                no method <i>get()</i>
            </div>
        </small>
        
        <br>
        
        <form method="POST" action="<?=BASE_URL?>page2">
            <a href="" onclick="this.parentNode.submit(); return false">page2 POST</a>
            <small class="expectation">
                &nbsp;&nbsp;
                will succeed
            </small>
        </form>
        
        <a href="<?=BASE_URL?>abc/de">abc/de GET</a>
        <small class="expectation">
            will not succeed
            <div class="explanation">
                no class <b>Abc</b>
                &nbsp;&nbsp;&nbsp;&nbsp;
            </div>
        </small>
        
        <br>
        
        <a href="<?=BASE_URL?>folder1/folder2/file.html" onclick="open(this.href, 'example', 'width=320,height=240'); return false">folder1/folder2/file.html GET</a>
        
    </body>
</html>
