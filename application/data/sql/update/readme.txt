Les scripts SQL de mise à jour de la base de données se situent dans ce répertoire.

Nommage des scripts
===================

Ils sont nommés de la manière suivante : "xxxx - Description.sql". XXXX est un
numéro séquentiel permettant de classer les scripts par ordre chronologique.
"Description" est une courte description des actions effectuées par le script.

Structure des scripts
=====================

Afin d'éviter les problèmes en cas d'erreur lors de l'exécution d'un script,
celui-ci devra être exécuté dans une transaction.

Pour cela, le script doit avoir la structure suivante :

    SET FOREIGN_KEY_CHECKS=0;
    SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
    SET AUTOCOMMIT=0;
    START TRANSACTION;
    
    // Code SQL du script
    
    UPDATE `application` SET `value` = 'xxxx' WHERE `key` = 'lastSqlScriptRun';   
    SET FOREIGN_KEY_CHECKS=1;
    COMMIT;
    
Afin de simplifier la mise à jour des scripts, la troisième ligne en partant de
la fin insère le numéro du script courant dans la clé "lastSqlScriptRun" de la
table "application". Le "xxxx" correspond au numéro du script tel qu'indiqué
dans le nom du fichier.
    
Modification des scripts
========================

Une fois poussé vers un dépôt extérieur, un script SQL ne doit plus être modifié
car il a pu être exécuté ailleurs. Si on souhaite modifier de nouveau la base de
données, il faut le faire avec un nouveau script SQL.

Le seul cas où la correction d'un script SQL déjà poussé dans un autre dépôt est
quand le script comporte une erreur empêchant sa bonne exécution. Dans ce cas,
il faut le modifier et prévenir le reste de l'équipe pour qu'ils gèrent le
problème manuellement.

Vidage du cache de base de données
==================================

Après l'exécution de scripts SQL, il faut supprimer les fichiers du cache de
base de données qui se trouvent dans le répertoire /data/cache/db. 