

        This is the page of <b><?=$userdata['nickname']?></b>
        <br>
        
    <?php
        if ( $userdata['colour'] ) :
    ?>
        
        <br>
        <?=$userdata['nickname']?>'s favourite colour is <i> <?=$userdata['colour']?> </i>
    <?php
        endif;
        if ( $userdata['pet'] ) :
    ?>
            
        <br>
        <?=$userdata['nickname']?>'s favourite pet is <i> <?=$userdata['pet']?> </i>
    <?php
        endif;
    ?>


    <?php
        if ( !empty($messages) && $its_me ) :
    ?>
        <form method="POST" action="<?=$msg_url?>">
            <input type="hidden" name="method" value="DELETE">
            <input type="hidden" name="id" value="0">
        </form>
        <script type="text/javascript">
            function delete_msg (id) {
                var form = document.forms[document.forms.length - 1];
                form.id.value = id;
                form.submit();
            }
        </script>
    <?php
        endif;
    ?>
        
        <div class="user_list"> 

    <?php
        for ( $msg_count = 0; $msg_count < count($messages); $msg_count++ ) :
            $silver = ($msg_count % 2) ? "silver" : "";
            $sender_profile = BASE_URL . "user/" . $messages[$msg_count]['sender'] . "/" . preg_replace("/\W+/", "", $messages[$msg_count]['nickname']);
    ?>
        
            <p class="msg_paragraph <?=$silver?>">
                <span class="sender_identification" onclick="location.href = '<?=$sender_profile?>'">
                    &nbsp;&nbsp;
                    <?=$messages[$msg_count]['nickname']?> 
                </span>
                <?= nl2br(htmlspecialchars($messages[$msg_count]['msg'])) ?>
        <?php
            if ( $its_me ) :
        ?>
                
                <b class="delete_bt" title="DELETE" onclick="delete_msg(<?=$messages[$msg_count]['id']?>)">
                    &nbsp; X &nbsp;
                </b>
        <?php
            endif;
        ?>    
            </p>
        
    <?php
        endfor;
    ?>
  
        </div>
        
        
    <?php
        if ( $logged_in && !$its_me ) :
    ?>
        
        <div class="user_list">
            <small>
                Write a message to <i><?=$userdata['nickname']?></i>...
            </small>
            <br>
            <form method="POST" action="<?=$msg_url?>">
                <textarea name="msg" cols="40" rows="8"></textarea>
                <br>
                <input type="submit" value=" Send ">
            </form>
        </div>
    <?php
        endif;
    ?>  
        