
<?php
//On démarre les sessions
session_start();
//On se connecte a la base de donnée
mysql_connect('localhost', 'root', 'root');
mysql_select_db('espace_membre');
//On saisit les valeurs de notre Base de données
$bdd = new PDO ('mysql:host=localhost;port=3306;dbname=espace_membre','root', 'root');
//On vérifie que le formulaire a été envoyé
if(isset($_POST['formconnexion'])) {
   //On sécurise les données 
   $mailconnect = htmlspecialchars($_POST['mailconnect']);
   $mdpconnect = sha1($_POST['mdpconnect']);
   if(!empty($mailconnect) AND !empty($mdpconnect)) {
      $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND motdepasse = ?");
      $requser->execute(array($mailconnect, $mdpconnect));
      $userexist = $requser->rowCount();
      //On vérifie que l'utilisateur existe
         if($userexist == 1) {
         $userinfo = $requser->fetch();
         $_SESSION['id'] = $userinfo['id'];
         $_SESSION['pseudo'] = $userinfo['pseudo'];
         $_SESSION['mail'] = $userinfo['mail'];
         header("Location: profil.php?id=".$_SESSION['id']);
      } else {//Sinon, on dit que l'adresse mail et le mot sont erronés
         $erreur = "Mauvais mail ou mot de passe !";
      }
   } else {//Sinon, on dit que tous les champs ne sont pas completés
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
         <h2>Connexion</h2>
         <br /><br />
         <form method="POST" action="">
            <input type="email" name="mailconnect" placeholder="Mail" />
            <input type="password" name="mdpconnect" placeholder="Mot de passe" />
            <br /><br />
            <input type="submit" name="formconnexion" value="Se connecter !" />
         </form>
         <?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         ?>
      </div>
   </body>
</html>