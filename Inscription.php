

<?php

//On saisit les valeurs de notre Base de données
$bdd = new PDO ('mysql:host=localhost;port=3306;dbname=espace_membre','root', 'root');

//On vérifie que le formulaire a été envoyé
if(isset($_POST['forminscription'])) {
   //On sécurise les données 
   $pseudo = htmlspecialchars($_POST['pseudo']);
   $mail = htmlspecialchars($_POST['mail']);
   $mail2 = htmlspecialchars($_POST['mail2']);
   $mdp = sha1($_POST['mdp']);
   $mdp2 = sha1($_POST['mdp2']);
   if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) {
      //On vérifie si le pseudo fait moins de 255 caractères
      $pseudolength = strlen($pseudo);
      if($pseudolength <= 255) {
          //On vérifie si l'adresse mail et celui de la vérification sont identiques et valide
         if($mail == $mail2) {
            if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
               $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
               $reqmail->execute(array($mail));
               $mailexist = $reqmail->rowCount();
               if($mailexist == 0) {
                   //On vérifie si le mot de passe et celui de la vérification sont identiques
                  if($mdp == $mdp2) {
                     $insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, mail, motdepasse) VALUES(?, ?, ?)");
                     $insertmbr->execute(array($pseudo, $mail, $mdp));

                     //Sinon, on dit que le compte a été crée
                     $erreur = "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";

                  } else {//Sinon, on dit que les mots de passes ne sont pas identiques
                     $erreur = "Vos mots de passes ne correspondent pas !";
                  }
               } else {//Sinon, on dit que l'adresse mail est utilisée par un autre utilisateur
                  $erreur = "Adresse mail déjà utilisée !";
               }
            } else {//Sinon, on dit que l'adresse mail saisi n'est pas valide
               $erreur = "Votre adresse mail n'est pas valide !";
            }
         } else {//Sinon, on dit que les adresses mails ne sont pas identiques
            $erreur = "Vos adresses mail ne correspondent pas !";
         }
      } else {//Sinon, on dit que le pseudo à dépasser le nombre de caractères maximales
         $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
      }
   } else {//Sinon, on dit que tous les champs ne sont pas complétés
      $erreur = "Tous les champs doivent être complétés !";
   }
}
//On affiche le formulaire
?>
<html>
   <head>
      <title>Projet Personnel</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div align="center">
         <h2>Inscription</h2>
         <br /><br />
         <form method="POST" action="">
            <table>
               <tr>
                  <td align="right">
                     <label for="pseudo">Pseudo :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail">Mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Votre mail" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail2">Confirmation du mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp">Mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp2">Confirmation du mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" />
                  </td>
               </tr>
               <tr>
                  <td></td>
                  <td align="center">
                     <br />
                     <input type="submit" name="forminscription" value="Je m'inscris" />
                  </td>
               </tr>
            </table>
         </form>
         <?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         ?>
      </div>
   </body>
</html>