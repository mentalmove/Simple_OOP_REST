

        This is the home page
        
        <div class="user_list">
            <?php
                if ( empty($user_list) ) :
            ?>
            
            <a href="javascript: void(1*1)">No registered users yet!</a>
            
            <?php
                else :

                /**
                 * Probably easier to read and definitely shorter than jumping in and out of PHP
                 */
                $stars = ($actual_user == $user_list[0]['id']) ? " *** " : "";
                echo "<a href=\"" . $base_url . "user/" . $user_list[0]['id'] . "/" . preg_replace("/\W+/", "", $user_list[0]['nickname']) . "\">" . $stars . $user_list[0]['nickname'] . $stars . "</a>";
                for ( $user_count = 1; $user_count < count($user_list); $user_count++ ) {
                    $stars = ($actual_user == $user_list[$user_count]['id']) ? " *** " : "";
                    echo "<br><a href=\"" . $base_url . "user/" . $user_list[$user_count]['id'] . "/" . preg_replace("/\W+/", "", $user_list[$user_count]['nickname']) . "\">" . $stars . $user_list[$user_count]['nickname'] . $stars . "</a>";
                }

                endif;
            ?>
            
        </div>