<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?= $url ?>"><?= $core->getSetting('web_name')->getValue(); ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Berichten <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?= $url ?>/index.php">Nieuw bericht</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="<?= $url ?>/edit.php">Lijst met berichten</a></li>
          </ul>
        </li>
        <?php if($user->getAdmin() >= 1) { ?>
        <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gebruikers <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="<?= $url ?>/user/add.php">Gebruiker toevoegen</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="<?= $url ?>/user/index.php">Lijst gebruikers</a></li>
        </ul>
        </li>
        <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Rubrieken <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="<?= $url ?>/rubric/add.php">Rubriek toevoegen</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="<?= $url ?>/rubric/index.php">Lijst Rubrieken</a></li>
        </ul>
        </li>
        <li><a href="<?= $url ?>/settings">Instellingen</a></li>
        <?php } ?>
      </ul>
        
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $user->getName(); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?= $url ?>/user/settings.php">Instellingen</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="<?= $url ?>/logout.php">Log uit</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>