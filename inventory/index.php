<?php
$consoleArr = array("NES", "SNES", "Nintendo 64", "Gamecube", "Wii", "Wii U", "Switch", "Gameboy", "Gameboy Color", "Gameboy Advance", 
                    "Nintendo DS", "Nintendo 3DS", "Playstaion", "Playstaion 2", "Playstation 3", "Playstaion 4", "PSP", "PS Vita", 
                    "Xbox", "Xbox 360", "Xbox One", "Sega Genesis", "Sega Saturn", "Sega Dreamcast", "Atari");

function generateConsoleSelectBox($arr){
    $html = "<select name='console-list'>
                <option value='0'>Select console:</option>";
    foreach($arr as $item){
        $itemLowerCase = strtolower($item);
        $html .= "<option value='{$itemLowerCase}'>$item</option>";
    }

    $html .= "</select>";

    return $html;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" type="text/css" href="styles/reset.css">
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <title>Gaming Generations : Inventory</title>
</head>
<div id="header">
    <img src=images/gg-logo.jpg>
    <h3>Gaming Generations Inventory</h3>
</div>
<body>
    <div class="column left">
        <div id="search-container">
            <p>Search for title:</p>
            <?php echo(generateConsoleSelectBox($consoleArr)); ?>
            <input type="text" placeholder="Enter UPC">
            <input type="submit" value="Submit">
        </div>
        <div id="list-container">
            <p>Filter</p>
            <ul class="console-ul">
            <?php
                foreach($consoleArr as $console){
                ?>
                <li><a href="#"><?php echo $console;?></a></li>
                <?php
                }
                ?>
            </ul>
            
        </div>
    </div>
    <div id="main-container" class="column right">
        <div class="info">
            <table class="info-pane">
                <form>
                    <tr>
                        <td><label for="upc">UPC:</label></td>
                        <td><input type="text" name="upc" id="upc" placeholder="Enter UPC"></td>
                    </tr>
                    <tr>
                        <td><label>Console:</label></td>
                        <td><?php echo(generateConsoleSelectBox($consoleArr)); ?></td>
                    </tr>
                    <tr>
                        <td><label for="item">Item:</label></td>
                        <td><input type="text" name="item" id="item" placeholder="Enter item"></td>
                    </tr>
                    <tr>
                        <td><label for="value">Gamestop Value:</label></td>
                        <td><input type="text" name="value" id="value" placeholder="Enter value"></td>
                    </tr>
                </form>
            </table>
        </div>
    </div>
</body>
</html>
