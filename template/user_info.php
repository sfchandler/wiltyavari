<span> <!-- User image size is adjusted inside CSS -->
    <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
        <?php if(!empty(getAvatarImage($mysqli,$_SESSION['userSession']))){ ?>
            <img src="img/avatars/<?php echo getAvatarImage($mysqli,$_SESSION['userSession']);?>" alt="<?php echo $_SESSION['userSession']; ?>" class="online" />
        <?php } ?>
        <span>
            <?php echo $_SESSION['userSession']; ?>
        </span>
        <i class="fa fa-angle-down"></i>
    </a>
</span>
