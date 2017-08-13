        
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?=$id?>">
            <input type="hidden" name="method" value="<?=$method?>">
            <table align="center" width="50%" cellpadding="12" border="0">
                <tr>
                    <td>
                        Nickname
                    </td>
                    <td>
                        <input type="text" name="nickname" size="40" <?=$name_disabled?> value="<?=$userdata['nickname']?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Favourite colour
                    </td>
                    <td>
                        <input type="text" name="colour" size="40" value="<?=$userdata['colour']?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Favourite pet
                    </td>
                    <td>
                        <input type="text" name="pet" size="40" value="<?=$userdata['pet']?>">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" value=" Send ">
                    </td>
                </tr>
            </table>
        </form>