<div class="view">
<?php
$data = json_decode($json_data);

if($data){
    echo '<fieldset><legend>'.$title.'</legend><table>';
    foreach ($data as $key => $value) { ?>
        <tr>
            <th><?php  echo $key; ?></th>
            <td><?php  echo $value; ?></td>
        </tr>
   <?php }
   echo '</table></fieldset>';
}
?>
</div>