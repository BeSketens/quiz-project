<?php

function logout($newLocation)
{
    session_unset();
    session_destroy();
    header('Location: ' . $newLocation);
}

function displayForm(string $action = 'login')
{ ?>
    <form id="createLoginForm" action="../controller/formsController.script.php" method="POST">
        <?php if ($action == 'create') { ?>
            <label>
                Nom d'utilisateur :
                <input autocomplete="off" required type="text" name="username">
            </label>
            <br>
            Sexe :
            <label>
                <input type="radio" required name="sex" value="m"> M
            </label>
            <label>   
                <input type="radio" required name="sex" value="f"> F
            </label>
            <br>
        <?php }?>
        <label>
            Email :
            <input autocomplete="off" required type="email" name="email">
        </label>
        <br>
        <label>
            Mot de passe :
            <input autocomplete="off" required type="password" name="password">
        </label>
        <br>
        <?php if ($action == 'login') { ?>
            <button type="submit" name="login">Me connecter</button>
        <?php } else { ?>
            <button type="submit" name="create">Créer mon compte</button>
        <?php } ?>     
    </form>
<?php }
        
