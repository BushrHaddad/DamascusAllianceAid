<?php

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\Authentication\AuthenticationProviders\LocalAuthentication;
// use ChurchCRM\dto\Cart;
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\view\MenuRenderer;


//
// Turn ON output buffering
ob_start();

require_once 'Header-function.php';
if (SystemConfig::debugEnabled()) {
    require_once 'Header-Security.php';
}

// Top level menu index counter
$MenuFirst = 1;
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="UTF-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <?php require 'Header-HTML-Scripts.php'; ?>
</head>

<body class="hold-transition <?= AuthenticationManager::GetCurrentUser()->getStyle() ?> sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <?php
  Header_modals();
  Header_body_scripts();

  $loggedInUserPhoto = SystemURLs::getRootPath().'/api/person/'.AuthenticationManager::GetCurrentUser()->getId().'/thumbnail';
  $MenuFirst = 1;
  ?>

  <header class="main-header">
    <!-- Logo -->
    <a href="<?= SystemURLs::getRootPath() ?>/Menu.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>C</b>RM</span>
      <!-- logo for regular state and mobile devices -->
      <?php
      $headerHTML = '<b>Alliance</b>Aid';
      $sHeader = SystemConfig::getValue("sHeader");
      if (!empty($sHeader)) {
          $headerHTML = html_entity_decode($sHeader, ENT_QUOTES);
      }
      ?>
      <span class="logo-lg"><?= $headerHTML ?></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only"><?= gettext('Toggle navigation') ?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    <i class="flag-icon flag-icon-squared"></i>
                </a>
                <ul class="dropdown-menu">
                    <li class="header">
                        <span>
                            <span id="translationInfo"></span>
                            <?php if (AuthenticationManager::GetCurrentUser()->isAdmin()) { ?>
                            <a href="<?= SystemURLs::getRootPath()?>/SystemSettings.php"> <i class="fa fa-pencil"></i></a>
                            <?php } ?>
                        </span>
                    </li>
                    <li id="localePer" class="header hidden">
                        <span id="translationPer"></span> <?= gettext("of translation completed")?>
                    </li>
                    <li class="footer">
                        <a href="https://poeditor.com/join/project?hash=RABdnDSqAt" target="poeditor"><?= gettext("Help translate this project")?></a>
                    </li>
                </ul>
            </li>
           

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" id="dropdown-toggle" data-toggle="dropdown" title="<?= gettext('Your settings and more') ?>">
              <img src="<?= SystemURLs::getRootPath()?>/api/person/<?= AuthenticationManager::GetCurrentUser()->getPersonId() ?>/thumbnail" class="user-image initials-image" alt="User Image">
              <span class="hidden-xs"><?= AuthenticationManager::GetCurrentUser()->getName() ?> </span>

            </a>
            <ul class="hidden-xxs dropdown-menu">
              <li class="user-header" id="yourElement" style="height:auto">
                <table border=0 width="100%">
                <tr style="border-bottom: 1pt solid white;">
                <td valign="middle" width=110></td>
                <td valign="middle" align="left" >
                  <a href="<?= SystemURLs::getRootPath()?>/PersonView.php?PersonID=<?= AuthenticationManager::GetCurrentUser()->getPersonId() ?>" class="item_link">
                      <p ><i class="fa fa-home"></i> <?= gettext("Profile") ?></p></a>
                  <a href="<?= SystemURLs::getRootPath() ?>/v2/user/current/changepassword" class="item_link" id="change-password">
                      <p ><i class="fa fa-key"></i> <?= gettext('Change Password') ?></p></a>
                  <a href="<?= SystemURLs::getRootPath() ?>/v2/user/<?= AuthenticationManager::GetCurrentUser()->getPersonId() ?>" class="item_link">
                      <p ><i class="fa fa-gear"></i> <?= gettext('Change Settings') ?></p></a>
                  <?php
                    if (LocalAuthentication::GetIsTwoFactorAuthSupported()) {
                        ?>
                  <a href="<?= SystemURLs::getRootPath() ?>/v2/user/current/enroll2fa" class="item_link">
                      <p ><i class="fa fa-gear"></i> <?= gettext("Manage 2 Factor Authentication") ?></p></a>
                  <?php
                    }
                  ?>
                  <a href="<?= SystemURLs::getRootPath() ?>/session/end" class="item_link">
                      <p ><i class="fa fa-sign-out"></i> <?= gettext('Sign out') ?></p></a>
                </td>
                </tr>
                </table>
                <p style="color:#fff"><b><?= AuthenticationManager::GetCurrentUser()->getName() ?></b></p>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>
  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">

        <select class="form-control multiSearch">
        </select>

      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <?php MenuRenderer::RenderMenu(); ?>
      </ul>
    </section>
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <h1><?= $sPageTitle; ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
